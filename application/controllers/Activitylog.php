<?php
defined('BASEPATH') OR exit('');

/**
 * Activity Log Controller
 * Comprehensive activity logging and monitoring
 */
class Activitylog extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->genlib->checkLogin();
        $this->permissions->requirePermission('reports', 'view');
        
        $this->load->model(['Activitylog_model' => 'activitylog', 'admin']);
    }
    
    /**
     * Main activity log view
     */
    public function index() {
        $data['statistics'] = $this->activitylog->getStatistics();
        $data['recent_activities'] = $this->activitylog->getRecent(10);
        $data['all_staff'] = $this->admin->getAll('first_name', 'ASC', 0, 1000);
        
        $data['pageContent'] = $this->load->view('activitylog/index', $data, TRUE);
        $data['pageTitle'] = "Activity Log";
        
        $this->load->view('main', $data);
    }
    
    /**
     * Load activity log table (AJAX)
     */
    public function loadLogs() {
        $this->genlib->ajaxOnly();
        
        $event_type = $this->input->get('event_type', TRUE);
        $staff_id = $this->input->get('staff_id', TRUE);
        $table_name = $this->input->get('table_name', TRUE);
        $date_from = $this->input->get('date_from', TRUE);
        $date_to = $this->input->get('date_to', TRUE);
        $orderBy = $this->input->get('orderBy', TRUE) ?: 'dateAdded';
        $orderFormat = $this->input->get('orderFormat', TRUE) ?: 'DESC';
        
        $pageNumber = $this->uri->segment(3, 0);
        $limit = $this->input->get('limit', TRUE) ?: 25;
        $start = $pageNumber == 0 ? 0 : ($pageNumber - 1) * $limit;
        
        // Count total
        $totalLogs = $this->activitylog->countAll($event_type, $staff_id, $table_name, $date_from, $date_to);
        
        // Setup pagination
        $this->load->library('pagination');
        $config = $this->genlib->setPaginationConfig($totalLogs, "activitylog/loadLogs", $limit, ['class' => 'log-pagination']);
        $this->pagination->initialize($config);
        
        // Get logs
        $data['allLogs'] = $this->activitylog->getAll($event_type, $staff_id, $table_name, $date_from, $date_to, $start, $limit, $orderBy, $orderFormat);
        $data['range'] = $totalLogs > 0 ? ($start + 1) . "-" . ($start + count($data['allLogs'])) . " of " . $totalLogs : "";
        $data['links'] = $this->pagination->create_links();
        $data['sn'] = $start + 1;
        
        $json['logTable'] = $this->load->view('activitylog/logtable', $data, TRUE);
        
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    
    /**
     * Get statistics (AJAX)
     */
    public function getStats() {
        $this->genlib->ajaxOnly();
        
        $date_from = $this->input->get('date_from', TRUE);
        $date_to = $this->input->get('date_to', TRUE);
        
        $stats = $this->activitylog->getStatistics($date_from, $date_to);
        
        $this->output->set_content_type('application/json')->set_output(json_encode($stats));
    }
    
    /**
     * Export activity log
     */
    public function export() {
        $this->permissions->requirePermission('reports', 'export');
        
        $event_type = $this->input->get('event_type', TRUE);
        $staff_id = $this->input->get('staff_id', TRUE);
        $table_name = $this->input->get('table_name', TRUE);
        $date_from = $this->input->get('date_from', TRUE);
        $date_to = $this->input->get('date_to', TRUE);
        $format = $this->input->get('format', TRUE) ?: 'csv';
        
        $logs = $this->activitylog->getAll($event_type, $staff_id, $table_name, $date_from, $date_to, 0, 10000);
        
        $filename = 'activity_log_' . date('Y-m-d_His') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Header row
        fputcsv($output, ['Date', 'Event', 'Table', 'Reference ID', 'Staff', 'Description']);
        
        // Data rows
        if ($logs) {
            foreach ($logs as $log) {
                fputcsv($output, [
                    $log->dateAdded,
                    $log->event,
                    $log->eventTable,
                    $log->eventRowIdOrRef,
                    $log->staff_name ?: 'N/A',
                    strip_tags($log->eventDesc)
                ]);
            }
        }
        
        fclose($output);
        exit;
    }
}

