<?php

class Storages extends MY_Controller
{
    private $menu;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('storages_model');
        $this->load->model('sectors_model');
        $this->menu = "storages";
    }

    public function add()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Név', 'required');

        $data['page'] = 'add_storage';
        $data['page_title'] = "Raktár hozzáadása";
        $data['menu'] = $this->menu;

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('storages/' . $data['page'], $data);
            $this->load->view('templates/footer');
        } else {
            $id = $this->storages_model->add_storage();
            $this->session->set_flashdata('created', true);
            redirect('/storages/' . $id);
        }
    }

    public function archive($id)
    {
        $this->storages_model->archive_storage($id);
        $this->session->set_flashdata('archived', true);
        redirect('storages/archived');
    }

    public function archived() {
        $data['page'] = 'archived_storages';
        $data['page_title'] = "Archivált raktárak";
        $data['menu'] = $this->menu;
        $data['storages'] = $this->storages_model->get_archived_storages();

        $this->load->view('templates/header', $data);
        if ($this->session->flashdata('archived')) $this->load->view('success', array('type' => 'storage', 'action' => 'archived'));
        if ($this->session->flashdata('deleted')) $this->load->view('success', array('type' => 'storage', 'action' => 'deleted'));
        if ($this->session->flashdata('not_deleted')) $this->load->view('error', array('type' => 'storage', 'error' => 'deleted'));
        $this->load->view('storages/' . $data['page'], $data);
        $this->load->view('templates/footer');
    }

    public function delete($id)
    {
        if ($this->storages_model->deletable($id)) {
            $this->storages_model->delete_storage($id);
            $this->session->set_flashdata('deleted', true);
        } else {
            $this->session->set_flashdata('not_deleted', true);
        }
        redirect('storages/archived');
    }

    public function edit($id = false)
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Név', 'required');

        $data['page'] = 'edit_storage';
        $data['page_title'] = "Raktár szerkesztése";
        $data['menu'] = $this->menu;
        $data['storage'] = $this->storages_model->get_storages($id);

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('storages/' . $data['page'], $data);
            $this->load->view('templates/footer');
        } else {
            $this->storages_model->set_storage();
            $this->session->set_flashdata('modified', true);
            redirect('/storages/' . $id . '/success');
        }
    }

    public function index()
    {
        $data['page'] = 'storages';
        $data['page_title'] = "Raktárak";
        $data['menu'] = $this->menu;
        $data['storages'] = $this->storages_model->get_storages();

        $this->load->view('templates/header', $data);
        if ($this->session->flashdata('restored')) $this->load->view('success', array('type' => 'storage', 'action' => 'restored'));
        $this->load->view('storages/' . $data['page'], $data);
        $this->load->view('templates/footer');
    }

    public function restore($id)
    {
        $this->storages_model->restore_storage($id);
        $this->session->set_flashdata('restored', true);
        redirect('storages');
    }

    public function view($id = false, $msg = null)
    {
        $data['storage'] = $this->storages_model->get_storages($id);
        $data['sectors'] = $this->storages_model->get_sectors($id);
        for ($i = 0; $i < count($data['sectors']); $i++) {
            $data['sectors'][$i]['items_num'] = count($this->sectors_model->get_items_last_seen_in_sector($data['sectors'][$i]['id']));
        }
        $data['items'] = $this->storages_model->get_items_last_seen_in_storage($id);

        $data['page'] = 'storage';
        $data['page_title'] = $data['storage']['name'];
        $data['menu'] = $this->menu;

        $this->load->view('templates/header', $data);
        if ($this->session->flashdata('created')) $this->load->view('success', array('type' => 'storage', 'action' => 'created'));
        if ($this->session->flashdata('modified')) $this->load->view('success', array('type' => 'storage', 'action' => 'modified'));
        if ($this->session->flashdata('restored')) $this->load->view('success', array('type' => 'sector', 'action' => 'restored'));
        $this->load->view('storages/' . $data['page'], $data);
        $this->load->view('templates/footer');
    }
}
