<?php

class Sectors extends CI_Controller
{
    private $menu;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('storages_model');
        $this->load->model('sectors_model');
        $this->menu = "sectors";
    }

    public function add($id = false)
    {
        if ($id === false)
            show_404();
        
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Név', 'required');

        $data['page'] = 'add_sector';
        $data['page_title'] = "Szektor hozzáadása ide: " . $this->storages_model->get_storages($id)['name'];
        $data['menu'] = $this->menu;
        $data['storage_id'] = $id;

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view($data['page'], $data);
            $this->load->view('templates/footer');
        } else {
            $id = $this->sectors_model->add_sector();
            redirect('/sectors/' . $id . '/success');
        }
    }

    public function archive($id)
    {
        $this->sectors_model->archive_sector($id);
        redirect('sectors/archived');
    }

    public function archived() {
        $data['page'] = 'archived_sectors';
        $data['page_title'] = "Archivált szektorok";
        $data['menu'] = $this->menu;
        $data['sectors'] = $this->sectors_model->get_archived_sectors();

        $this->load->view('templates/header', $data);
        $this->load->view($data['page'], $data);
        $this->load->view('templates/footer');
    }

    public function edit($id = false)
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Név', 'required');

        $data['page'] = 'edit_sector';
        $data['page_title'] = "Szektor szerkesztése";
        $data['menu'] = $this->menu;
        $data['sector'] = $this->sectors_model->get_sectors($id);

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view($data['page'], $data);
            $this->load->view('templates/footer');
        } else {
            $this->sectors_model->set_sector();
            redirect('/sectors/' . $id . '/success');
        }
    }

    public function index()
    {
        $data['page'] = 'sectors';
        $data['page_title'] = "Szektorok";
        $data['menu'] = $this->menu;
        $data['sectors'] = $this->sectors_model->get_sectors();

        $this->load->view('templates/header', $data);
        $this->load->view($data['page'], $data);
        $this->load->view('templates/footer');
    }

    public function restore($id)
    {
        $this->sectors_model->restore_sector($id);
        redirect('sectors');
    }

    public function view($id = false, $msg = null)
    {
        $data['page'] = 'sector';
        $data['page_title'] = "Szektor";
        $data['menu'] = $this->menu;
        $data['sector'] = $this->sectors_model->get_sectors($id);
        $data['items'] = $this->sectors_model->get_items_last_seen_in_sector($id);

        $this->load->view('templates/header', $data);
        if ($msg == 'success') $this->load->view('success');
        $this->load->view($data['page'], $data);
        $this->load->view('templates/footer');
    }
}
