<?php

class Users_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_name($id = null)
    {
        if ($id !== null) {
            $reg_db = $this->load->database('reg', TRUE);

            $reg_db
                ->select('lastname, firstname')
                ->from('users')
                ->where('id', $id);
            $user = $reg_db->get()->row_array();

            return $user['lastname'] . ' ' . $user['firstname'];
        }
    }
}
