<?php

class Inventory extends CI_Controller
{
    private $menu;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('storages_model');
        $this->load->model('inventory_model');
        $this->menu = "inventory";
    }

    public function index()
    {
        $data['page'] = 'inventory';
        $data['page_title'] = "Leltárazás";
        $data['menu'] = $this->menu;
        $data['storages'] = $this->storages_model->get_storages();
        $data['inventory'] = $this->inventory_model->list_inventory();

        $this->load->view('templates/header', $data);
        $this->load->view($data['page'], $data);
        $this->load->view('templates/footer');
    }
}
