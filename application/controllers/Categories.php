<?php

class Categories extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('categories_model');
    }

    public function index()
    {
        $data['page'] = 'categories';
        $data['page_title'] = "Kategóriák";
        $data['categories'] = $this->categories_model->get_categories();

        $this->load->view('templates/header', $data);
        $this->load->view($data['page'], $data);
        $this->load->view('templates/footer');
    }

    public function view($id = false, $msg = null)
    {
        $data['page'] = 'category';
        $data['page_title'] = "Kategória";
        $data['category'] = $this->categories_model->get_categories($id);
        $data['items'] = $this->categories_model->get_items_in_category($id);

        $this->load->view('templates/header', $data);
        if ($msg == 'success') $this->load->view('success');
        $this->load->view($data['page'], $data);
        $this->load->view('templates/footer');
    }
}