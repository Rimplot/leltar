<?php

class Ajax extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('inventory_model');
        $this->load->model('sessions_model');
    }

    public function inventory() {
        $result = $this->inventory_model->inventory(
            $this->input->post('session_id'),
            $this->input->post('barcode'),
            $this->input->post('sector')
        );
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function start_session() {
        $result = $this->sessions_model->start_session($this->input->post('name'));
    }

    public function stop_session($id) {
        $result = $this->sessions_model->stop_session($id);
    }
}