<?php

class Items extends MY_Controller
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
        $this->load->model('users_model');
        $this->menu = "items";
    }

    public function add($id = null)
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        if (!$id) {
            $this->form_validation->set_rules('name', 'Név', 'required');
        }
        $this->form_validation->set_rules('barcode', 'Vonalkód', 'required');

        $data['id'] = $id;
        $data['page'] = 'add_item';
        $data['page_title'] = ($id) ? "Példány hozzáadása" : "Eszköz hozzáadása";
        $data['menu'] = $this->menu;
        $data['categories'] = $this->categories_model->get_categories();
        $data['boxes'] = $this->boxes_model->get_boxes();
        $data['owners'] = $this->owners_model->get_owners();
        
        if ($id) {
            $data['item'] = $this->items_model->get($id);
        }

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('items/' . $data['page'], $data);
            $this->load->view('templates/footer');
        } else {
            if ($id) {
                $id = $this->items_model->add_item($id);
            }
            else {
                $id = $this->items_model->add_item();
            }
            
            if ($id !== null) {
                $this->session->set_flashdata('created', true);
                redirect('/items/' . $id);
            }
            else {
                $this->session->set_flashdata('not_created', true);
                redirect('/items');
            }
        }
    }

    public function delete($id)
    {
        $this->items_model->delete_item($id);
        $this->session->set_flashdata('deleted', true);
        redirect('items');
    }

    public function edit($id = null, $mode = null)
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['menu'] = $this->menu;
        $data['item'] = $this->items_model->get_items($id);
        $data['categories'] = $this->categories_model->get_categories();
        $data['boxes'] = $this->boxes_model->get_boxes();
        $data['owners'] = $this->owners_model->get_owners();

        $multiple_instances = count($this->items_model->get_instances($data['item']['item_id'])) > 1;

        if ($multiple_instances && $mode) {
            $data['page'] = 'edit_instance';
            $data['page_title'] = "Példány szerkesztése";
            //$this->form_validation->set_rules('barcode', 'Vonalkód', 'required');
        }
        else if ($multiple_instances) {
            $data['page'] = 'edit_main_item';
            $data['page_title'] = "Eszköz szerkesztése";
            $this->form_validation->set_rules('name', 'Név', 'required');
        }
        else {
            $data['page'] = 'edit_item';
            $data['page_title'] = "Eszköz szerkesztése";
            $this->form_validation->set_rules('name', 'Név', 'required');
            //$this->form_validation->set_rules('barcode', 'Vonalkód', 'required');
        }

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('items/' . $data['page'], $data);
            $this->load->view('templates/footer');
        } else {
            if ($multiple_instances && $mode) {
                $this->items_model->set_items(false, true);
            }
            else if ($multiple_instances) {
                $this->items_model->set_items(true, false);
            }
            else {
                $this->items_model->set_items();
            }
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
        if ($this->session->flashdata('not_created')) $this->load->view('error', array('type' => 'item', 'error' => 'created'));
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
        $data['instances'] = $this->items_model->get_instances($data['item']['item_id']);

        $data['item']['creator_name'] = $this->users_model->get_name($data['item']['created_by']);
        if ($data['item']['last_modified_by'] !== null) {
            if ($data['item']['last_modified_by'] !== $data['item']['created_by']) {
                $data['item']['last_modified_name'] = $this->users_model->get_name($data['item']['last_modified_by']);
            } else {
                $data['item']['last_modified_name'] = $data['item']['creator_name'];
            }
        }
        
        $this->load->view('templates/header', $data);
        if ($this->session->flashdata('created')) $this->load->view('success', array('type' => 'item', 'action' => 'created'));
        if ($this->session->flashdata('modified')) $this->load->view('success', array('type' => 'item', 'action' => 'modified'));
        $this->load->view('items/' . $data['page'], $data);
        $this->load->view('templates/footer');
    }
}
