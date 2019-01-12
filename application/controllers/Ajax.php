<?php

class Ajax extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('inventory_model');
    }

    public function inventory() {
        $result = $this->inventory_model->inventory($this->input->post('barcode'), $this->input->post('sector'));
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }
}