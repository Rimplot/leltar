<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Migration_Redesign_database_structure extends CI_Migration {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
    }

    public function up()
    {

        // add table 'types'

        $fields = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => TRUE
            )
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('types', FALSE, $attributes);


        // fill table with default data

        $data = array(
            array('name' => 'asset'),
            array('name' => 'stock'),
            array('name' => 'box'),
            array('name' => 'other')
        );
        $this->db->insert_batch('types', $data);


        // add quantity column to 'inventory' table

        $fields = array(
            'quantity' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE,
                'after' => 'sector_id'
            )
        );
        $this->dbforge->add_column('inventory', $fields);


        // modify column order, set 'category_id' and 'box_id' to NULL in 'items' table

        $fields = array(
            'type' => array(
                'name' => 'type_id',
                'type' => 'INT',
                'constraint' => 11
            ),
            'category_id' => array(
                'null' => TRUE,
                'after' => 'box_id',
                'type' => 'INT',
                'constraint' => 11
            ),
            'box_id' => array(
                'null' => TRUE,
                'type' => 'INT',
                'constraint' => 11
            )
        );
        $this->dbforge->modify_column('items', $fields);


        // set parent category as nullable

        $fields = array(
            'parent' => array(
                'null' => TRUE,
                'type' => 'INT',
                'constraint' => 11
            )
        );
        $this->dbforge->modify_column('categories', $fields);


        // drop 'boxes' table

        $this->dbforge->drop_table('boxes', TRUE);

        $this->dbforge->add_column('items',['CONSTRAINT `items_fk0` FOREIGN KEY (`type`) REFERENCES `types`(`id`)',]);
        $this->dbforge->add_column('items',['CONSTRAINT `items_fk1` FOREIGN KEY (`box_id`) REFERENCES `items`(`id`)',]);
        $this->dbforge->add_column('items',['CONSTRAINT `items_fk2` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`)',]);
        $this->dbforge->add_column('categories',['CONSTRAINT `categories_fk0` FOREIGN KEY (`parent`) REFERENCES `categories`(`id`)',]);
        $this->dbforge->add_column('inventory',['CONSTRAINT `inventory_fk0` FOREIGN KEY (`item_id`) REFERENCES `items`(`id`)',]);
        $this->dbforge->add_column('inventory',['CONSTRAINT `inventory_fk1` FOREIGN KEY (`sector_id`) REFERENCES `sectors`(`id`)',]);
        $this->dbforge->add_column('sectors',['CONSTRAINT `sectors_fk0` FOREIGN KEY (`storage_id`) REFERENCES `storages`(`id`)',]);

    }

    public function down()
    {
        $this->db->query('ALTER TABLE `items` DROP FOREIGN KEY `items_fk0`');
        $this->db->query('ALTER TABLE `items` DROP FOREIGN KEY `items_fk1`');
        $this->db->query('ALTER TABLE `items` DROP FOREIGN KEY `items_fk2`');
        $this->db->query('ALTER TABLE `categories` DROP FOREIGN KEY `categories_fk0`');
        $this->db->query('ALTER TABLE `inventory` DROP FOREIGN KEY `inventory_fk0`');
        $this->db->query('ALTER TABLE `inventory` DROP FOREIGN KEY `inventory_fk1`');
        $this->db->query('ALTER TABLE `sectors` DROP FOREIGN KEY `sectors_fk0`');

        $this->dbforge->drop_table('types', TRUE);
        $this->dbforge->drop_column('inventory', 'quantity');


        // recreate boxes

        $fields = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => 255
            ),
            'barcode' => array(
                'type' => 'VARCHAR',
                'constraint' => 32,
                'null' => TRUE
            ),
            'parent' => array(
                'type' => 'INT',
                'constraint' => 11
            )
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('parent');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('boxes', FALSE, $attributes);


        // reset type_id
        
        $fields = array(
            'type_id' => array(
                'name' => 'type',
                'type' => 'INT',
                'constraint' => 11
            )
        );
        $this->dbforge->modify_column('items', $fields);
    }
}