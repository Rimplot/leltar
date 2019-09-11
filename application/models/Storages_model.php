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

    public function get_sectors($id = false)
    {
        if ($id !== false) {
            $this->db->select('sectors.*, barcodes.barcode');
            $this->db->from('sectors');
            $this->db->join('barcodes', 'barcodes.id = sectors.barcode_id');
            $this->db->where('storage_id', $id);
            $this->db->where('archived <> 1');
            $query = $this->db->get();

            return $query->result_array();
        }
    }

    public function get_archived_storages()
    {
        $this->db->select('*');
        $this->db->from('storages');
        $this->db->where('archived = 1');
        $result = $this->db->get()->result_array();

        for ($i = 0; $i < count($result); $i++) {
            $result[$i]['deletable'] = $this->deletable($result[$i]['id']);
        }

        return $result;
    }

    public function get_items_last_seen_in_storage($id = null) {
        if ($id !== null) {
            $sectors = $this->get_sectors($id);
            $items = array();

            foreach ($sectors as $sector) {
                $this->db->select('instances.*, items.category_id, items.type_id, items.name AS name, categories.name AS category, inventory.time');
                $this->db->from('inventory');
                $this->db->where('latest = 1');
                $this->db->where('sector_id', $sector['id']);
                $this->db->join('instances', 'instances.id = inventory.item_id');
                $this->db->join('items', 'items.id = instances.item_id');
                $this->db->join('categories', 'categories.id = items.category_id');

                $query = $this->db->get();

                $result = $query->result_array();
                for ($i = 0; $i < count($result); $i++) {
                    $result[$i]['sector'] = $sector['name'];
                    $result[$i]['sector_id'] = $sector['id'];
                }
                $items = array_merge($items, $result);
            }

            return $items;
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

    public function deletable($id) {
        return count($this->get_items_last_seen_in_storage($id)) == 0;
    }

    public function delete_storage($id)
    {
        $this->db->delete('storages', array('id' => $id));
    }
}
