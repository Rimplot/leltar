<?php

class Owners extends CI_Controller
{
    private $menu;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('owners_model');
        $this->menu = "owners";
    }

    public function add()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Név', 'required');

        $data['page'] = 'add_owner';
        $data['page_title'] = "Tulajdonos hozzáadása";
        $data['menu'] = $this->menu;

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('owners/' . $data['page'], $data);
            $this->load->view('templates/footer');
        } else {
            $id = $this->owners_model->add_owner();
            redirect('/owners/' . $id . '/success');
        }
    }

    public function delete($id)
    {
        $this->owners_model->delete_owner($id);
        redirect('owners');
    }

    public function edit($id = false)
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Név', 'required');

        $data['page'] = 'edit_owner';
        $data['page_title'] = "Tulajdonos szerkesztése";
        $data['menu'] = $this->menu;
        $data['owner'] = $this->owners_model->get_owners($id);

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('owners/' . $data['page'], $data);
            $this->load->view('templates/footer');
        } else {
            $this->owners_model->set_owner();
            redirect('/owners/' . $id . '/success');
        }
    }

    public function index()
    {
        $data['page'] = 'owners';
        $data['page_title'] = "Tulajdonosok";
        $data['menu'] = $this->menu;
        $data['owners'] = $this->owners_model->get_owners();

        $this->load->view('templates/header', $data);
        $this->load->view('owners/' . $data['page'], $data);
        $this->load->view('templates/footer');
    }

    public function view($id = false, $msg = null)
    {
        $data['page'] = 'owner';
        $data['page_title'] = "Tulajdonos";
        $data['menu'] = $this->menu;
        $data['owner'] = $this->owners_model->get_owners($id);
        $data['items'] = $this->owners_model->get_items_of_owner($id);

        $this->load->view('templates/header', $data);
        if ($msg == 'success') $this->load->view('success');
        $this->load->view('owners/' . $data['page'], $data);
        $this->load->view('templates/footer');
    }
}