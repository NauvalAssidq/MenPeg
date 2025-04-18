<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leave_model extends CI_Model {

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }
    
    public function is_on_leave($employee_id, $date) {
        $this->db->where('employee_id', $employee_id);
        $this->db->where('status', 'approved');
        $this->db->where('start_date <=', $date);
        $this->db->where('end_date >=', $date);
        $query = $this->db->get('leave_requests');
        return $query->num_rows() > 0;
    }

    public function get_total_leave_days($employee_id, $month, $year) {
        $this->db->where('employee_id', $employee_id);
        $this->db->where('status', 'approved');
        $this->db->where("MONTH(start_date) <= ", $month);
        $this->db->where("MONTH(end_date) >= ", $month);
        $this->db->where("YEAR(start_date) <= ", $year);
        $this->db->where("YEAR(end_date) >= ", $year);
        $query = $this->db->get('leave_requests')->result();

        $total = 0;
        foreach ($query as $row) {
            $start = new DateTime($row->start_date);
            $end = new DateTime($row->end_date);
            $interval = $start->diff($end);
            $total += $interval->days + 1;
        }
        return $total;
    }
    
    public function get_all($filters = array()){
        $this->db->select('lr.*, e.full_name');
        $this->db->from('leave_requests lr');
        $this->db->join('employees e', 'lr.employee_id = e.id', 'left');
        
        if (isset($filters['status'])) {
            $this->db->where('lr.status', $filters['status']);
        }
        
        if (isset($filters['employee_id'])) {
            $this->db->where('lr.employee_id', $filters['employee_id']);
        }
        
        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $this->db->where('lr.start_date >=', $filters['start_date']);
            $this->db->where('lr.end_date <=', $filters['end_date']);
        }
        
        $this->db->order_by('lr.created_at', 'DESC');
        return $this->db->get()->result_array();
    }
    
    public function get_by_id($id){
        return $this->db->get_where('leave_requests', ['id' => $id])->row_array();
    }
    
    public function insert($data){
        return $this->db->insert('leave_requests', $data);
    }
    
    public function update($id, $data){
        $this->db->where('id', $id);
        return $this->db->update('leave_requests', $data);
    }
    
    public function delete($id){
        $this->db->where('id', $id);
        return $this->db->delete('leave_requests');
    }
}
