<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        $this->load->library(['session','form_validation']);
        $this->load->helper(['url', 'form', 'security']);
        $this->load->model('Notification_model');

        if (!$this->session->userdata('logged_in') && $this->uri->segment(1) !== 'auth') {
            redirect('auth/login');
        }

        if ($this->session->userdata('logged_in')) {
            $this->user_id = $this->session->userdata('user_id');
            $this->username = $this->session->userdata('username');
            $this->role = $this->session->userdata('role');
        }

        if ($this->session->userdata('user_id')) {
            $user_id = $this->session->userdata('user_id');
            $this->data['notifications'] = $this->Notification_model->get_by_user($user_id, ['is_read' => 0]);
            $this->data['notif_count'] = $this->Notification_model->count_unread($user_id);
        } else {
            $this->data['notifications'] = [];
            $this->data['notif_count'] = 0;
        }
    }

    protected function check_role($role)
    {
        if ($this->role !== $role) {
            redirect('dashboard');
        }
    }

    protected function load_view($view, $data = [])
    {
        // Merge $this->data with the passed $data array
        // This ensures notifications and other global data are available in all views
        // If there are duplicate keys, $data will override $this->data values
        $merged_data = array_merge($this->data, $data);
        
        $this->load->view($view, $merged_data);
    }
    
}
