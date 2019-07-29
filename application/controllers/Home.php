<?php

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth_model');
    }

    public function index() {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['page'] = 'home';
        $data['page_title'] = "Főoldal";
        $data['menu'] = "home";

        $this->form_validation->set_rules('login', 'Felhasználónév', 'required');
        $this->form_validation->set_rules('password', 'Jelszó', 'required');

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view($data['page'], $data);
            $this->load->view('templates/footer');
        }
        else {
            $this->form_validation->set_rules('login', 'Felhasználónév', 'callback_login', array('login' => ' '));
            $this->form_validation->set_rules('password', 'Jelszó', 'callback_login', array('login' => 'Helytelen felhasználónév-jelszó páros'));
            if ($this->form_validation->run() === false) {
                $this->load->view('templates/header', $data);
                $this->load->view($data['page'], $data);
                $this->load->view('templates/footer');
            }
            else {
                redirect($_SERVER['REQUEST_URI']);
            }
        }
    }

    public function login($param) {
        return $this->auth_model->login($this->input->post('login'), $this->input->post('password'));
    }
}
