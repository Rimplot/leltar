<?php

class Items extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('items_model');
    }

    public function edit($id = false)
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Név', 'required');
        $this->form_validation->set_rules('barcode', 'Vonalkód', 'required');

        $data['page'] = 'edit_item';
        $data['page_title'] = "Eszköz szerkesztése";
        $data['item'] = $this->items_model->get_items($id);

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view($data['page'], $data);
            $this->load->view('templates/footer');
        } else {
            $this->items_model->set_items();
            $this->view($id);
        }
    }

    public function index()
    {
        $data['page'] = 'items';
        $data['page_title'] = "Eszközök";
        $data['items'] = $this->items_model->get_items();

        $this->load->view('templates/header', $data);
        $this->load->view($data['page'], $data);
        $this->load->view('templates/footer');
    }

    public function view($id = false)
    {
        if ($id === false) {
            $this->index();
            return;
        }

        $data['page'] = 'item';
        $data['page_title'] = "Eszköz";
        $data['item'] = $this->items_model->get_items($id);

        $this->load->view('templates/header', $data);
        $this->load->view($data['page'], $data);
        $this->load->view('templates/footer');
    }
}
