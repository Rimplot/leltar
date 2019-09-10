<?php

class Inventory_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function inventory($session_id = null, $barcode_id = null, $sector_id = null, $quantity = null) {
        if ($session_id == "") {
            $session_id = null;
        }
        
        if ($barcode_id !== null && $sector_id !== null) {
            $this->load->model('items_model');
            
            // get the id of the item with barcode_id == $barcode_id
            $this->db->select('*');
            $this->db->from('instances');
            $this->db->where('barcode_id', $barcode_id);
            $query = $this->db->get();
            $item = $query->row_array();
            
            if ($this->db->error()['code'] || !$query->num_rows()) {
                $result['success'] = false;
            }
            else {
                $this->db->select('id, time, session_id');
                $this->db->from('inventory');
                $this->db->where('item_id', $item['id']);
                $this->db->where('latest', 1);
                $result = $this->db->get();
                $inventory = $result->row_array();

                date_default_timezone_set('Europe/Bratislava');
                $date1 = strtotime($inventory['time']);
                $date2 = time();

                if ((abs($date2 - $date1) / 60 > 10) || ($inventory['session_id'] != $session_id)) {
                    // set latest to false to all previous records
                    $this->db->update('inventory', array('latest' => 0), array('item_id' => $item['id'], 'latest' => 1));

                    // insert a new row into the table
                    $data = array(
                        'session_id' => $session_id,
                        'item_id' => $item['id'],
                        'sector_id' => $sector_id,
                        'quantity' => $quantity
                    );
                    $this->db->insert('inventory', $data);
            
                    $inventory_id = $this->db->insert_id();

                    // get back the details of the newly added row
                    $this->db->select(
                        'inventory.*,
                        items.name,
                        barcodes.barcode,
                        categories.name AS category,
                        categories.id AS category_id,
                        storages.name AS storage,
                        storages.id AS storage_id,
                        sectors.name AS sector,
                        sessions.name AS session'
                    );
                    $this->db->from('inventory');
                    $this->db->where('inventory.id', $inventory_id);
                    $this->db->join('instances', 'instances.id = inventory.item_id');
                    $this->db->join('items', 'items.id = instances.item_id');
                    $this->db->join('barcodes', 'barcodes.id = instances.barcode_id');
                    $this->db->join('categories', 'categories.id = items.category_id', 'left');
                    $this->db->join('sessions', 'sessions.id = inventory.session_id');
                    $this->db->join('sectors', 'sectors.id = inventory.sector_id');
                    $this->db->join('storages', 'storages.id = sectors.storage_id');

                    $result = $this->db->get()->row_array();
                    $result['success'] = true;
                }
                else {
                    $data = array(
                        'id' => $inventory['id'],
                        'session_id' => $session_id,
                        'item_id' => $item['id'],
                        'sector_id' => $sector_id,
                        'quantity' => $quantity,
                        'latest' => 1
                    );
                    
                    $this->db->replace('inventory', $data);

                    // get back the details of the updated added row
                    $this->db->select(
                        'inventory.*,
                        items.name,
                        barcodes.barcode,
                        items.category_id,
                        categories.name AS category,
                        storages.name AS storage,
                        sectors.name AS sector,
                        sessions.name AS session'
                    );
                    $this->db->from('inventory');
                    $this->db->where('inventory.id', $inventory['id']);
                    $this->db->join('instances', 'instances.id = inventory.item_id');
                    $this->db->join('items', 'items.id = instances.item_id');
                    $this->db->join('barcodes', 'barcodes.id = instances.barcode_id');
                    $this->db->join('categories', 'categories.id = items.category_id');
                    $this->db->join('sessions', 'sessions.id = inventory.session_id');
                    $this->db->join('sectors', 'sectors.id = inventory.sector_id');
                    $this->db->join('storages', 'storages.id = sectors.storage_id');

                    $result = $this->db->get()->row_array();
                    $result['success'] = true;
                }
            }

            return $result;
        }
    }

    public function list_inventory($limit = 0) {
        $this->db->select(
            'inventory.*,
            items.name,
            barcodes.barcode,
            items.category_id,
            categories.name AS category,
            storages.name AS storage,
            storages.id AS storage_id,
            sectors.name AS sector,
            sessions.id AS session_id,
            sessions.name AS session'
        );

        $this->db->from('inventory');
        $this->db->where('latest', 1);
        $this->db->join('instances', 'instances.id = inventory.item_id');
        $this->db->join('items', 'items.id = instances.item_id');
        $this->db->join('barcodes', 'barcodes.id = instances.barcode_id');
        $this->db->join('categories', 'categories.id = items.category_id', 'left');
        $this->db->join('sessions', 'sessions.id = inventory.session_id', 'left');
        $this->db->join('sectors', 'sectors.id = inventory.sector_id');
        $this->db->join('storages', 'storages.id = sectors.storage_id');
        $this->db->order_by('time', 'DESC');
        if ($limit !== 0) $this->db->limit($limit);
        
        return $this->db->get()->result_array();
    }

    public function check_type($barcode) {
        $this->db
            ->select('*')
            ->from('barcodes')
            ->where('barcode', $barcode);
        $query = $this->db->get();

        if ($query->num_rows()) {
            $result = $query->row_array();
            $result['found'] = true;
        } else {
            $result = array('found' => false);
        }

        return $result;
    }
}
