<?php

class Printer extends MY_Controller
{
    private $menu;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['page'] = 'printer';
        $data['page_title'] = "Nyomtatás";
        $data['menu'] = $this->menu;

        $this->load->view('templates/header', $data);
        $this->load->view($data['page'], $data);
        $this->load->view('templates/footer');
    }
}