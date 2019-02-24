<?php

class Ajax extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('inventory_model');
        $this->load->model('items_model');
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
        echo $this->sessions_model->start_session($this->input->post('name'));
    }

    public function stop_session($id) {
        $this->sessions_model->stop_session($id);
    }

    public function get_unique_barcode() {
        do {
            $barcode = strval(rand(1000000, 9999999));

            $sum = 0;
            for ($i = 0; $i < strlen($barcode); $i += 2) {
                $sum += $barcode[$i] * 3;
            }
            for ($i = 1; $i < strlen($barcode); $i += 2) {
                $sum += $barcode[$i];
            }
            $last_digit = 10 - $sum % 10;
            $barcode .= $last_digit;

            $used = $this->items_model->check_barcode_used($barcode);
        } while($used);

        header('Content-Type: application/json');
        echo json_encode(array('barcode' => $barcode));
    }
}