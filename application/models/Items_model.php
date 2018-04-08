<?php

class Items_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_items($id = false)
    {
        if ($id === false) {
            $query = $this->db->get('items');
            return $query->result_array();
        }
    }
}