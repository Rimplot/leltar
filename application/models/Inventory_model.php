<?php

class Inventory_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function inventory($barcode = null, $storage_id = null) {
        if ($barcode !== null && $storage_id !== null) {
            $this->load->model('items_model');
            
            // get the id of the item with barcode == $barcode
            $this->db->select('*');
            $this->db->from('items');
            $this->db->where('barcode', $barcode);
            $query = $this->db->get();
            $item = $query->row_array();
            
            if ($this->db->error()['code'] || !$query->num_rows()) {
                $result['success'] = false;
            }
            else {
                $this->db->select('id, time');
                $this->db->from('inventory');
                $this->db->where('item_id', $item['id']);
                $this->db->where('latest', 1);
                $result = $this->db->get();
                $inventory = $result->row_array();

                date_default_timezone_set('Europe/Bratislava');
                $date1 = strtotime($inventory['time']);
                $date2 = time();

                if (abs($date2 - $date1) / 60 > 10) {
                    // set latest to false to all previous records
                    $this->db->update('inventory', array('latest' => 0), array('item_id' => $item['id'], 'latest' => 1));

                    // insert a new row into the table
                    $data = array(
                        'item_id' => $item['id'],
                        'storage_id' => $storage_id
                    );
                    $this->db->insert('inventory', $data);
            
                    $inventory_id = $this->db->insert_id();

                    // get back the details of the newly added row
                    $this->db->select('inventory.*, items.name, items.barcode, categories.name AS category, storages.name AS storage');
                    $this->db->from('inventory');
                    $this->db->where('inventory.id', $inventory_id);
                    $this->db->join('items', 'items.id = inventory.item_id');
                    $this->db->join('categories', 'categories.id = items.category_id');
                    $this->db->join('storages', 'storages.id = inventory.storage_id');

                    $result = $this->db->get()->row_array();
                    $result['success'] = true;
                }
                else {
                    $data = array(
                        'id' => $inventory['id'],
                        'item_id' => $item['id'],
                        'storage_id' => $storage_id,
                        'latest' => 1
                    );
                    
                    $this->db->replace('inventory', $data);

                    // get back the details of the updated added row
                    $this->db->select('inventory.*, items.name, items.barcode, items.category_id, categories.name AS category, storages.name AS storage');
                    $this->db->from('inventory');
                    $this->db->where('inventory.id', $inventory['id']);
                    $this->db->join('items', 'items.id = inventory.item_id');
                    $this->db->join('categories', 'categories.id = items.category_id');
                    $this->db->join('storages', 'storages.id = inventory.storage_id');

                    $result = $this->db->get()->row_array();
                    $result['success'] = true;
                }
            }

            return $result;
        }
    }

    public function list_inventory($limit = 0) {
        $this->db->select('inventory.*, items.name, items.barcode, items.category_id, categories.name AS category, storages.name AS storage');
        $this->db->from('inventory');
        $this->db->where('latest', 1);
        $this->db->join('items', 'items.id = inventory.item_id');
        $this->db->join('categories', 'categories.id = items.category_id');
        $this->db->join('storages', 'storages.id = inventory.storage_id');
        $this->db->order_by('time', 'DESC');
        if ($limit !== 0) $this->db->limit($limit);

        return $this->db->get()->result_array();
    }
}
