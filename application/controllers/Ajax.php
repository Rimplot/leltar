<?php

class Ajax extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('inventory_model');
        $this->load->model('items_model');
        $this->load->model('boxes_model');
        $this->load->model('sessions_model');
        $this->load->model('sectors_model');
    }

    public function inventory() {
        $quantity = ($this->input->post('quantity')) ? $this->input->post('quantity') : NULL;
        $result = $this->inventory_model->inventory(
            $this->input->post('session_id'),
            $this->input->post('barcode_id'),
            $this->input->post('sector'),
            $quantity
        );
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function check_type() {
        $barcode = $this->input->post('barcode');
        $result = $this->inventory_model->check_type($barcode);

        if ($result['found']) {
            if ($result['type'] == BARCODE_TYPE_ID['item']) {
                // check if it is stock
                $item = $this->items_model->get_item_by_barcode($barcode);
                $result['stock'] = ($item['type_id'] == ITEM_TYPE_ID['stock']);
            }
            else if ($result['type'] == BARCODE_TYPE_ID['box']) {
                $box = $this->boxes_model->get_box_by_barcode($barcode);
                $items = $this->boxes_model->get_items_in_box($box['id']);

                $result['box'] = $box;
                $result['items'] = $items;
            }
            else if ($result['type'] == BARCODE_TYPE_ID['sector']) {
                $sector = $this->sectors_model->get_sector_by_barcode($barcode);
                $result['sector'] = $sector;
            }
        }

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
            $last_digit = (10 - $sum % 10) % 10;
            $barcode .= $last_digit;

            $used = $this->items_model->check_barcode_used($barcode);
        } while($used);

        header('Content-Type: application/json');
        echo json_encode(array('barcode' => $barcode));
    }
}