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

    public function restart_session($id = null) {
        if ($id !== null) {
            $this->db->where('id', $id);
            $this->db->set('end', null);
            $this->db->update('sessions');
        }
    }

    public function stop_session($id = null) {
        if ($id !== null) {
            $this->db->select('time');
            $this->db->from('inventory');
            $this->db->where('session_id', $id);
            $this->db->order_by('time', 'desc');
            $this->db->limit(1);
            $result = $this->db->get();

            if ($result->num_rows()) {
                $time = $result->row_array()['time'];
                $this->db->where('id', $id);
                return $this->db->update('sessions', ['end' => $time]);
            }
        }
    }

    public function get_sessions($id = null) {
        $this->db->select('*');
        $this->db->from('sessions');

        if ($id === null) {
            $query = $this->db->get();
            $result = $query->result_array();

            for ($i = 0; $i < count($result); $i++) {
                $result[$i]['item_num'] = count($this->get_session_items($result[$i]['id']));
            }

            return $result;
        } else {
            $this->db->where('id', $id);
            $result = $this->db->get()->row_array();
            $result['item_num'] = count($this->get_session_items($id));

            return $result;
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
                items.name,
                instances.*,
                barcodes.barcode,
                inventory.time,
                storages.name AS storage,
                storages.id AS storage_id,
                sectors.name AS sector,
                sectors.id AS sector_id
            ');
            $this->db->from('inventory');
            $this->db->where('session_id', $id);
            $this->db->order_by('time', 'DESC');
            $this->db->join('instances', 'instances.id = inventory.item_id');
            $this->db->join('items', 'items.id = instances.item_id');
            $this->db->join('barcodes', 'barcodes.id = instances.barcode_id');
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
