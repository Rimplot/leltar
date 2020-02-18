<?php

class Labels extends MY_Controller
{
    private $menu;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('labels_model');
        $this->menu = "labels";
    }

    public function add()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Név', 'required');

        $data['page'] = 'add_label';
        $data['page_title'] = "Címke hozzáadása";
        $data['menu'] = $this->menu;
        $data['labels'] = $this->labels_model->get_labels();

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('labels/' . $data['page'], $data);
            $this->load->view('templates/footer');
        } else {
            $id = $this->labels_model->add_label();
            $this->session->set_flashdata('created', true);
            redirect('/labels/' . $id . '/success');
        }
    }

    public function delete($id)
    {
        $this->labels_model->delete_label($id);
        $this->session->set_flashdata('deleted', true);
        redirect('labels');
    }

    public function edit($id = false)
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Név', 'required');

        $data['page'] = 'edit_label';
        $data['page_title'] = "Címke szerkesztése";
        $data['menu'] = $this->menu;
        $data['label'] = $this->labels_model->get_labels($id);
        $data['labels'] = $this->labels_model->get_labels();

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('labels/' . $data['page'], $data);
            $this->load->view('templates/footer');
        } else {
            $this->labels_model->set_label();
            $this->session->set_flashdata('modified', true);
            redirect('/labels/' . $id);
        }
    }

    public function index()
    {
        $data['page'] = 'labels';
        $data['page_title'] = "Címkék";
        $data['menu'] = $this->menu;
        $data['labels'] = $this->labels_model->get_labels();

        $this->load->view('templates/header', $data);
        if ($this->session->flashdata('deleted')) $this->load->view('success', array('type' => 'label', 'action' => 'deleted'));
        $this->load->view('labels/' . $data['page'], $data);
        $this->load->view('templates/footer');
    }

    public function view($id = false, $msg = null)
    {
        $data['label'] = $this->labels_model->get_labels($id);
        $data['categories'] = $this->labels_model->get_categories_with_label($id);
        
        $data['page'] = 'label';
        $data['page_title'] = $data['label']['name'];
        $data['menu'] = $this->menu;

        $this->load->view('templates/header', $data);
        if ($this->session->flashdata('created')) $this->load->view('success', array('type' => 'label', 'action' => 'created'));
        if ($this->session->flashdata('modified')) $this->load->view('success', array('type' => 'label', 'action' => 'modified'));
        $this->load->view('labels/' . $data['page'], $data);
        $this->load->view('templates/footer');
    }
}