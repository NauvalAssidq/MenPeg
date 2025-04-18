<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        // Ensure the database library is loaded
        $this->load->database();
    }

    /**
     * Insert a new user into the 'users' table.
     *
     * @param array $data User data including username, email, password, and role.
     * @return mixed The inserted user's ID on success, or false on failure.
     */
    public function insert_user($data) {
        if ($this->db->insert('users', $data)) {
            return $this->db->insert_id();
        } else {
            log_message('error', 'User_model: insert_user error: ' . $this->db->error()['message']);
            return false;
        }
    }

    /**
     * Get a user by username.
     *
     * @param string $username The username to search for.
     * @return array|null The user record if found, or null if not.
     */
    public function get_user_by_username($username) {
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        return $query->row_array();
    }

    /**
     * Get a user by email.
     *
     * @param string $email The email to search for.
     * @return array|null The user record if found, or null if not.
     */
    public function get_user_by_email($email) {
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        return $query->row_array();
    }

    /**
     * Get a user by ID.
     *
     * @param int $id The user ID.
     * @return array|null The user record if found, or null if not.
     */
    public function get_user_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('users');
        return $query->row_array();
    }
    
    /**
     * Update a user's information in the 'users' table.
     *
     * @param int $id The user ID to update.
     * @param array $data The user data to update.
     * @return bool True on success, False on failure.
     */
    public function update_user($id, $data) {
        $this->db->where('id', $id);
        if ($this->db->update('users', $data)) {
            return true;
        } else {
            log_message('error', 'User_model: update_user error: ' . $this->db->error()['message']);
            return false;
        }
    }
}
