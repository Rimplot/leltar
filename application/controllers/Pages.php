<?php

class Pages extends CI_Controller
{
    public function index() {
        $data['page_title'] = "Főoldal";

        $this->load->view('templates/header', $data);
        $this->load->view('home', $data);
        $this->load->view('templates/footer');
    }
}
