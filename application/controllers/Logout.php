<?php

class Logout extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth_model');
    }

    public function index() {
        $this->auth_model->logout();
        redirect('');
    }
}
