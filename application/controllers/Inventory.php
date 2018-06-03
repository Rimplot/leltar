<?php

class Inventory extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('items_model');
    }

    public function add()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Név', 'required');
        $this->form_validation->set_rules('barcode', 'Vonalkód', 'required');

        $data['page'] = 'add_item';
        $data['page_title'] = "Eszköz hozzáadása";

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view($data['page'], $data);
            $this->load->view('templates/footer');
        } else {
            $id = $this->items_model->add_item();
            redirect('/items/' . $id . '/success');
        }
    }

    public function delete($id)
    {
        $this->items_model->delete_item($id);
        redirect('items');
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
            redirect('/items/' . $id . '/success');
        }
    }

    public function index()
    {
        $data['page'] = 'inventory';
        $data['page_title'] = "Leltárazás";
        //$data['items'] = $this->items_model->get_items();

        $this->load->view('templates/header', $data);
        $this->load->view($data['page'], $data);
        $this->load->view('templates/footer');
    }

    public function view($id = false, $msg = null)
    {
        $data['page'] = 'item';
        $data['page_title'] = "Eszköz";
        $data['item'] = $this->items_model->get_items($id);
        $data['inventory_history'] = $this->items_model->get_item_history($id);

        $this->load->view('templates/header', $data);
        if ($msg == 'success') $this->load->view('success');
        $this->load->view($data['page'], $data);
        $this->load->view('templates/footer');
    }
}
