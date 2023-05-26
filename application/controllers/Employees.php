<?php
defined('BASEPATH') OR exit('');

/**
 * Description of Home
 *
 *  
 *   
 *    
 */
class Employees extends CI_Controller{
    
    public function __construct(){
        parent::__construct();
        
        $this->genlib->checkLogin();
        
        $this->genlib->superOnly();
    }
    
    
    public function index(){
        $data['pageContent'] = $this->load->view('employees', '', TRUE);
        $data['pageTitle'] = "Employees";
        
        $this->load->view('main', $data);
    }
}