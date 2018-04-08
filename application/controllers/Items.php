<?php

class Items extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('items_model');
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
