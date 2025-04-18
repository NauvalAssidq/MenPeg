<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Attendance_model');
        $this->load->model('Employee_model');
        $this->load->model('Leave_model');
    }

    public function index() {
        $data['title'] = 'Dashboard';
        $user_id = $this->session->userdata('user_id');
        $employee = $this->Employee_model->get_by_user_id($user_id);
        if (!$employee) {
            show_error('Employee record not found.', 404);
            return;
        }
        
        $today = date('Y-m-d');
        $today_record = $this->Attendance_model->get_by_employee_date($employee['id'], $today);
        $data['today_record'] = $today_record;
        $data['total_employees'] = count($this->Employee_model->get_all());
        $data['active_employees'] = count($this->Employee_model->get_all());
        $leave_filters = ['status' => 'pending'];
        $data['leave_requests'] = count($this->Leave_model->get_all($leave_filters));
        $this->load_view('dashboard/index', $data);
    }
}
