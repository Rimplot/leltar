<?php

class Sectors_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function add_sector()
    {
        $data = array(
            'name' => $this->input->post('name'),
            'barcode' => ($this->input->post('barcode') == 0) ? NULL : $this->input->post('barcode'),
            'storage_id' => $this->input->post('storage_id')
        );

        $this->db->insert('sectors', $data);

        if ($this->db->error()['code']) {
            die($this->db->error()['code'] . ': ' . $this->db->error()['message']);
        }

        return $this->db->insert_id();
    }

    public function archive_sector($id = null)
    {
        if ($id !== null) {
            $data = array('archived' => 1);
            $this->db->where('id', $id);
    
            return $this->db->update('sectors', $data);
        }
    }

    public function get_sectors($id = false)
    {
        $this->db->select('*');
        $this->db->from('sectors');

        if ($id === false) {
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

    public function get_archived_sectors($id = false)
    {
        if ($id !== false) {
            $this->db->select('*');
            $this->db->from('sectors');
            $this->db->where('storage_id', $id);
            $this->db->where('archived = 1');
            $query = $this->db->get();

            return $query->result_array();
        }
    }

    public function get_items_last_seen_in_sector($id = null) {
        if ($id !== null) {
            $this->db->select('items.*, items.name AS name, categories.name AS category, inventory.time');
            $this->db->from('inventory');
            $this->db->where('latest = 1');
            $this->db->where('sector_id', $id);
            $this->db->join('items', 'items.id = inventory.item_id');
            $this->db->join('categories', 'categories.id = items.category_id', 'left');

            $query = $this->db->get();

            if ($this->db->error()['code']) {
                die($this->db->error()['code'] . ': ' . $this->db->error()['message']);
            }
            
            return $query->result_array();
        }
    }

    public function restore_sector($id = null)
    {
        if ($id !== null) {
            $data = array('archived' => 0);
            $this->db->where('id', $id);
    
            return $this->db->update('sectors', $data);
        }
    }

    public function set_sector()
    {
        $data = array(
            'name' => $this->input->post('name'),
            'barcode' => ($this->input->post('barcode') == '') ? NULL : $this->input->post('barcode')
        );

        $this->db->where('id', $this->input->post('id'));

        return $this->db->update('sectors', $data);
    }
}
