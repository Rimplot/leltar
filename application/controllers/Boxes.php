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

        $data['page'] = 'add_box';
        $data['page_title'] = "Doboz hozzáadása";
        $data['menu'] = $this->menu;
        $data['boxes'] = $this->boxes_model->get_boxes();

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view($data['page'], $data);
            $this->load->view('templates/footer');
        } else {
            $id = $this->boxes_model->add_box();
            redirect('/boxes/' . $id . '/success');
        }
    }

    public function delete($id)
    {
        $this->boxes_model->delete_box($id);
        redirect('boxes');
    }

    public function edit($id = false)
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Név', 'required');

        $data['page'] = 'edit_box';
        $data['page_title'] = "Doboz szerkesztése";
        $data['menu'] = $this->menu;
        $data['box'] = $this->boxes_model->get_boxes($id);
        $data['boxes'] = $this->boxes_model->get_boxes();

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view($data['page'], $data);
            $this->load->view('templates/footer');
        } else {
            $this->boxes_model->set_box();
            redirect('/boxes/' . $id . '/success');
        }
    }

    public function index()
    {
        $data['page'] = 'boxes';
        $data['page_title'] = "Dobozok";
        $data['menu'] = $this->menu;
        $data['boxes'] = $this->boxes_model->get_boxes();

        $this->load->view('templates/header', $data);
        $this->load->view($data['page'], $data);
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
        if ($msg == 'success') $this->load->view('success');
        $this->load->view($data['page'], $data);
        $this->load->view('templates/footer');
    }
}