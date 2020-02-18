<?php

class Categories extends MY_Controller
{
    private $menu;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('categories_model');
        $this->load->model('labels_model');
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
        $data['labels'] = $this->labels_model->get_labels();

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('categories/' . $data['page'], $data);
            $this->load->view('templates/footer');
        } else {
            $id = $this->categories_model->add_category();
            $this->session->set_flashdata('created', true);
            redirect('/categories/' . $id);
        }
    }

    public function delete($id)
    {
        $this->categories_model->delete_category($id);
        $this->session->set_flashdata('deleted', true);
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
        $data['labels'] = $this->labels_model->get_labels();

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('categories/' . $data['page'], $data);
            $this->load->view('templates/footer');
        } else {
            $this->categories_model->set_category();
            $this->session->set_flashdata('modified', true);
            redirect('/categories/' . $id);
        }
    }

    public function index()
    {
        $data['page'] = 'categories';
        $data['page_title'] = "Kategóriák";
        $data['menu'] = $this->menu;
        $data['categories'] = $this->categories_model->get_categories();

        $this->load->view('templates/header', $data);
        if ($this->session->flashdata('deleted')) $this->load->view('success', array('type' => 'category', 'action' => 'deleted'));
        $this->load->view('categories/' . $data['page'], $data);
        $this->load->view('templates/footer');
    }

    public function view($id = false, $msg = null)
    {
        $data['category'] = $this->categories_model->get_categories($id);
        $data['items'] = $this->categories_model->get_items_in_category($id);

        $data['page'] = 'category';
        $data['page_title'] = $data['category']['name'];
        $data['menu'] = $this->menu;

        $this->load->view('templates/header', $data);
        if ($this->session->flashdata('created')) $this->load->view('success', array('type' => 'category', 'action' => 'created'));
        if ($this->session->flashdata('modified')) $this->load->view('success', array('type' => 'category', 'action' => 'modified'));
        $this->load->view('categories/' . $data['page'], $data);
        $this->load->view('templates/footer');
    }
}