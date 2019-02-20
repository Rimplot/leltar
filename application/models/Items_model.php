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
            'category_id' => $this->input->post('category_id'),
            'type' => $this->input->post('type'),
            'box_id' => $this->input->post('box')
        );

        $this->db->insert('items', $data);

        return $this->db->insert_id();
    }

    public function delete_item($id = null)
    {
        if ($id !== null) {
            $this->db->delete('items', array('id' => $id));
            $this->db->delete('inventory', array('item_id' => $id));
        }
    }

    public function get_items($id = false)
    {
        $this->db->select('items.*, categories.name AS category, types.name AS type');
        $this->db->from('items');
        $this->db->where('type_id <> ' . BOX_TYPE_ID);
        $this->db->join('categories', 'categories.id = items.category_id', 'left');
        $this->db->join('types', 'types.id = items.type_id');

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

    public function get_item_by_barcode($barcode = null) {
        if ($barcode !== null) {
            $this->db->select('id, type_id');
            $this->db->from('items');
            $this->db->where('barcode', $barcode);

            return $this->db->get()->row_array();
        }
    }

    public function get_item_history($id = null) {
        if ($id !== null) {
            $this->db->select(
                'inventory.*,
                storages.name AS storage,
                storages.id AS storage_id,
                sectors.name AS sector,
                sectors.id AS sector_id,
                sessions.name AS session,
                sessions.id AS session_id'
            );
            $this->db->from('inventory');
            $this->db->where('item_id', $id);
            $this->db->order_by('time', 'DESC');
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

    public function get_last_seen($id = null) {
        if ($id !== null) {
            $this->db->select(
                'inventory.*,
                storages.name AS storage_name,
                storages.id AS storage_id,
                sessions.name AS session');
            $this->db->from('inventory');
            $this->db->where('item_id', $id);
            $this->db->where('latest', 1);
            $this->db->join('sessions', 'sessions.id = inventory.session_id', 'left');
            $this->db->join('sectors', 'sectors.id = inventory.sector_id', 'left');
            $this->db->join('storages', 'storages.id = sectors.storage_id');
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
            'name' => $this->input->post('name'),
            'barcode' => ($this->input->post('barcode') == 0) ? NULL : $this->input->post('barcode'),
            'category_id' => $this->input->post('category_id'),
            'type_id' => $this->input->post('type'),
            'box_id' => ($this->input->post('box') == 0) ? NULL : $this->input->post('box')
        );

        $this->db->where('id', $this->input->post('id'));
        return $this->db->update('items', $data);
    }

    public function check_barcode_used($barcode)
    {
        $this->db->select('*');
        $this->db->from('items');
        $this->db->where('barcode', $barcode);
        return boolval($this->db->get()->num_rows());
    }
}
