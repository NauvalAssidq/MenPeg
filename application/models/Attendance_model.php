<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance_model extends CI_Model {

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }
    
    public function get_by_employee_date($employee_id, $date){
        return $this->db->get_where('attendance', ['employee_id' => $employee_id, 'date' => $date])->row_array();
    }

    public function insert($data){
        return $this->db->insert('attendance', $data);
    }

    public function update($id, $data){
        $this->db->where('id', $id);
        return $this->db->update('attendance', $data);
    }

    public function get_all_by_employee($employee_id){
        $this->db->order_by('date', 'DESC');
        return $this->db->get_where('attendance', ['employee_id' => $employee_id])->result_array();
    }

    public function get_all_attendance_by_date($date)
    {
        $this->db->select('a.*, u.full_name');
        $this->db->from('attendance a');
        $this->db->join('employees u', 'a.employee_id = u.id');
        $this->db->where('a.date', $date);
        $this->db->order_by('u.full_name', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_monthly_attendance($employee_id, $month, $year) {
        $this->db->where('employee_id', $employee_id);
        $this->db->where('MONTH(date)', $month);
        $this->db->where('YEAR(date)', $year);
        $this->db->order_by('date', 'ASC');
        return $this->db->get('attendance')->result_array();
    }
    
    public function get_monthly_summary($employee_id, $month, $year) {
        $this->db->select('COUNT(*) as total_present');
        $this->db->where('employee_id', $employee_id);
        $this->db->where('MONTH(date)', $month);
        $this->db->where('YEAR(date)', $year);
        $this->db->where('clock_in_time IS NOT NULL', null, false);
        $this->db->where('clock_out_time IS NOT NULL', null, false);
        $present = $this->db->get('attendance')->row_array();
    
        $this->load->model('Leave_model');
        $total_leave = $this->Leave_model->get_total_leave_days($employee_id, $month, $year);
    
        return [
            'total_present' => $present['total_present'],
            'total_leave'   => $total_leave
        ];
    }
}
