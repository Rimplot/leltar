<?php

class Categories extends CI_Controller
{
    public function index() {
        $data['page'] = 'categories';
        $data['page_title'] = "Kategóriák";

        $this->load->view('templates/header', $data);
        $this->load->view($data['page'], $data);
        $this->load->view('templates/footer');
    }
}