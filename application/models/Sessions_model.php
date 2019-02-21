<?php

class Sessions_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function start_session($name) {
        $data = array(
            'name' => $name
        );
        $this->db->insert('sessions', $data);

        return $this->db->insert_id();
    }

    public function stop_session($id = null) {
        if ($id !== null) {
            $this->db->where('id', $id);
            return $this->db->update('sessions', ['end' => date('Y-m-d H:i:s')]);
        }
    }

    public function get_sessions($id = null) {
        $this->db->select('*');
        $this->db->from('sessions');

        if ($id === null) {
            $query = $this->db->get();
            return $query->result_array();
        } else {
            $this->db->where('id', $id);
            return $this->db->get()->row_array();
        }
    }

    public function get_running_sessions($id = null) {
        $this->db->select('*');
        $this->db->from('sessions');
        $this->db->where('end', null);

        if ($id === null) {
            $query = $this->db->get();
            return $query->result_array();
        } else {
            $this->db->where('id', $id);
            return $this->db->get()->row_array();
        }
    }

    public function get_session_items($id = null) {
        if ($id !== null) {
            $this->db->select('
                items.id,
                items.name,
                items.barcode,
                inventory.time,
                storages.name AS storage,
                storages.id AS storage_id,
                sectors.name AS sector,
                sectors.id AS sector_id
            ');
            $this->db->from('inventory');
            $this->db->where('session_id', $id);
            $this->db->order_by('time', 'DESC');
            $this->db->join('items', 'items.id = inventory.item_id');
            $this->db->join('sessions', 'sessions.id = inventory.session_id', 'left');
            $this->db->join('sectors', 'sectors.id = inventory.sector_id', 'left');
            $this->db->join('storages', 'storages.id = sectors.storage_id');
            $query = $this->db->get();

            if ($this->db->error()['code']) {
                die($this->db->error()['code'] . ': ' . $this->db->error()['message']);
            }
            
            return $query->result_array();
        }
    }
}
