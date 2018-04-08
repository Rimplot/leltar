<?php

class Items extends CI_Controller
{
    public function index() {
        $data['page_title'] = "Eszközök";

        $this->load->view('templates/header', $data);
        $this->load->view('items', $data);
        $this->load->view('templates/footer');
    }
}