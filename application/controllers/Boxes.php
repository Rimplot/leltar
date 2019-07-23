<?php

class Boxes extends CI_Controller
{
    private $menu;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('boxes_model');
        $this->menu = "boxes";
    }

    public function add()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Név', 'required');
        $this->form_validation->set_rules('barcode', 'Vonalkód', 'required');

        $data['page'] = 'add_box';
        $data['page_title'] = "Doboz hozzáadása";
        $data['menu'] = $this->menu;
        $data['boxes'] = $this->boxes_model->get_boxes();

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('boxes/' . $data['page'], $data);
            $this->load->view('templates/footer');
        } else {
            $id = $this->boxes_model->add_box();
            $this->session->set_flashdata('created', true);
            redirect('/boxes/' . $id);
        }
    }

    public function delete($id)
    {
        $this->boxes_model->delete_box($id);
        $this->session->set_flashdata('deleted', true);
        redirect('boxes');
    }

    public function edit($id = false)
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Név', 'required');
        $this->form_validation->set_rules('barcode', 'Vonalkód', 'required');

        $data['page'] = 'edit_box';
        $data['page_title'] = "Doboz szerkesztése";
        $data['menu'] = $this->menu;
        $data['box'] = $this->boxes_model->get_boxes($id);
        $data['boxes'] = $this->boxes_model->get_boxes();

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('boxes/' . $data['page'], $data);
            $this->load->view('templates/footer');
        } else {
            $this->boxes_model->set_box();
            $this->session->set_flashdata('modified', true);
            redirect('/boxes/' . $id);
        }
    }

    public function index()
    {
        $data['page'] = 'boxes';
        $data['page_title'] = "Dobozok";
        $data['menu'] = $this->menu;
        $data['boxes'] = $this->boxes_model->get_boxes();

        $this->load->view('templates/header', $data);
        if ($this->session->flashdata('deleted')) $this->load->view('success', array('type' => 'box', 'action' => 'deleted'));
        $this->load->view('boxes/' . $data['page'], $data);
        $this->load->view('templates/footer');
    }

    public function view($id = false, $msg = null)
    {
        $data['page'] = 'box';
        $data['page_title'] = "Doboz";
        $data['menu'] = $this->menu;
        $data['box'] = $this->boxes_model->get_boxes($id);
        $data['items'] = $this->boxes_model->get_items_in_box($id);

        $this->load->view('templates/header', $data);
        if ($this->session->flashdata('created')) $this->load->view('success', array('type' => 'box', 'action' => 'created'));
        if ($this->session->flashdata('modified')) $this->load->view('success', array('type' => 'box', 'action' => 'modified'));
        $this->load->view('boxes/' . $data['page'], $data);
        $this->load->view('templates/footer');
    }
}