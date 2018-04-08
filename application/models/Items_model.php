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
            $query = $this->db->query("SELECT items.*, category.name AS category FROM items JOIN category ON category.id = items.category_id");
            return $query->result_array();
        }
    }
}