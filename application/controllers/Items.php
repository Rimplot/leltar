<?php

class Items extends CI_Controller
{
    private $menu;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('items_model');
        $this->load->model('categories_model');
        $this->load->model('boxes_model');
        $this->load->model('owners_model');
        $this->load->model('storages_model');
        $this->menu = "items";
    }

    public function add()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Név', 'required');
        $this->form_validation->set_rules('barcode', 'Vonalkód', 'required');

        $data['page'] = 'add_item';
        $data['page_title'] = "Eszköz hozzáadása";
        $data['menu'] = $this->menu;
        $data['categories'] = $this->categories_model->get_categories();
        $data['boxes'] = $this->boxes_model->get_boxes();
        $data['owners'] = $this->owners_model->get_owners();

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('items/' . $data['page'], $data);
            $this->load->view('templates/footer');
        } else {
            $id = $this->items_model->add_item();
            $this->session->set_flashdata('created', true);
            redirect('/items/' . $id);
        }
    }

    public function delete($id)
    {
        $this->items_model->delete_item($id);
        $this->session->set_flashdata('deleted', true);
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
        $data['menu'] = $this->menu;
        $data['item'] = $this->items_model->get_items($id);
        $data['categories'] = $this->categories_model->get_categories();
        $data['boxes'] = $this->boxes_model->get_boxes();
        $data['owners'] = $this->owners_model->get_owners();

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('items/' . $data['page'], $data);
            $this->load->view('templates/footer');
        } else {
            $this->items_model->set_items();
            $this->session->set_flashdata('modified', true);
            redirect('/items/' . $id);
        }
    }

    public function index()
    {
        $data['page'] = 'items';
        $data['page_title'] = "Eszközök";
        $data['menu'] = $this->menu;
        $data['items'] = $this->items_model->get_items();

        $this->load->view('templates/header', $data);
        if ($this->session->flashdata('deleted')) $this->load->view('success', array('type' => 'item', 'action' => 'deleted'));
        $this->load->view('items/' . $data['page'], $data);
        $this->load->view('templates/footer');
    }

    public function view($id = false, $msg = null)
    {
        $data['page'] = 'item';
        $data['page_title'] = "Eszköz";
        $data['menu'] = $this->menu;
        $data['item'] = $this->items_model->get_items($id);
        $data['inventory_history'] = $this->items_model->get_item_history($id);
        $data['storages'] = $this->storages_model->get_storages();
        for ($i = 0; $i < count($data['storages']); $i++) {
            $data['storages'][$i]['sectors'] = $this->storages_model->get_sectors($data['storages'][$i]['id']);
        }

        $this->load->view('templates/header', $data);
        if ($this->session->flashdata('created')) $this->load->view('success', array('type' => 'item', 'action' => 'created'));
        if ($this->session->flashdata('modified')) $this->load->view('success', array('type' => 'item', 'action' => 'modified'));
        $this->load->view('items/' . $data['page'], $data);
        $this->load->view('templates/footer');
    }
}
