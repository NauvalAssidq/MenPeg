<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leave extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Leave_model');
        $this->load->model('Employee_model');  // For fetching employee data if needed
        $this->load->model('User_model');     // For getting admin users
        
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }
    
    public function index(){
        $role = $this->session->userdata('role');
        
        if ($role === 'admin') {
            $filters = array();
            if ($this->input->get('status')) {
                $filters['status'] = $this->input->get('status');
            }
            if ($this->input->get('employee_id')) {
                $filters['employee_id'] = $this->input->get('employee_id');
            }
            $data['leave_requests'] = $this->Leave_model->get_all($filters);
        } else {
            $employee = $this->Employee_model->get_by_user_id($this->session->userdata('user_id'));
            $filters = ['employee_id' => $employee['id']];
            $data['leave_requests'] = $this->Leave_model->get_all($filters);
        }
        
        $this->load_view('dashboard/leave/index', $data);
    }
    
    public function create(){
        $employee = $this->Employee_model->get_by_user_id($this->session->userdata('user_id'));
        $data['employee'] = $employee;
        
        $this->load_view('dashboard/leave/create', $data);
    }
    
    public function store(){
        $employee = $this->Employee_model->get_by_user_id($this->session->userdata('user_id'));
        
        $data = array(
            'employee_id' => $employee['id'], // Or use form input if admin is creating for someone else
            'leave_type'  => $this->input->post('leave_type'),
            'start_date'  => $this->input->post('start_date'),
            'end_date'    => $this->input->post('end_date'),
            'reason'      => $this->input->post('reason'),
            'status'      => 'pending'
        );
        
        if($this->Leave_model->insert($data)){
            $this->session->set_flashdata('success', 'Leave request submitted successfully.');
            
            // Create notification for admin users
            $admin_users = $this->User_model->get_users_by_role('admin');
            
            if ($admin_users) {
                $employee_name = $employee['first_name'] . ' ' . $employee['last_name'];
                $notification_message = 'New leave request from ' . $employee_name;
                
                foreach ($admin_users as $admin) {
                    $this->Notification_model->create_notification(
                        $notification_message,
                        'leave_request',
                        $admin['id'],
                        'leave'
                    );
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Failed to submit leave request.');
        }
        redirect('leave');
    }
    
    public function approve($id){
        $data = array(
            'status'      => 'approved',
            'approved_by' => $this->session->userdata('user_id')
        );
        
        // Get leave request details before update
        $leave_request = $this->Leave_model->get_by_id($id);
        
        if($this->Leave_model->update($id, $data)){
            $this->session->set_flashdata('success', 'Leave request approved.');
            
            // Create notification for the employee
            if ($leave_request) {
                $employee = $this->Employee_model->get_by_id($leave_request['employee_id']);
                if ($employee && $employee['user_id']) {
                    $notification_message = 'Your leave request has been approved';
                    $this->Notification_model->create_notification(
                        $notification_message,
                        'leave_approved',
                        $employee['user_id'],
                        'leave'
                    );
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Failed to approve leave request.');
        }
        redirect('leave');
    }
    
    public function reject($id){
        $data = array(
            'status'      => 'rejected',
            'approved_by' => $this->session->userdata('user_id')
        );
        
        // Get leave request details before update
        $leave_request = $this->Leave_model->get_by_id($id);
        
        if($this->Leave_model->update($id, $data)){
            $this->session->set_flashdata('success', 'Leave request rejected.');
            
            // Create notification for the employee
            if ($leave_request) {
                $employee = $this->Employee_model->get_by_id($leave_request['employee_id']);
                if ($employee && $employee['user_id']) {
                    $notification_message = 'Your leave request has been rejected';
                    $this->Notification_model->create_notification(
                        $notification_message,
                        'leave_rejected',
                        $employee['user_id'],
                        'leave'
                    );
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Failed to reject leave request.');
        }
        redirect('leave');
    }
    
    public function delete($id){
        if($this->Leave_model->delete($id)){
            $this->session->set_flashdata('success', 'Leave request deleted.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete leave request.');
        }
        redirect('leave');
    }
}
