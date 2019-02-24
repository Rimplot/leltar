<?php

class Barcodes extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('items_model');
    }

    public function index() {
        $barcode = $this->input->get('barcode');
        $item = $this->items_model->get_item_by_barcode($barcode);
        if ($item['type_id'] == BOX_TYPE_ID) {
            redirect('boxes/' . $item['id']);
        }
        else {
            redirect('items/' . $item['id']);
        }
    }

    public function generate($barcode = null) {
        if ($barcode !== null) {
            $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
            header("Content-type: image/png");
            echo $generator->getBarcode($barcode, $generator::TYPE_EAN_8, 10, 300);
        }
    }
}
