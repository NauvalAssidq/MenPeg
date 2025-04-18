<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Notification_model');
    }
    
    /**
     *
     * @param int
     * @return void
     */
    public function read($id) {
        $this->db->where('id', $id);
        $notification = $this->db->get('notifications')->row_array();
        
        if (!$notification) {
            $this->session->set_flashdata('error', 'Notification not found.');
            redirect($_SERVER['HTTP_REFERER'] ?? 'dashboard');
            return;
        }
        $this->Notification_model->mark_as_read($id);
        $redirect_url = !empty($notification['url']) ? site_url($notification['url']) : 'dashboard';
        redirect($redirect_url);
    }
    
    /**
     *
     * @return void
     */
    public function mark_all_read() {
        $user_id = $this->session->userdata('user_id');
        
        if ($user_id) {
            $this->Notification_model->mark_all_as_read($user_id);
            $this->session->set_flashdata('success', 'All notifications marked as read.');
        }
        
        redirect($_SERVER['HTTP_REFERER'] ?? 'dashboard');
    }
    
    /**
     *
     * @return void
     */
    public function index() {
        $user_id = $this->session->userdata('user_id');
    
        if ($user_id) {
            $data['all_notifications'] = $this->Notification_model->get_by_user($user_id);
            $this->load_view('dashboard/notifications/index', $data);
        } else {
            redirect('auth/login');
        }
    }
    
}

