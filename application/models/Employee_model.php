<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all() {
        return $this->db
            ->select('employees.*, users.username, users.email, users.role')
            ->from('employees')
            ->join('users', 'users.id = employees.user_id', 'left')
            ->get()
            ->result_array();
    }

    public function get_by_id($id) {
        return $this->db
            ->select('employees.*, users.username, users.email, users.role')
            ->from('employees')
            ->join('users', 'users.id = employees.user_id', 'left')
            ->where('employees.id', $id)
            ->get()
            ->row_array();
    }    

    public function get_employee_with_user($id) {
        return $this->db
            ->select('employees.*, users.username, users.email, users.role')
            ->from('employees')
            ->join('users', 'users.id = employees.user_id', 'left')
            ->where('employees.id', $id)
            ->get()
            ->row_array();
    }

    public function get_by_user_id($user_id) {
        return $this->db
            ->select('employees.*, users.username, users.email, users.role')
            ->from('employees')
            ->join('users', 'users.id = employees.user_id', 'left')
            ->where('employees.user_id', $user_id)
            ->get()
            ->row_array();
    }    

    public function insert($data) {
        return $this->db->insert('employees', $data);
    }

    public function update_user($user_id, $data) {
        $this->db->where('id', $user_id);
        return $this->db->update('users', $data);
    }

    public function update_employee($employee_id, $data) {
        $this->db->where('id', $employee_id);
        return $this->db->update('employees', $data);
    }

    public function delete($id) {
        return $this->db->delete('employees', ['id' => $id]);
    }
}
