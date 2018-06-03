<?php

class Ajax extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ajax_model');
    }

    public function inventory() {
        header('Content-Type: application/json');
        echo json_encode($this->ajax_model->inventory($this->input->post('barcode'), $this->input->post('storage')));
    }
}