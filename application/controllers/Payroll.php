<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payroll extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Payroll_model');
        $this->load->model('Employee_model');
        $this->load->model('Attendance_model');
    }

    public function index() {
        $data['payrolls'] = $this->Payroll_model->get_all();
        $this->load_view('dashboard/payroll/index', $data);
    }

    public function generate() {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $month = $this->input->post('month');
            $year = $this->input->post('year');

            if (!is_numeric($month) || $month < 1 || $month > 12) {
                $this->session->set_flashdata('error', 'Invalid month input.');
                redirect('payroll/generate'); // Redirection handled appropriately
                return;
            }

            if (!is_numeric($year) || $year < 2000) {
                $this->session->set_flashdata('error', 'Invalid year input.');
                redirect('payroll/generate');
                return;
            }

            $employees = $this->Employee_model->get_all();
            foreach ($employees as $employee) {
                $summary = $this->Attendance_model->get_monthly_summary($employee['id'], $month, $year);

                $salary = $employee['salary'];
                $total_working_days = $this->get_total_working_days($month, $year);
                $daily_salary = $salary / $total_working_days;

                $present_days = $summary['total_present'];
                $leave_days = $summary['total_leave'];
                $absent_days = $total_working_days - ($present_days + $leave_days);

                $deductions = $absent_days * $daily_salary;
                $total_salary = $salary - $deductions;
                $payroll_data = [
                    'employee_id'   => $employee['id'],
                    'month'         => $month,
                    'year'          => $year,
                    'salary'        => $salary,
                    'bonus'         => 0,
                    'deductions'    => $deductions,
                    'total_salary'  => $total_salary,
                    'status'        => 'unpaid',
                ];

                $this->Payroll_model->create_or_update($payroll_data);
                
                $bulan_bahasa = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                ];
                
                $bulan = isset($bulan_bahasa[(int)$month]) ? $bulan_bahasa[(int)$month] : $month;
                
                if (isset($employee['user_id']) && $employee['user_id']) {
                    $message = "Penggajian untuk periode " . $bulan . " " . $year . " telah disiapkan. ";
                    $message .= "Gaji pokok: Rp " . number_format($salary, 0, ',', '.') . ". ";
                    
                    if ($deductions > 0) {
                        $message .= "Potongan: Rp " . number_format($deductions, 0, ',', '.') . " ";
                        $message .= "(" . $absent_days . " hari tidak hadir). ";
                    }
                    
                    $message .= "Total gaji: Rp " . number_format($total_salary, 0, ',', '.');
                    
                    $this->Notification_model->create_notification(
                        $message,
                        'payroll',
                        $employee['user_id'],
                        'payroll'
                    );
                }
            }

            $bulan_bahasa = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];

            $bulan = isset($bulan_bahasa[(int)$month]) ? $bulan_bahasa[(int)$month] : $month;
            $this->session->set_flashdata('success', 'Penggajian berhasil dibuat untuk periode ' . $bulan . ' ' . $year . '.');
        } else {
            $this->load_view('dashboard/payroll/generate');
        }
    }

    public function view($id) {
        $data['payroll'] = $this->Payroll_model->get_by_id($id);
        $this->load_view('dashboard/payroll/view', $data);
    }

    public function mark_as_paid($id) {
        $this->Payroll_model->update($id, ['status' => 'paid']);
        
        $this->load->model('Notification_model');
        
        $payroll = $this->Payroll_model->get_by_id($id);
        
        $employee = $this->Employee_model->get_by_id($payroll['employee_id']);
        
        // Get Indonesian month name
        $bulan_bahasa = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $bulan = isset($bulan_bahasa[(int)$payroll['month']]) ? $bulan_bahasa[(int)$payroll['month']] : $payroll['month'];
        
        // Create detailed notification message
        $message = "Halo " . $employee['full_name'] . ", gaji Anda untuk periode " . $bulan . " " . $payroll['year'] . " telah dibayarkan. ";
        $message .= "Total gaji: Rp " . number_format($payroll['total_salary'], 0, ',', '.') . ". ";
        
        if ($payroll['deductions'] > 0) {
            $message .= "Potongan: Rp " . number_format($payroll['deductions'], 0, ',', '.') . ". ";
        }
        
        $message .= "Silahkan periksa slip gaji Anda untuk detail lebih lanjut.";
        
        if (isset($employee['user_id']) && $employee['user_id']) {
            $this->Notification_model->create_notification(
                $message, 
                'payroll', 
                $employee['user_id'],
                'payroll/view/' . $id
            );
        }
        
        $this->session->set_flashdata('success', 'Gaji sudah dibayarkan dan notifikasi telah dikirim.');
        redirect('payroll');
    }
    

    private function get_total_working_days($month, $year) {
        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $working_days = 0;
        for ($day = 1; $day <= $days_in_month; $day++) {
            $weekday = date('N', strtotime("$year-$month-$day")); // 1 (Mon) to 7 (Sun)
            if ($weekday < 6) {
                $working_days++;
            }
        }
        return $working_days;
    }

    public function get_payroll_matrix() {
        $this->db->select('p.employee_id, p.month, p.year, p.total_salary, p.status, e.full_name');
        $this->db->from('payroll p');
        $this->db->join('employees e', 'e.id = p.employee_id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function monthly() {
        // Get the current logged-in user's employee ID
        $user_id = $this->session->userdata('user_id');
        
        // Get employee data for the current user using our model
        $employee = $this->Employee_model->get_by_user_id($user_id);
        
        if (!$employee) {
            show_error('Employee record not found for your account.');
            return;
        }
        
        $data['payrolls'] = $this->Payroll_model->get_by_employee_id($employee['id']);
        $data['employee'] = $employee;
        $this->load_view('dashboard/payroll/monthly', $data);
    }
}
