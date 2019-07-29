<?php

class Auth_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function login($login, $password) {
        $reg_db = $this->load->database('reg', TRUE);

        $password = hash("sha256", $password);
        $login = mysqli_real_escape_string($reg_db->conn_id, $login);
        $sql = "SELECT id, password, lastname, firstname FROM users WHERE username='" . $login . "' OR email='" . $login . "' ";
        $user = $reg_db->query($sql)->row_array();

        if (!is_null($user) && $password == $user['password']) {
            $data = array(
                'logged_in' => true,
                'user_id' => $user['id'],
                'user_name' => $user['lastname'] . ' ' . $user['firstname']
            );
            $this->session->set_userdata($data);
            return true;
        }
        else {
            $this->session->set_userdata('logged_in', false);
            return false;
        }
    }

    public function logout() {
        $this->session->set_userdata('logged_in', false);
    }
}
