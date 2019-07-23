<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Migration_Additional_item_properties extends CI_Migration {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
    }

    public function up()
    {
        // add columns 'date_created', 'date_bought', 'value' to table 'items'
        $fields = array(
            'date_bought' => array(
                'type' => 'DATE',
                'null' => TRUE,
                'after' => 'date_created'
            ),
            'value' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE,
                'after' => 'date_bought'
            )
        );
        $this->dbforge->add_field('date_created DATETIME DEFAULT NULL AFTER barcode');
        $this->dbforge->add_column('items', $fields);

        $this->db->query('ALTER TABLE `items` CHANGE `date_created` `date_created` DATETIME NULL DEFAULT CURRENT_TIMESTAMP;');


        // guess creation date for existing items from first inventory
        $this->db->select('id');
        $this->db->from('items');
        $this->db->where('type_id <> ' . BOX_TYPE_ID);
        $items = $this->db->get()->result_array();

        foreach ($items as $item) {
            $this->db->select('time');
            $this->db->from('inventory');
            $this->db->where('item_id = ' . $item['id']);
            $this->db->order_by('time', 'asc');
            $result = $this->db->get();
            
            if ($result->num_rows()) {
                $this->db->set('date_created', $result->row()->time);
                $this->db->where('id', $item['id']);
                $this->db->update('items');
            }
        }
    }

    public function down()
    {
        $this->dbforge->drop_column('items', 'date_created');
        $this->dbforge->drop_column('items', 'date_bought');
        $this->dbforge->drop_column('items', 'value');
    }
}