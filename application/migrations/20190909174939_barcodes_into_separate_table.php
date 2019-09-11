<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Migration_Barcodes_into_separate_table extends CI_Migration {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
    }

    public function up()
    {
        // add table 'barcodes'
        $fields = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'barcode' => array(
                'type' => 'VARCHAR',
                'constraint' => 32
            ),
            'type' => array(
                'type' => 'INT',
                'constraint' => 11
            )
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('barcodes', FALSE, $attributes);

        // add column barcode_id
        $fields = array(
            'barcode_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => FALSE,
                'after' => 'barcode'
            )
        );
        $this->dbforge->add_column('instances', $fields);
        $this->dbforge->add_column('boxes', $fields);
        $this->dbforge->add_column('sectors', $fields);

        // migrate existing barcodes to the new table
        // instances
        $this->db
            ->select('id, barcode')
            ->from('instances')
            ->order_by('id', 'asc');
        $instances = $this->db->get()->result_array();

        foreach ($instances as $instance) {
            $data = array(
                'barcode' => $instance['barcode'],
                'type' => 1
            );
            $this->db->insert('barcodes', $data);
            $insert_id = $this->db->insert_id();
            $this->db->update('instances', array('barcode_id' => $insert_id), array('id' => $instance['id']));
        }
        
        // boxes
        $this->db
            ->select('id, barcode')
            ->from('boxes')
            ->order_by('id', 'asc');
        $boxes = $this->db->get()->result_array();

        foreach ($boxes as $box) {
            $data = array(
                'barcode' => $box['barcode'],
                'type' => 2
            );
            $this->db->insert('barcodes', $data);
            $insert_id = $this->db->insert_id();
            $this->db->update('boxes', array('barcode_id' => $insert_id), array('id' => $box['id']));
        }

        // sectors
        $this->db
            ->select('id, barcode')
            ->from('sectors')
            ->order_by('id', 'asc');
        $sectors = $this->db->get()->result_array();

        foreach ($sectors as $sector) {
            $data = array(
                'barcode' => $sector['barcode'],
                'type' => 3
            );
            $this->db->insert('barcodes', $data);
            $insert_id = $this->db->insert_id();
            $this->db->update('sectors', array('barcode_id' => $insert_id), array('id' => $sector['id']));
        }


        // remove column barcode
        $this->dbforge->drop_column('instances', 'barcode');
        $this->dbforge->drop_column('boxes', 'barcode');
        $this->dbforge->drop_column('sectors', 'barcode');

        
        // add foreign keys
        $this->dbforge->add_column('instances',['CONSTRAINT `instance_barcode` FOREIGN KEY (`barcode_id`) REFERENCES `barcodes`(`id`)',]);
        $this->dbforge->add_column('boxes',['CONSTRAINT `box_barcode` FOREIGN KEY (`barcode_id`) REFERENCES `barcodes`(`id`)',]);
        $this->dbforge->add_column('sectors',['CONSTRAINT `sector_barcode` FOREIGN KEY (`barcode_id`) REFERENCES `barcodes`(`id`)',]);

        // barcodes unique constraint
        $this->dbforge->add_column('barcodes',['CONSTRAINT `unique_barcodes` UNIQUE(barcode)',]);
    }

    /*public function down()
    {
        
    }*/
}