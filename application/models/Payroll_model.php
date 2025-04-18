<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payroll_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function create_or_update($data) {
        $month = (int) $data['month'];
        $year = (int) $data['year'];
    
        $month_date = date('Y-m-01', strtotime("$year-$month-01"));
    
        $payroll_data = array(
            'employee_id'   => $data['employee_id'],
            'month'         => $month_date,
            'salary'        => $data['salary'],
            'bonus'         => $data['bonus'],
            'deductions'    => $data['deductions'],
            'total_salary'  => $data['total_salary'],
            'status'        => $data['status']
        );
    
        $this->db->where('employee_id', $data['employee_id']);
        $this->db->where('month', $month_date);
        $query = $this->db->get('payroll');
    
        if ($query->num_rows() > 0) {
            $existing = $query->row();
            $this->db->where('id', $existing->id);
            return $this->db->update('payroll', $payroll_data);
        } else {
            return $this->db->insert('payroll', $payroll_data);
        }
    }

    public function get_all() {
        $this->db->select('p.*, e.full_name, MONTH(p.month) as month_num, YEAR(p.month) as year_num');
        $this->db->from('payroll p');
        $this->db->join('employees e', 'p.employee_id = e.id');
        $this->db->order_by('p.month DESC');
        return $this->db->get()->result_array();
    }

    public function get_by_id($id) {
        $this->db->select('p.*, e.full_name, e.position as designation, MONTH(p.month) as month_num, YEAR(p.month) as year_num');
        $this->db->from('payroll p');
        $this->db->join('employees e', 'p.employee_id = e.id');
        $this->db->where('p.id', $id);
        return $this->db->get()->row_array();
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('payroll', $data);
    }

    public function get_by_employee_id($employee_id) {
        $this->db->select('p.*, e.full_name, MONTH(p.month) as month_num, YEAR(p.month) as year_num');
        $this->db->from('payroll p');
        $this->db->join('employees e', 'p.employee_id = e.id');
        $this->db->where('p.employee_id', $employee_id);
        $this->db->order_by('p.month DESC');
        return $this->db->get()->result_array();
    }
}
