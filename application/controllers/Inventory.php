<?php

class Inventory extends MY_Controller
{
    private $menu;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('sessions_model');
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
        for ($i = 0; $i < count($data['storages']); $i++) {
            $data['storages'][$i]['sectors'] = $this->storages_model->get_sectors($data['storages'][$i]['id']);
        }
        $data['inventory'] = $this->inventory_model->list_inventory();
        $data['sessions'] = $this->sessions_model->get_running_sessions();

        $this->load->view('templates/header', $data);
        $this->load->view($data['page'], $data);
        $this->load->view('templates/footer');
    }
}
