<?php

class Items_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function add_item($id = null)
    {
        if ($id === null) {
            $data = array(
                'name' => $this->input->post('name'),
                'category_id' => ($this->input->post('category_id') == 0) ? NULL : $this->input->post('category_id'),
                'type_id' => $this->input->post('type')
            );
            $this->db->insert('items', $data);
        }

        $data = array(
            'barcode' => ($this->input->post('barcode') == 0) ? NULL : $this->input->post('barcode'),
            'date_bought' => ($this->input->post('date_bought') == "") ? NULL : $this->input->post('date_bought'),
            'value' => ($this->input->post('value') == "") ? NULL : $this->input->post('value'),
            'box_id' => ($this->input->post('box') == 0) ? NULL : $this->input->post('box'),
            'owner_id' => ($this->input->post('owner') == 0) ? NULL : $this->input->post('owner'),
            'stock' => ($this->input->post('stock') == 0) ? NULL : $this->input->post('stock')
        );
        $data['item_id'] = ($id === null) ? $this->db->insert_id() : $id;
        $this->db->insert('instances', $data);

        return $this->db->insert_id();
    }

    public function delete_item($id = null)
    {
        if ($id !== null) {
            $item_id = $this->db->query('SELECT item_id FROM instances WHERE id = ' . $id)->row_array()['item_id'];
            $this->db->delete('instances', array('id' => $id));
            $this->db->delete('inventory', array('item_id' => $id));

            if ($this->db->query('SELECT * FROM instances WHERE item_id = ' . $item_id)->num_rows() == 0) {
                $this->db->delete('items', array('id' => $item_id));
            }
        }
    }

    public function get_items($id = false)
    {
        $this->db->select('items.*, instances.*, categories.name AS category, types.name AS type, labels.content AS label, owners.name AS owner');
        $this->db->from('items');
        $this->db->where('type_id <> ' . BOX_TYPE_ID);
        $this->db->join('instances', 'instances.item_id = items.id');
        $this->db->join('categories', 'categories.id = items.category_id', 'left');
        $this->db->join('labels', 'labels.id = categories.label_id', 'left');
        $this->db->join('types', 'types.id = items.type_id');
        $this->db->join('owners', 'owners.id = instances.owner_id', 'left');
        $this->db->order_by('instances.id', 'DESC');
        $this->db->order_by('items.id', 'DESC');

        if ($id === false) {
            $query = $this->db->get();
            $result = $query->result_array();

            for ($i = 0; $i < count($result); $i++) {
                $result[$i]['last_seen'] = $this->get_last_seen($result[$i]['id']);
                $result[$i]['instance_count'] = count($this->get_instances($result[$i]['item_id']));
            }

            return $result;
        } else {
            $this->db->where('instances.id = ' . $id);
            $query = $this->db->get();

            if ($this->db->error()['code'] || !$query->num_rows()) {
                show_404();
            }
            
            $result = $query->row_array();
            $result['last_seen'] = $this->get_last_seen($id);

            return $result;
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

    private function history_db_query($id) {
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
        
        return $query;
    }

    public function get_item_history($id = null) {
        if ($id !== null) {
            return $this->history_db_query($id)->result_array();
        }
    }

    public function get_last_seen($id = null) {
        if ($id !== null) {
            return $this->history_db_query($id)->row_array();
        }
    }

    public function set_items($item = true, $instance = true)
    {
        if ($item) {
            $data = array(
                'name' => $this->input->post('name'),
                'category_id' => ($this->input->post('category_id') == 0) ? NULL : $this->input->post('category_id'),
                'type_id' => $this->input->post('type')
            );
            $this->db->where('id', $this->input->post('item_id'));
            $this->db->update('items', $data);
        }

        if ($instance) {
            $data = array(
                'barcode' => ($this->input->post('barcode') == 0) ? NULL : $this->input->post('barcode'),
                'date_bought' => ($this->input->post('date_bought') == "") ? NULL : $this->input->post('date_bought'),
                'value' => ($this->input->post('value') == "") ? NULL : $this->input->post('value'),
                'box_id' => ($this->input->post('box') == 0) ? NULL : $this->input->post('box'),
                'owner_id' => ($this->input->post('owner') == 0) ? NULL : $this->input->post('owner'),
                'stock' => ($this->input->post('stock') == 0) ? NULL : $this->input->post('stock')
            );
            $this->db->where('id', $this->input->post('id'));
            $this->db->update('instances', $data);
        }
    }

    public function check_barcode_used($barcode)
    {
        $this->db->select('*');
        $this->db->from('instances');
        $this->db->where('barcode', $barcode);
        return boolval($this->db->get()->num_rows());
    }

    public function get($id) {
        $this->db->select('*');
        $this->db->from('items');
        $this->db->where('id = ' . $id);
        return $this->db->get()->row_array();
    }

    public function get_instances($id = null) {
        if ($id !== null) {
            $this->db->select('instances.*, owners.name AS owner');
            $this->db->from('instances');
            $this->db->join('owners', 'owners.id = instances.owner_id', 'left');
            $this->db->where('item_id', $id);
            return $this->db->get()->result_array();
        }
    }
}
