<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function register()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        $this->form_validation->set_message('required', 'Bidang {field} wajib diisi.');
        $this->form_validation->set_message('is_unique', 'Bidang {field} harus memiliki nilai yang unik.');
        $this->form_validation->set_message('min_length', 'Bidang {field} minimal harus terdiri dari {param} karakter.');
        $this->form_validation->set_message('valid_email', 'Bidang {field} harus berisi alamat email yang valid.');
        $this->form_validation->set_message('in_list', 'Bidang {field} harus salah satu dari: {param}.');

        $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[users.username]');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|is_unique[users.email]|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]');
        $this->form_validation->set_rules('role', 'Role', 'required|in_list[admin,employee,manager]');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('auth/register');
        } else {
            $data = [
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'role' => $this->input->post('role'),
            ];

            $this->User_model->insert_user($data);

            $this->session->set_flashdata('success', 'Pendaftaran berhasil! Silahkan login.');
            redirect('auth/login');
        }
    }

    public function login()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard/index');  
        }

        $this->form_validation->set_message('required', 'Bidang {field} wajib diisi.');

        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('auth/login');
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $user = $this->User_model->get_user_by_username($username);

            if ($user && password_verify($password, $user['password'])) {
                $this->session->set_userdata('logged_in', TRUE);
                $this->session->set_userdata('user_id', $user['id']);
                $this->session->set_userdata('username', $user['username']);
                $this->session->set_userdata('role', $user['role']);
                redirect('dashboard');
            } else {
                $this->session->set_flashdata('error', 'Username atau password salah.');
                redirect('auth/login');
            }
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}
