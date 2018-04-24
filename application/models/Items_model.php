<?php

class Items_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function add_item()
    {
        $data = array(
            'name' => $this->input->post('name'),
            'barcode' => $this->input->post('barcode'),
            'category_id' => $this->input->post('category_id')
        );

        $this->db->insert('items', $data);

        return $this->db->insert_id();
    }

    public function delete_item($id = null)
    {
        if ($id !== null) {
            $data['id'] = $id;
            $this->db->delete('items', $data);
        }
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

            if ($this->db->error()['code'] || !$query->num_rows()) {
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
