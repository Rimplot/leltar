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
            $result = $query->result_array();

            for ($i = 0; $i < count($result); $i++) {
                $result[$i]['last_seen'] = $this->get_last_seen($result[$i]['id']);
            }

            return $result;
        } else {
            $this->db->where('items.id = ' . $id);
            $query = $this->db->get();

            if ($this->db->error()['code'] || !$query->num_rows()) {
                show_404();
            }
            
            return $query->row_array();
        }
    }

    public function get_last_seen($id = null) {
        if ($id !== null) {
            $this->db->select('inventory.*, storage.name AS storage_name');
            $this->db->from('inventory');
            $this->db->where('equipment_id', $id);
            $this->db->where('latest', 1);
            $this->db->join('storage', 'storage.id = inventory.storage_id');
            $query = $this->db->get();

            if ($this->db->error()['code']) {
                die($this->db->error()['code'] . ': ' . $this->db->error()['message']);
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
