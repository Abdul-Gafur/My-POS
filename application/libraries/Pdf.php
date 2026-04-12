<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * PDF Library
 * Simple PDF generation using TCPDF or mPDF
 */
class Pdf {
    
    private $ci;
    
    public function __construct() {
        $this->ci =& get_instance();
    }
    
    /**
     * Generate PDF from HTML
     * Uses browser print functionality for PDF generation
     * @param string $html HTML content
     * @param string $filename Output filename
     * @param bool $download Force download
     * @param string $paper_size Paper size (A4, Letter, etc.)
     * @param string $orientation Portrait or Landscape
     */
    public function generate($html, $filename = 'document.pdf', $download = true, $paper_size = 'A4', $orientation = 'portrait') {
        // Load the print view with HTML content
        $data = [
            'html' => $html,
            'filename' => $filename,
            'orientation' => $orientation
        ];
        
        $this->ci->load->view('items/items_pdf_print', $data);
    }
}

