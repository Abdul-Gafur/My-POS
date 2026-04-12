<?php
defined('BASEPATH') OR exit('');

/**
 * Activity Log Model
 * Handles all activity log operations
 */
class Activitylog_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get all activity logs with filters
     * @param string $event_type
     * @param string $staff_id
     * @param string $table_name
     * @param string $date_from
     * @param string $date_to
     * @param int $start
     * @param int $limit
     * @param string $orderBy
     * @param string $orderFormat
     * @return boolean|object
     */
    public function getAll($event_type = '', $staff_id = '', $table_name = '', $date_from = '', $date_to = '', $start = 0, $limit = 50, $orderBy = 'dateAdded', $orderFormat = 'DESC') {
        $dateCol = $this->getDateColumn();
        
        // Use id for ordering if date column doesn't exist
        if (!$dateCol && $orderBy === 'dateAdded') {
            $orderBy = 'id';
        }
        
        $this->db->select('eventlog.*, CONCAT_WS(" ", admin.first_name, admin.last_name) as staff_name, admin.email as staff_email');
        $this->db->from('eventlog');
        $this->db->join('admin', 'eventlog.staffInCharge = admin.id', 'LEFT');
        
        // Apply filters
        if (!empty($event_type)) {
            $this->db->like('eventlog.event', $event_type);
        }
        
        if (!empty($staff_id)) {
            $this->db->where('eventlog.staffInCharge', $staff_id);
        }
        
        if (!empty($table_name)) {
            $this->db->where('eventlog.eventTable', $table_name);
        }
        
        if ($dateCol && !empty($date_from)) {
            $this->db->where("DATE(eventlog.{$dateCol}) >=", $date_from);
        }
        
        if ($dateCol && !empty($date_to)) {
            $this->db->where("DATE(eventlog.{$dateCol}) <=", $date_to);
        }
        
        $this->db->order_by($orderBy, $orderFormat);
        $this->db->limit($limit, $start);
        
        $run_q = $this->db->get();
        
        if ($run_q->num_rows() > 0) {
            return $run_q->result();
        }
        
        return FALSE;
    }
    
    /**
     * Count total activity logs
     * @param array $filters
     * @return int
     */
    public function countAll($event_type = '', $staff_id = '', $table_name = '', $date_from = '', $date_to = '') {
        $dateCol = $this->getDateColumn();
        
        $this->db->from('eventlog');
        
        if (!empty($event_type)) {
            $this->db->like('event', $event_type);
        }
        
        if (!empty($staff_id)) {
            $this->db->where('staffInCharge', $staff_id);
        }
        
        if (!empty($table_name)) {
            $this->db->where('eventTable', $table_name);
        }
        
        if ($dateCol && !empty($date_from)) {
            $this->db->where("DATE({$dateCol}) >=", $date_from);
        }
        
        if ($dateCol && !empty($date_to)) {
            $this->db->where("DATE({$dateCol}) <=", $date_to);
        }
        
        return $this->db->count_all_results();
    }
    
    /**
     * Check if dateAdded column exists in eventlog table
     * @return bool
     */
    private function hasDateAddedColumn() {
        try {
            $this->db->query("SELECT dateAdded FROM eventlog LIMIT 1");
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get date column name (with fallback)
     * @return string
     */
    private function getDateColumn() {
        if ($this->hasDateAddedColumn()) {
            return 'dateAdded';
        }
        // Try to find alternative date columns
        $possibleColumns = ['date', 'created_at', 'timestamp', 'eventDate'];
        foreach ($possibleColumns as $col) {
            try {
                $this->db->query("SELECT {$col} FROM eventlog LIMIT 1");
                return $col;
            } catch (Exception $e) {
                continue;
            }
        }
        // Return null if no date column found - queries will skip date filtering
        return null;
    }
    
    /**
     * Get activity statistics
     * @param string $date_from
     * @param string $date_to
     * @return array
     */
    public function getStatistics($date_from = '', $date_to = '') {
        $stats = [];
        $dateCol = $this->getDateColumn();
        
        // Total activities
        $this->db->from('eventlog');
        if ($dateCol && !empty($date_from)) {
            $this->db->where("DATE({$dateCol}) >=", $date_from);
        }
        if ($dateCol && !empty($date_to)) {
            $this->db->where("DATE({$dateCol}) <=", $date_to);
        }
        $stats['total_activities'] = $this->db->count_all_results();
        
        // Activities by event type
        $this->db->select('event, COUNT(*) as count');
        $this->db->from('eventlog');
        if ($dateCol && !empty($date_from)) {
            $this->db->where("DATE({$dateCol}) >=", $date_from);
        }
        if ($dateCol && !empty($date_to)) {
            $this->db->where("DATE({$dateCol}) <=", $date_to);
        }
        $this->db->group_by('event');
        $this->db->order_by('count', 'DESC');
        $stats['by_event'] = $this->db->get()->result();
        
        // Activities by staff
        $this->db->select('eventlog.staffInCharge, CONCAT_WS(" ", admin.first_name, admin.last_name) as staff_name, COUNT(*) as count');
        $this->db->from('eventlog');
        $this->db->join('admin', 'eventlog.staffInCharge = admin.id', 'LEFT');
        if ($dateCol && !empty($date_from)) {
            $this->db->where("DATE(eventlog.{$dateCol}) >=", $date_from);
        }
        if ($dateCol && !empty($date_to)) {
            $this->db->where("DATE(eventlog.{$dateCol}) <=", $date_to);
        }
        $this->db->group_by('eventlog.staffInCharge');
        $this->db->order_by('count', 'DESC');
        $this->db->limit(10);
        $stats['by_staff'] = $this->db->get()->result();
        
        // Activities by table
        $this->db->select('eventTable, COUNT(*) as count');
        $this->db->from('eventlog');
        if ($dateCol && !empty($date_from)) {
            $this->db->where("DATE({$dateCol}) >=", $date_from);
        }
        if ($dateCol && !empty($date_to)) {
            $this->db->where("DATE({$dateCol}) <=", $date_to);
        }
        $this->db->group_by('eventTable');
        $this->db->order_by('count', 'DESC');
        $stats['by_table'] = $this->db->get()->result();
        
        // Daily activities (last 30 days) - only if date column exists
        if ($dateCol) {
            $this->db->select("DATE({$dateCol}) as activity_date, COUNT(*) as count");
            $this->db->from('eventlog');
            $this->db->where("DATE({$dateCol}) >=", date('Y-m-d', strtotime('-30 days')));
            if (!empty($date_to)) {
                $this->db->where("DATE({$dateCol}) <=", $date_to);
            }
            $this->db->group_by("DATE({$dateCol})");
            $this->db->order_by('activity_date', 'ASC');
            $stats['daily'] = $this->db->get()->result();
        } else {
            $stats['daily'] = [];
        }
        
        return $stats;
    }
    
    /**
     * Get recent activities
     * @param int $limit
     * @return boolean|object
     */
    public function getRecent($limit = 10) {
        $dateCol = $this->getDateColumn();
        $orderBy = $dateCol ? "eventlog.{$dateCol}" : 'eventlog.id';
        
        $this->db->select('eventlog.*, CONCAT_WS(" ", admin.first_name, admin.last_name) as staff_name');
        $this->db->from('eventlog');
        $this->db->join('admin', 'eventlog.staffInCharge = admin.id', 'LEFT');
        $this->db->order_by($orderBy, 'DESC');
        $this->db->limit($limit);
        
        $run_q = $this->db->get();
        
        if ($run_q->num_rows() > 0) {
            return $run_q->result();
        }
        
        return FALSE;
    }
}

