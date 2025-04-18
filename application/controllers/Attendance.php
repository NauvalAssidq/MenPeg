<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Attendance_model');
        $this->load->model('Employee_model');
        $this->load->model('Notification_model');
        $this->load->model('Leave_model');
        $this->load->library(['session']);
        $this->load->helper('url');

        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');
        $employee = $this->Employee_model->get_by_user_id($user_id);
    
        if (!$employee) {
            $this->session->set_flashdata('error', 'Employee record not found.');
            show_error('Employee record not found.', 404);
            return;
        }
    
        $data['employee'] = $employee;
        $data['records'] = $this->Attendance_model->get_all_by_employee($employee['id']);
    
        if ($this->session->userdata('role') === 'admin') {
            $date = $this->input->get('date') ?? date('Y-m-d');
            $data['date'] = $date;
    
            $employees = $this->Employee_model->get_all();
            $attendance_records = $this->Attendance_model->get_all_attendance_by_date($date);
    
            $indexed = [];
            foreach ($attendance_records as $record) {
                $indexed[$record['employee_id']] = $record;
            }
    
            $recap = [];
            foreach ($employees as $emp) {
                $record = $indexed[$emp['id']] ?? null;
                $checkin = $record['clock_in_time'] ?? null;
                $checkout = $record['clock_out_time'] ?? null;
            
                if ($this->Leave_model->is_on_leave($emp['id'], $date)) {
                    $status = 'Cuti';
                    $status_icon = 'event_available';
                    $status_class = 'bg-yellow-100 text-yellow-800';
                } elseif ($checkin && $checkout) {
                    $status = 'Hadir';
                    $status_icon = 'check_circle';
                    $status_class = 'bg-green-100 text-green-800';
                } elseif ($checkin && !$checkout) {
                    $status = 'Belum Checkout';
                    $status_icon = 'warning';
                    $status_class = 'bg-orange-100 text-orange-800';
                } else {
                    $status = 'Absen';
                    $status_icon = 'cancel';
                    $status_class = 'bg-red-100 text-red-800';
                }
            
                $recap[] = [
                    'employee'    => $emp,
                    'checkin'     => $checkin,
                    'checkout'    => $checkout,
                    'status'      => $status,
                    'status_icon' => $status_icon,
                    'status_class'=> $status_class
                ];
            }
            $data['recap'] = $recap;
        }
        $this->load_view('dashboard/attendance/index', $data);
    }
    
    public function checkin() {
        $user_id = $this->session->userdata('user_id');
        $employee = $this->Employee_model->get_by_user_id($user_id);
    
        if (!$employee) {
            $this->session->set_flashdata('error', 'Employee record not found.');
            redirect('attendance');
            return;
        }
    
        $employee_id = $employee['id'];
        $today = date('Y-m-d');
    
        $this->load->model('Leave_model');
        if ($this->Leave_model->is_on_leave($employee_id, $today)) {
            $this->session->set_flashdata('error', 'Anda sedang cuti hari ini dan tidak dapat melakukan check in.');
            redirect('attendance');
            return;
        }
    
        $record = $this->Attendance_model->get_by_employee_date($employee_id, $today);
        if ($record) {
            $this->session->set_flashdata('error', 'Anda sudah melakukan check in hari ini.');
            redirect('attendance');
            return;
        }
    
        $data = [
            'employee_id'   => $employee_id,
            'date'          => $today,
            'clock_in_time' => date('Y-m-d H:i:s'),
            'status'        => 'present'
        ];
    
        $this->Attendance_model->insert($data);
    
        $message = $employee['full_name'] . " melakukan check in pada pukul " . date('H:i:s');
        $this->Notification_model->create_notification($message, 'attendance');
    
        $this->session->set_flashdata('success', 'Check in berhasil.');
        redirect('attendance');
    }
    
    public function checkout() {
        $user_id = $this->session->userdata('user_id');
        $employee = $this->Employee_model->get_by_user_id($user_id);
    
        if (!$employee) {
            $this->session->set_flashdata('error', 'Employee record not found.');
            redirect('attendance');
            return;
        }
    
        $employee_id = $employee['id'];
        $today = date('Y-m-d');
    
        $this->load->model('Leave_model');
        if ($this->Leave_model->is_on_leave($employee_id, $today)) {
            $this->session->set_flashdata('error', 'Anda sedang cuti hari ini dan tidak dapat melakukan check out.');
            redirect('attendance');
            return;
        }
    
        $record = $this->Attendance_model->get_by_employee_date($employee_id, $today);
        if (!$record) {
            $this->session->set_flashdata('error', 'Anda belum check in hari ini.');
            redirect('attendance');
            return;
        }
    
        if (!empty($record['clock_out_time'])) {
            $this->session->set_flashdata('error', 'Anda sudah melakukan check out hari ini.');
            redirect('attendance');
            return;
        }
    
        $update_data = [
            'clock_out_time' => date('Y-m-d H:i:s')
        ];
        $this->Attendance_model->update($record['id'], $update_data);
    
        $message = $employee['full_name'] . " melakukan check out pada pukul " . date('H:i:s');
        $this->Notification_model->create_notification($message, 'attendance');
    
        $this->session->set_flashdata('success', 'Check out berhasil.');
        redirect('attendance');
    }
    

    public function monthly() {
        $month = $this->input->get('month') ? $this->input->get('month') : date('m');
        $year = $this->input->get('year') ? $this->input->get('year') : date('Y');
    
        if ($this->session->userdata('role') === 'admin') {
            $employee_id = $this->input->get('employee_id');
            if (!$employee_id) {
                $this->session->set_flashdata('error', 'Employee ID is required for admin view.');
                redirect('employee'); 
                return;
            }
            $employee = $this->Employee_model->get_by_id($employee_id);
        } else {
            $user_id = $this->session->userdata('user_id');
            $employee = $this->Employee_model->get_by_user_id($user_id);
        }
    
        if (!$employee) {
            $this->session->set_flashdata('error', 'Data pegawai tidak ditemukan.');
            redirect('attendance');
            return;
        }
    
        $records = $this->Attendance_model->get_monthly_attendance($employee['id'], $month, $year);
    
        $summary = $this->Attendance_model->get_monthly_summary($employee['id'], $month, $year);
    
        $data = [
            'employee' => $employee,
            'records'  => $records,
            'month'    => $month,
            'year'     => $year,
            'summary'  => $summary
        ];
    
        $this->load_view('dashboard/attendance/monthly', $data);
    }
    
}
