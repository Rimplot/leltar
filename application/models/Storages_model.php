<?php

class Storages_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function add_storage()
    {
        $data = array(
            'name' => $this->input->post('name'),
            'address' => $this->input->post('address')
        );

        $this->db->insert('storages', $data);

        return $this->db->insert_id();
    }

    public function archive_storage($id = null)
    {
        if ($id !== null) {
            $data = array('archived' => 1);
            $this->db->where('id', $id);
    
            return $this->db->update('storages', $data);
        }
    }

    public function get_storages($id = false)
    {
        $this->db->select('*');
        $this->db->from('storages');

        if ($id === false) {
            $this->db->where('archived <> 1');
            $query = $this->db->get();

            return $query->result_array();
        } else {
            $this->db->where('id = ' . $id);
            $query = $this->db->get();
            $result = $query->row_array();

            if ($this->db->error()['code'] || !$query->num_rows()) {
                show_404();
            }
            
            return $result;
        }
    }

    public function get_archived_storages()
    {
        $this->db->select('*');
        $this->db->from('storages');
        $this->db->where('archived = 1');
        $query = $this->db->get();

        return $query->result_array();
    }

    public function get_items_last_seen_in_storage($id = null) {
        if ($id !== null) {
            $this->db->select('items.*, items.name AS name, categories.name AS category, inventory.time');
            $this->db->from('inventory');
            $this->db->where('latest = 1');
            $this->db->where('storage_id', $id);
            $this->db->join('items', 'items.id = inventory.item_id');
            $this->db->join('categories', 'categories.id = items.category_id');

            $query = $this->db->get();

            if ($this->db->error()['code']) {
                die($this->db->error()['code'] . ': ' . $this->db->error()['message']);
            }
            
            return $query->result_array();
        }
    }

    public function restore_storage($id = null)
    {
        if ($id !== null) {
            $data = array('archived' => 0);
            $this->db->where('id', $id);
    
            return $this->db->update('storages', $data);
        }
    }

    public function set_storage()
    {
        $data = array(
            'name' => $this->input->post('name'),
            'address' => $this->input->post('address')
        );

        $this->db->where('id', $this->input->post('id'));

        return $this->db->update('storages', $data);
    }
}
