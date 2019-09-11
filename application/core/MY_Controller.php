<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();

        if (!$this->session->logged_in) {
            $this->session->set_flashdata('auth_required', true);
            $this->session->set_userdata('redirect_url', current_url());
            redirect('');
        }
        else if ($this->session->logged_in) {
            $this->session->unset_userdata('redirect_url');
        }
    }
}