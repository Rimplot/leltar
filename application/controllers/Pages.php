<?php

class Pages extends CI_Controller
{
    public function index() {
        $data['page_title'] = "Home";

        $this->load->view('templates/header', $data);
        $this->load->view('pages/home', $data);
        $this->load->view('templates/footer');
    }
}
