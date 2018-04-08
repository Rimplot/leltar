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

            if ($this->db->error()['code']) {
                show_404();
            }
            
            return $query->row_array();
        }
    }

    public function set_items()
    {
        $data = array(
            'id' => $this->input->post('id'),
            'name' => $this->input->post('name'),
            'barcode' => $this->input->post('barcode'),
            'category_id' => $this->input->post('category_id')
        );

        return $this->db->replace('items', $data);
    }
}
