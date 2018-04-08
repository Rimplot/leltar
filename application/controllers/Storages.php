<?php

class Storages extends CI_Controller
{
    public function index() {
        $data['page'] = 'storages';
        $data['page_title'] = "Raktárak";

        $this->load->view('templates/header', $data);
        $this->load->view($data['page'], $data);
        $this->load->view('templates/footer');
    }
}