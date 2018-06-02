<?php

class Storages extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('storages_model');
    }

    /*public function add()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Név', 'required');

        $data['page'] = 'add_category';
        $data['page_title'] = "Kategória hozzáadása";

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view($data['page'], $data);
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
        $data['category'] = $this->categories_model->get_categories($id);

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view($data['page'], $data);
            $this->load->view('templates/footer');
        } else {
            $this->categories_model->set_category();
            redirect('/categories/' . $id . '/success');
        }
    }*/

    public function index()
    {
        $data['page'] = 'storages';
        $data['page_title'] = "Raktárak";
        $data['storages'] = $this->storages_model->get_storages();

        $this->load->view('templates/header', $data);
        $this->load->view($data['page'], $data);
        $this->load->view('templates/footer');
    }

    public function view($id = false, $msg = null)
    {
        $data['page'] = 'storage';
        $data['page_title'] = "Raktár";
        $data['storage'] = $this->storages_model->get_storages($id);
        $data['items'] = $this->storages_model->get_items_last_seen_in_category($id);

        $this->load->view('templates/header', $data);
        if ($msg == 'success') $this->load->view('success');
        $this->load->view($data['page'], $data);
        $this->load->view('templates/footer');
    }
}
