<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Employee extends MY_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('Employee_model');
    $this->load->library(['form_validation', 'session']);
    $this->load->helper(['url', 'form']);
    if (!$this->session->userdata('logged_in')) {
      redirect('auth/login');
    }
  }
  public function get_data() {
    $employees = $this->Employee_model->get_all();
    header('Content-Type: application/json');
    echo json_encode($employees);
  }
  public function index() {
    $data['title'] = 'Employees';
    $data['employees'] = $this->Employee_model->get_all();
    $this->load_view('dashboard/employees/index', $data);
  }
  public function create() {
    $data['title'] = 'Add Employee';
    $this->form_validation->set_rules('full_name', 'Full Name', 'required');
    $this->form_validation->set_rules('gender', 'Gender', 'required');
    $this->form_validation->set_rules('dob', 'Date of Birth', 'required');
    $this->form_validation->set_rules('phone', 'Phone', 'required|numeric');
    $this->form_validation->set_rules('address', 'Address', 'required');
    $this->form_validation->set_rules('position', 'Position', 'required');
    $this->form_validation->set_rules('department', 'Department', 'required');
    $this->form_validation->set_rules('salary', 'Salary', 'required|numeric');
    $this->form_validation->set_rules('hire_date', 'Hire Date', 'required');
    $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[users.username]');
    $this->form_validation->set_rules('email', 'Email', 'required|trim|is_unique[users.email]|valid_email');
    $this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]');
    $this->form_validation->set_rules('role', 'Role', 'required|in_list[admin,employee,manager]');
    if ($this->form_validation->run() === FALSE) {
      $this->load_view('dashboard/employees/create', $data);
    } else {
      $profile_image = 'default.png';
      if (!empty($_FILES['profile_image']['name'])) {
        $config['upload_path']   = './uploads/profiles/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 2048;
        $config['file_name']     = time() . '_' . $_FILES['profile_image']['name'];
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('profile_image')) {
          $profile_image = $this->upload->data('file_name');
        } else {
          $error = $this->upload->display_errors();
          log_message('error', 'Failed to upload profile image: ' . $error);
          $this->session->set_flashdata('error', 'Image upload failed: ' . $error);
        }
      }
      $this->load->model('User_model');
      $username = $this->input->post('username', TRUE);
      $email    = $this->input->post('email', TRUE);
      $password = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
      $role     = $this->input->post('role', TRUE);
      $user_data = [
        'username' => $username,
        'email'    => $email,
        'password' => $password,
        'role'     => $role
      ];
      $user_id = $this->User_model->insert_user($user_data);
      $employee_data = [
        'user_id'       => $user_id,
        'full_name'     => $this->input->post('full_name', TRUE),
        'gender'        => $this->input->post('gender', TRUE),
        'dob'           => $this->input->post('dob', TRUE),
        'phone'         => $this->input->post('phone', TRUE),
        'address'       => $this->input->post('address', TRUE),
        'position'      => $this->input->post('position', TRUE),
        'department'    => $this->input->post('department', TRUE),
        'salary'        => $this->input->post('salary', TRUE),
        'hire_date'     => $this->input->post('hire_date', TRUE),
        'profile_image' => $profile_image
      ];
      $this->Employee_model->insert($employee_data);
      $this->session->set_flashdata('success', 'Employee and user account created successfully');
      redirect('employee');
    }
  }
  public function edit($id) {
    $data['title'] = 'Edit Employee';
    $data['employee'] = $this->Employee_model->get_by_id($id);
    if (!$data['employee']) {
      show_404();
    }
    $this->form_validation->set_rules('full_name', 'Full Name', 'required');
    $this->form_validation->set_rules('gender', 'Gender', 'required');
    $this->form_validation->set_rules('dob', 'Date of Birth', 'required');
    $this->form_validation->set_rules('phone', 'Phone', 'required|numeric');
    $this->form_validation->set_rules('address', 'Address', 'required');
    $this->form_validation->set_rules('position', 'Position', 'required');
    $this->form_validation->set_rules('department', 'Department', 'required');
    $this->form_validation->set_rules('salary', 'Salary', 'required|numeric');
    $this->form_validation->set_rules('status', 'Status', 'required');
    $this->form_validation->set_rules('username', 'Username', 'required|trim');
    $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
    $this->form_validation->set_rules('role', 'Role', 'required|in_list[admin,employee,manager]');
    if ($this->form_validation->run() === FALSE) {
      $this->load_view('dashboard/employees/edit', $data);
    } else {
      $profile_image = $data['employee']['profile_image'];
      if (!empty($_FILES['profile_image']['name'])) {
        $config['upload_path']   = './uploads/profiles/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 2048;
        $config['file_name']     = time() . '_' . $_FILES['profile_image']['name'];
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('profile_image')) {
          $profile_image = $this->upload->data('file_name');
        }
      }
      $employee_data = [
        'full_name'     => $this->input->post('full_name', TRUE),
        'gender'        => $this->input->post('gender', TRUE),
        'dob'           => $this->input->post('dob', TRUE),
        'phone'         => $this->input->post('phone', TRUE),
        'address'       => $this->input->post('address', TRUE),
        'position'      => $this->input->post('position', TRUE),
        'department'    => $this->input->post('department', TRUE),
        'salary'        => $this->input->post('salary', TRUE),
        'status'        => $this->input->post('status', TRUE),
        'profile_image' => $profile_image
      ];
      if ($this->Employee_model->update_employee($id, $employee_data)) {
        if (!isset($this->User_model)) {
          $this->load->model('User_model');
        }
        $user_id = $data['employee']['user_id'];
        $user_data = [
          'username' => $this->input->post('username', TRUE),
          'email'    => $this->input->post('email', TRUE),
          'role'     => $this->input->post('role', TRUE)
        ];
        $new_password = $this->input->post('password');
        if (!empty($new_password)) {
          $user_data['password'] = password_hash($new_password, PASSWORD_BCRYPT);
        }
        if ($this->User_model->update_user($user_id, $user_data)) {
          $this->session->set_flashdata('success', 'Employee and user account updated successfully');
        } else {
          $this->session->set_flashdata('error', 'Failed to update user account');
        }
      } else {
        $this->session->set_flashdata('error', 'Failed to update employee data');
      }
      redirect('employee');
    }
  }
  public function delete($id) {
    if ($this->Employee_model->delete($id)) {
      $this->session->set_flashdata('success', 'Employee deleted successfully');
    } else {
      $this->session->set_flashdata('error', 'Failed to delete employee');
    }
    redirect('employee');
  }
  public function view($id) {
    $employee = $this->Employee_model->get_by_id($id);
    if (!$employee) {
      show_404();
    }
    $this->load->model('User_model');
    $user = $this->User_model->get_user_by_id($employee['user_id']);
    if ($user) {
      $employee['username'] = $user['username'];
      $employee['email']    = $user['email'];
      $employee['role']     = $user['role'];
    }
    $data['employee'] = $employee;
    $this->load_view('dashboard/employees/view', $data);
  }
}
