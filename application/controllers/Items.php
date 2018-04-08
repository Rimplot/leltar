<?php

class Items extends CI_Controller
{
    public function index() {
        $data['page'] = 'items';
        $data['page_title'] = "Eszközök";

        $this->load->view('templates/header', $data);
        $this->load->view($data['page'], $data);
        $this->load->view('templates/footer');
    }
}