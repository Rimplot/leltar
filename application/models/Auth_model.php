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
        
        $reg_db
            ->select('id, password, lastname, firstname')
            ->from('users')
            ->where('username', $login)
            ->or_where('email', $login);
        $user = $reg_db->get()->row_array();

        if (!is_null($user) && $password == $user['password']) {
            $_SESSION['user'] = array(
                'id' => $user['id'],
                'name' => $user['lastname'] . ' ' . $user['firstname']
            );
            return true;
        }
        else {
            return false;
        }
    }

    public function has_access($id) {
        $this->db
            ->select('role')
            ->from('users')
            ->where('id', $id);
        $result = $this->db->get();

        if ($result->num_rows()) {
            $role = $result->row_array()['role'];
            $_SESSION['logged_in'] = true;
            $_SESSION['user']['role'] = $role;

            return true;
        }
        else {
            $msg = $this->session->user['name'];
            $msg .= ' (#' . $this->session->user['id'];
            $msg .= ') megpróbált bejelentkezni a leltárba, de nincs hozzáférése.';

            mail("akislb99@gmail.com", "Bejelentkezési próbálkozás a leltárba", $msg, "From: akislb99@gmail.com");

            return false;
        }
    }

    public function logout() {
        $this->session->set_userdata('logged_in', false);
        $this->session->unset_userdata('user');
    }
}
