<?php
defined('BASEPATH') OR exit('');

/**
 * Bulk Operations Controller
 * Handles bulk import/export, updates, and deletions
 */
class Bulkoperations extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->genlib->checkLogin();
        $this->permissions->requirePermission('items', 'import');
        
        $this->load->model(['item']);
        $this->load->helper(['file', 'form']);
    }
    
    /**
     * Bulk import items from CSV/Excel
     */
    public function importItems() {
        $this->genlib->ajaxOnly();
        $this->permissions->requirePermission('items', 'import');
        
        $config['upload_path'] = './uploads/temp/';
        $config['allowed_types'] = 'csv|xls|xlsx';
        $config['max_size'] = 2048; // 2MB
        $config['file_name'] = 'bulk_import_' . time();
        
        // Create upload directory if it doesn't exist
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0755, true);
        }
        
        $this->load->library('upload', $config);
        
        if (!$this->upload->do_upload('file')) {
            $json = ['status' => 0, 'msg' => $this->upload->display_errors('', '')];
        } else {
            $upload_data = $this->upload->data();
            $file_path = $upload_data['full_path'];
            
            // Process file based on extension
            $results = $this->processImportFile($file_path);
            
            // Delete uploaded file
            @unlink($file_path);
            
            $json = [
                'status' => 1,
                'msg' => "Import completed. {$results['success']} items imported, {$results['failed']} failed.",
                'details' => $results
            ];
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    
    /**
     * Process import file
     */
    private function processImportFile($file_path) {
        $results = ['success' => 0, 'failed' => 0, 'errors' => []];
        
        $file_ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
        
        if ($file_ext === 'csv') {
            $data = $this->readCSV($file_path);
        } elseif (in_array($file_ext, ['xls', 'xlsx'])) {
            $data = $this->readExcel($file_path);
        } else {
            $results['errors'][] = 'Unsupported file format';
            return $results;
        }
        
        if (empty($data) || count($data) < 2) {
            $results['errors'][] = 'File is empty or invalid format';
            return $results;
        }
        
        // Skip header row
        array_shift($data);
        
        $this->db->trans_start();
        
        foreach ($data as $row_num => $row) {
            // Expected format: Name, Code, Quantity, Price, Description
            if (count($row) < 4) {
                $results['failed']++;
                $results['errors'][] = "Row " . ($row_num + 2) . ": Insufficient columns";
                continue;
            }
            
            $name = trim($row[0]);
            $code = trim($row[1]);
            $quantity = floatval($row[2]);
            $price = floatval($row[3]);
            $description = isset($row[4]) ? trim($row[4]) : '';
            
            // Validate data
            if (empty($name) || empty($code) || $quantity < 0 || $price < 0) {
                $results['failed']++;
                $results['errors'][] = "Row " . ($row_num + 2) . ": Invalid data";
                continue;
            }
            
            // Check if code already exists
            $this->db->select('id');
            $this->db->where('code', $code);
            $existing_query = $this->db->get('items');
            if ($existing_query->num_rows() > 0) {
                $results['failed']++;
                $results['errors'][] = "Row " . ($row_num + 2) . ": Code '{$code}' already exists";
                continue;
            }
            
            // Insert item
            $insertedId = $this->item->add($name, $quantity, $price, $description, $code);
            
            if ($insertedId) {
                $results['success']++;
                
                // Log event
                $desc = "Bulk import: Added {$quantity} quantities of '{$name}' at {$price} each";
                $this->genmod->addevent("Bulk Import", $insertedId, $desc, "items", $this->session->admin_id);
            } else {
                $results['failed']++;
                $results['errors'][] = "Row " . ($row_num + 2) . ": Database insert failed";
            }
        }
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            $results['errors'][] = 'Transaction failed. Some items may not have been imported.';
        }
        
        return $results;
    }
    
    /**
     * Read CSV file
     */
    private function readCSV($file_path) {
        $data = [];
        if (($handle = fopen($file_path, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
                $data[] = $row;
            }
            fclose($handle);
        }
        return $data;
    }
    
    /**
     * Read Excel file (simplified - requires PHPExcel library or similar)
     */
    private function readExcel($file_path) {
        // For now, convert to CSV approach
        // In production, use PhpSpreadsheet or similar library
        return $this->readCSV($file_path);
    }
    
    /**
     * Export items to CSV/Excel/PDF
     */
    public function exportItems() {
        $this->permissions->requirePermission('items', 'export');
        
        $format = $this->input->get('format') ?: 'csv';
        $orderBy = $this->input->get('orderBy') ?: 'name';
        $orderFormat = $this->input->get('orderFormat') ?: 'ASC';
        
        $items = $this->item->getAll($orderBy, $orderFormat);
        
        if ($format === 'pdf') {
            redirect('items/exportPdf?orderBy=' . $orderBy . '&orderFormat=' . $orderFormat);
        } elseif ($format === 'csv') {
            $this->exportCSV($items);
        } elseif ($format === 'excel') {
            $this->exportExcel($items);
        }
    }
    
    /**
     * Export to CSV
     */
    private function exportCSV($items) {
        $filename = 'items_export_' . date('Y-m-d_His') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Header row
        fputcsv($output, ['Name', 'Code', 'Quantity', 'Unit Price', 'Description', 'Date Added']);
        
        // Data rows
        if ($items) {
            foreach ($items as $item) {
                fputcsv($output, [
                    $item->name,
                    $item->code,
                    $item->quantity,
                    $item->unitPrice,
                    $item->description ?: '',
                    $item->dateAdded
                ]);
            }
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Export to Excel (simplified)
     */
    private function exportExcel($items) {
        // For now, export as CSV with Excel content type
        // In production, use PhpSpreadsheet library
        header('Content-Type: application/vnd.ms-excel');
        $this->exportCSV($items);
    }
    
    /**
     * Bulk update prices
     */
    public function bulkUpdatePrices() {
        $this->genlib->ajaxOnly();
        $this->permissions->requirePermission('items', 'bulk_update');
        
        $item_ids = $this->input->post('item_ids');
        $update_type = $this->input->post('update_type'); // 'percentage', 'fixed', 'set'
        $value = floatval($this->input->post('value'));
        
        if (empty($item_ids) || !is_array($item_ids)) {
            $json = ['status' => 0, 'msg' => 'No items selected'];
            $this->output->set_content_type('application/json')->set_output(json_encode($json));
            return;
        }
        
        $this->db->trans_start();
        
        $success_count = 0;
        
        foreach ($item_ids as $item_id) {
            $this->db->select('unitPrice');
            $this->db->where('id', $item_id);
            $item_query = $this->db->get('items');
            
            if ($item_query->num_rows() === 0) continue;
            
            $item = $item_query->row();
            $current_price = floatval($item->unitPrice);
            $new_price = 0;
            
            switch ($update_type) {
                case 'percentage':
                    $new_price = $current_price * (1 + ($value / 100));
                    break;
                case 'fixed':
                    $new_price = $current_price + $value;
                    break;
                case 'set':
                    $new_price = $value;
                    break;
            }
            
            if ($new_price < 0) continue;
            
            $this->db->where('id', $item_id);
            $this->db->update('items', ['unitPrice' => round($new_price, 2)]);
            
            if ($this->db->affected_rows() > 0) {
                $success_count++;
                
                // Log event
                $this->db->select('name');
                $this->db->where('id', $item_id);
                $name_query = $this->db->get('items');
                $item_name = $name_query->num_rows() > 0 ? $name_query->row()->name : 'Unknown';
                $desc = "Bulk price update: {$item_name} price changed from {$current_price} to {$new_price}";
                $this->genmod->addevent("Bulk Price Update", $item_id, $desc, "items", $this->session->admin_id);
            }
        }
        
        $this->db->trans_complete();
        
        $json = [
            'status' => 1,
            'msg' => "Successfully updated {$success_count} item(s)",
            'updated' => $success_count
        ];
        
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    
    /**
     * Bulk stock adjustment
     */
    public function bulkStockAdjustment() {
        $this->genlib->ajaxOnly();
        $this->permissions->requirePermission('items', 'bulk_update');
        
        $item_ids = $this->input->post('item_ids');
        $adjustment_type = $this->input->post('adjustment_type'); // 'add', 'subtract', 'set'
        $quantity = floatval($this->input->post('quantity'));
        $reason = $this->input->post('reason') ?: 'Bulk stock adjustment';
        
        if (empty($item_ids) || !is_array($item_ids)) {
            $json = ['status' => 0, 'msg' => 'No items selected'];
            $this->output->set_content_type('application/json')->set_output(json_encode($json));
            return;
        }
        
        $this->db->trans_start();
        
        $success_count = 0;
        
        foreach ($item_ids as $item_id) {
            $this->db->select('quantity');
            $this->db->where('id', $item_id);
            $item_query = $this->db->get('items');
            
            if ($item_query->num_rows() === 0) continue;
            
            $item = $item_query->row();
            $current_qty = floatval($item->quantity);
            $updated = false;
            
            switch ($adjustment_type) {
                case 'add':
                    $updated = $this->item->newstock($item_id, $quantity);
                    break;
                case 'subtract':
                    if ($current_qty >= $quantity) {
                        $updated = $this->item->deficit($item_id, $quantity);
                    }
                    break;
                case 'set':
                    $this->db->where('id', $item_id);
                    $this->db->update('items', ['quantity' => $quantity]);
                    $updated = $this->db->affected_rows() > 0;
                    break;
            }
            
            if ($updated) {
                $success_count++;
                
                // Log event
                $this->db->select('name');
                $this->db->where('id', $item_id);
                $name_query = $this->db->get('items');
                $item_name = $name_query->num_rows() > 0 ? $name_query->row()->name : 'Unknown';
                $desc = "Bulk stock adjustment: {$quantity} quantities {$adjustment_type} for '{$item_name}'. Reason: {$reason}";
                $this->genmod->addevent("Bulk Stock Adjustment", $item_id, $desc, "items", $this->session->admin_id);
            }
        }
        
        $this->db->trans_complete();
        
        $json = [
            'status' => 1,
            'msg' => "Successfully adjusted {$success_count} item(s)",
            'updated' => $success_count
        ];
        
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    
    /**
     * Bulk delete/archive items
     */
    public function bulkDelete() {
        $this->genlib->ajaxOnly();
        $this->permissions->requirePermission('items', 'bulk_delete');
        
        $item_ids = $this->input->post('item_ids');
        $delete_type = $this->input->post('delete_type') ?: 'delete'; // 'delete' or 'archive'
        
        if (empty($item_ids) || !is_array($item_ids)) {
            $json = ['status' => 0, 'msg' => 'No items selected'];
            $this->output->set_content_type('application/json')->set_output(json_encode($json));
            return;
        }
        
        $this->db->trans_start();
        
        $success_count = 0;
        
        foreach ($item_ids as $item_id) {
            // Get item name before deletion/archive for logging
            $item_name = 'Unknown';
            $this->db->select('name');
            $this->db->where('id', $item_id);
            $name_query = $this->db->get('items');
            if ($name_query->num_rows() > 0) {
                $item_name = $name_query->row()->name;
            }
            
            if ($delete_type === 'archive') {
                // Soft delete - add archived flag if column exists
                $this->db->where('id', $item_id);
                $this->db->update('items', ['archived' => 1]);
                $updated = $this->db->affected_rows() > 0;
            } else {
                // Hard delete
                $this->db->where('id', $item_id);
                $this->db->delete('items');
                $updated = $this->db->affected_rows() > 0;
            }
            
            if ($updated) {
                $success_count++;
                
                $action = $delete_type === 'archive' ? 'archived' : 'deleted';
                $desc = "Bulk {$action}: Item '{$item_name}' was {$action}";
                $this->genmod->addevent("Bulk {$action}", $item_id, $desc, "items", $this->session->admin_id);
            }
        }
        
        $this->db->trans_complete();
        
        $json = [
            'status' => 1,
            'msg' => "Successfully {$delete_type}d {$success_count} item(s)",
            'deleted' => $success_count
        ];
        
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
}

