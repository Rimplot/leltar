<?php

class Items_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_items($id = false)
    {
        $this->db->select('items.*, category.name AS category');
        $this->db->from('items');
        $this->db->join('category', 'category.id = items.category_id');

        if ($id === false) {
            $query = $this->db->get();

            return $query->result_array();
        } else {
            $this->db->where('items.id = ' . $id);
            $query = $this->db->get();
            
            return $query->row_array();
        }
    }
}
