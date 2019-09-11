<?php

class Sessions extends MY_Controller
{
    private $menu;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('sessions_model');
        $this->menu = "sessions";
    }

    public function stop($id)
    {
        $this->sessions_model->stop_session($id);
        $this->session->set_flashdata('finished', true);
        redirect('sessions/' . $id);
    }

    public function restart($id)
    {
        $this->sessions_model->restart_session($id);
        $this->session->set_flashdata('restarted', true);
        redirect('sessions/' . $id);
    }

    public function index()
    {
        $data['page'] = 'sessions';
        $data['page_title'] = "Sessions";
        $data['menu'] = $this->menu;
        $data['sessions'] = $this->sessions_model->get_sessions();

        $this->load->view('templates/header', $data);
        $this->load->view('sessions/' . $data['page'], $data);
        $this->load->view('templates/footer');
    }

    public function view($id = false, $msg = null)
    {
        $data['page'] = 'session';
        $data['page_title'] = "Sessions";
        $data['menu'] = $this->menu;
        $data['session'] = $this->sessions_model->get_sessions($id);
        $data['items'] = $this->sessions_model->get_session_items($id);

        $this->load->view('templates/header', $data);
        if ($this->session->flashdata('finished')) $this->load->view('success', array('type' => 'session', 'action' => 'finished'));
        if ($this->session->flashdata('restarted')) $this->load->view('success', array('type' => 'session', 'action' => 'restarted'));
        $this->load->view('sessions/' . $data['page'], $data);
        $this->load->view('templates/footer');
    }
}
