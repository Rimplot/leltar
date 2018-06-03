<?php

class Ajax_model extends CI_Model
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

            return $result;
        }
    }
}
