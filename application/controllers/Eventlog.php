<?php
defined('BASEPATH') OR exit('');

/**
 * Description of Eventlog
 *
 *  
 *   
 *    
 */
class Eventlog extends CI_Controller{
    
    public function __construct(){
        parent::__construct();
        
        $this->genlib->checkLogin();
        
        $this->genlib->superOnly();
    }
    
    
    public function index(){
        $data['pageContent'] = $this->load->view('Eventlog', '', TRUE);
        $data['pageTitle'] = "Eventlog";
        
        $this->load->view('main', $data);
    }
}