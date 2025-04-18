<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Create a new notification
     * 
     * @param array $data Notification data including user_id, message, type, and url
     * @return int|bool The inserted notification ID on success, or false on failure
     */
    public function create($data) {
        if ($this->db->insert('notifications', $data)) {
            return $this->db->insert_id();
        } else {
            log_message('error', 'Notification_model: create error: ' . $this->db->error()['message']);
            return false;
        }
    }

    /**
     * Get notifications for a specific user
     * 
     * @param int $user_id The user ID
     * @param array $filters Optional filters like is_read, type, limit
     * @return array The list of notifications
     */
    public function get_by_user($user_id, $filters = array()) {
        $this->db->where('user_id', $user_id);
        
        // Apply optional filters
        if (isset($filters['is_read'])) {
            $this->db->where('is_read', $filters['is_read']);
        }
        
        if (isset($filters['type'])) {
            $this->db->where('type', $filters['type']);
        }
        
        // Order by newest first
        $this->db->order_by('created_at', 'DESC');
        
        // Limit results if specified
        if (isset($filters['limit'])) {
            $this->db->limit($filters['limit']);
        }
        
        $query = $this->db->get('notifications');
        return $query->result_array();
    }

    /**
     * Count unread notifications for a user
     * 
     * @param int $user_id The user ID
     * @return int Number of unread notifications
     */
    public function count_unread($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->where('is_read', 0);
        return $this->db->count_all_results('notifications');
    }

    /**
     * Mark a notification as read
     * 
     * @param int $notification_id The notification ID
     * @return bool True on success, False on failure
     */
    public function mark_as_read($notification_id) {
        $this->db->where('id', $notification_id);
        return $this->db->update('notifications', ['is_read' => 1]);
    }

    /**
     * Mark all notifications as read for a user
     * 
     * @param int $user_id The user ID
     * @return bool True on success, False on failure
     */
    public function mark_all_as_read($user_id) {
        $this->db->where('user_id', $user_id);
        return $this->db->update('notifications', ['is_read' => 1]);
    }

    /**
     * Delete a notification
     * 
     * @param int $notification_id The notification ID
     * @return bool True on success, False on failure
     */
    public function delete($notification_id) {
        $this->db->where('id', $notification_id);
        return $this->db->delete('notifications');
    }

    /**
     * Delete all notifications for a user
     * 
     * @param int $user_id The user ID
     * @return bool True on success, False on failure
     */
    public function delete_all_for_user($user_id) {
        $this->db->where('user_id', $user_id);
        return $this->db->delete('notifications');
    }

    /**
     * Create a notification with basic parameters
     * 
     * @param string $message The notification message
     * @param string $type The notification type
     * @param int $user_id The user ID (optional)
     * @param string $url Related URL (optional)
     * @return bool True on success, False on failure
     */
    public function create_notification($message, $type, $user_id = NULL, $url = NULL) {
        $data = [
            'message'    => $message,
            'type'       => $type,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if ($user_id !== NULL) {
            $data['user_id'] = $user_id;
        }
        
        if ($url !== NULL) {
            $data['url'] = $url;
        }
        
        return $this->db->insert('notifications', $data);
    }

    /**
     * Get unread notifications for a user
     * Provided for backward compatibility
     * 
     * @param int $user_id The user ID
     * @return array Unread notifications
     */
    public function get_unread_notifications($user_id) {
        return $this->get_by_user($user_id, ['is_read' => 0]);
    }
}
