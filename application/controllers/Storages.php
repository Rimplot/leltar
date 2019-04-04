<?php

class Storages extends CI_Controller
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
            redirect('/storages/' . $id . '/success');
        }
    }

    public function archive($id)
    {
        $this->storages_model->archive_storage($id);
        redirect('storages/archived');
    }

    public function archived() {
        $data['page'] = 'archived_storages';
        $data['page_title'] = "Archivált raktárak";
        $data['menu'] = $this->menu;
        $data['storages'] = $this->storages_model->get_archived_storages();

        $this->load->view('templates/header', $data);
        $this->load->view('storages/' . $data['page'], $data);
        $this->load->view('templates/footer');
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
        $this->load->view('storages/' . $data['page'], $data);
        $this->load->view('templates/footer');
    }

    public function restore($id)
    {
        $this->storages_model->restore_storage($id);
        redirect('storages');
    }

    public function view($id = false, $msg = null)
    {
        $data['page'] = 'storage';
        $data['page_title'] = "Raktár";
        $data['menu'] = $this->menu;
        $data['storage'] = $this->storages_model->get_storages($id);
        $data['sectors'] = $this->storages_model->get_sectors($id);
        for ($i = 0; $i < count($data['sectors']); $i++) {
            $data['sectors'][$i]['items_num'] = count($this->sectors_model->get_items_last_seen_in_sector($data['sectors'][$i]['id']));
        }
        $data['items'] = $this->storages_model->get_items_last_seen_in_storage($id);

        $this->load->view('templates/header', $data);
        if ($msg == 'success') $this->load->view('success');
        $this->load->view('storages/' . $data['page'], $data);
        $this->load->view('templates/footer');
    }
}
