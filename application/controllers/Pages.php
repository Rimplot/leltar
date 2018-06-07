<?php

class Pages extends CI_Controller
{
    public function index() {
        $data['page'] = 'home';
        $data['page_title'] = "Főoldal";
        $data['menu'] = "home";

        $this->load->view('templates/header', $data);
        $this->load->view($data['page'], $data);
        $this->load->view('templates/footer');
    }
}
