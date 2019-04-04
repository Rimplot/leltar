<?php

class Categories extends CI_Controller
{
    private $menu;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('categories_model');
        $this->menu = "categories";
    }

    public function add()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Név', 'required');

        $data['page'] = 'add_category';
        $data['page_title'] = "Kategória hozzáadása";
        $data['menu'] = $this->menu;
        $data['categories'] = $this->categories_model->get_categories();

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('categories/' . $data['page'], $data);
            $this->load->view('templates/footer');
        } else {
            $id = $this->categories_model->add_category();
            redirect('/categories/' . $id . '/success');
        }
    }

    public function delete($id)
    {
        $this->categories_model->delete_category($id);
        redirect('categories');
    }

    public function edit($id = false)
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Név', 'required');

        $data['page'] = 'edit_category';
        $data['page_title'] = "Kategória szerkesztése";
        $data['menu'] = $this->menu;
        $data['category'] = $this->categories_model->get_categories($id);
        $data['categories'] = $this->categories_model->get_categories();

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('categories/' . $data['page'], $data);
            $this->load->view('templates/footer');
        } else {
            $this->categories_model->set_category();
            redirect('/categories/' . $id . '/success');
        }
    }

    public function index()
    {
        $data['page'] = 'categories';
        $data['page_title'] = "Kategóriák";
        $data['menu'] = $this->menu;
        $data['categories'] = $this->categories_model->get_categories();

        $this->load->view('templates/header', $data);
        $this->load->view('categories/' . $data['page'], $data);
        $this->load->view('templates/footer');
    }

    public function view($id = false, $msg = null)
    {
        $data['page'] = 'category';
        $data['page_title'] = "Kategória";
        $data['menu'] = $this->menu;
        $data['category'] = $this->categories_model->get_categories($id);
        $data['items'] = $this->categories_model->get_items_in_category($id);

        $this->load->view('templates/header', $data);
        if ($msg == 'success') $this->load->view('success');
        $this->load->view('categories/' . $data['page'], $data);
        $this->load->view('templates/footer');
    }
}