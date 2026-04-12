<?php
defined('BASEPATH') OR exit('');

/**
 * Reports Controller
 * Comprehensive reporting and analytics
 */
class Reports extends CI_Controller{
    
    public function __construct(){
        parent::__construct();
        
        $this->genlib->checkLogin();
        $this->permissions->requirePermission('reports', 'view');
        
        $this->load->model(['report', 'analytic', 'transaction', 'item']);
    }
    
    
    public function index(){
        $data['totalSales'] = $this->transaction->totalEarnedToday();
        $data['totalItems'] = $this->db->count_all('items');
        $data['totalTransactions'] = $this->transaction->totalTransactions();
        $data['salesByDay'] = $this->analytic->getDailyTrans(0, 7);
        $data['topProducts'] = $this->analytic->topDemanded();
        
        $data['pageContent'] = $this->load->view('reports/index', $data, TRUE);
        $data['pageTitle'] = "Reports & Analytics";
        
        $this->load->view('main', $data);
    }
    
    /**
     * Sales report
     */
    public function sales() {
        $date_from = $this->input->get('date_from') ?: date('Y-m-01');
        $date_to = $this->input->get('date_to') ?: date('Y-m-d');
        
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        $data['transactions'] = $this->transaction->getDateRange($date_from, $date_to);
        
        // Calculate totals
        $totalSales = 0;
        $totalQuantity = 0;
        if ($data['transactions']) {
            foreach ($data['transactions'] as $trans) {
                $totalSales += $trans->totalMoneySpent;
                $totalQuantity += $trans->quantity;
            }
        }
        $data['totalSales'] = $totalSales;
        $data['totalQuantity'] = $totalQuantity;
        
        $this->load->view('reports/sales', $data);
    }
    
    /**
     * Product performance report
     */
    public function products() {
        $orderBy = $this->input->get('orderBy') ?: 'name';
        $orderFormat = $this->input->get('orderFormat') ?: 'ASC';
        
        $data['products'] = $this->item->getAll($orderBy, $orderFormat);
        
        // Calculate performance metrics for each product
        if ($data['products']) {
            foreach ($data['products'] as $product) {
                // Get total sold
                $this->db->select_sum('quantity');
                $this->db->where('itemCode', $product->code);
                $sold_query = $this->db->get('transactions');
                $product->totalSold = $sold_query->num_rows() > 0 ? floatval($sold_query->row()->quantity) : 0;
                
                // Get total earned
                $this->db->select_sum('totalPrice');
                $this->db->where('itemCode', $product->code);
                $earned_query = $this->db->get('transactions');
                $product->totalEarned = $earned_query->num_rows() > 0 ? floatval($earned_query->row()->totalPrice) : 0;
            }
        }
        
        $this->load->view('reports/products', $data);
    }
    
    /**
     * Export sales report
     */
    public function exportSales() {
        $this->permissions->requirePermission('reports', 'export');
        
        $date_from = $this->input->get('date_from') ?: date('Y-m-01');
        $date_to = $this->input->get('date_to') ?: date('Y-m-d');
        
        $transactions = $this->transaction->getDateRange($date_from, $date_to);
        
        $filename = 'sales_report_' . $date_from . '_to_' . $date_to . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        fputcsv($output, ['Date', 'Reference', 'Items', 'Quantity', 'Total Amount', 'Payment Method', 'Staff']);
        
        if ($transactions) {
            foreach ($transactions as $trans) {
                fputcsv($output, [
                    $trans->transDate,
                    $trans->ref,
                    $trans->quantity . ' items',
                    $trans->quantity,
                    $trans->totalMoneySpent,
                    $trans->modeOfPayment,
                    $trans->staffName ?: 'N/A'
                ]);
            }
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Get chart data (AJAX)
     */
    public function getChartData() {
        $this->genlib->ajaxOnly();
        
        $type = $this->input->get('type'); // daily, monthly, yearly, top_products
        $date_from = $this->input->get('date_from');
        $date_to = $this->input->get('date_to');
        
        $data = [];
        
        switch ($type) {
            case 'daily':
                $daily = $this->analytic->getDailyTrans(0, 30);
                if ($daily) {
                    foreach ($daily as $day) {
                        $data[] = [
                            'date' => $day->transactionDate,
                            'sales' => floatval($day->tot_earned),
                            'transactions' => intval($day->tot_trans),
                            'quantity' => intval($day->qty_sold)
                        ];
                    }
                }
                break;
                
            case 'top_products':
                $top = $this->analytic->topDemanded();
                if ($top) {
                    foreach ($top as $product) {
                        $data[] = [
                            'name' => $product->name,
                            'sold' => intval($product->totSold)
                        ];
                    }
                }
                break;
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}