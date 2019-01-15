<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Migration_Add_inventory_sessions extends CI_Migration {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
    }

    public function up()
    {

        // add table 'sessions'

        $fields = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => 255
            )
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_field('start DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $fields = array(
            'end' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            )
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('sessions', FALSE, $attributes);


        // add session_id column to 'inventory' table

        $fields = array(
            'session_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE,
                'after' => 'id'
            )
        );
        $this->dbforge->add_column('inventory', $fields);

        $this->dbforge->add_column('inventory',['CONSTRAINT `inventory_fk2` FOREIGN KEY (`session_id`) REFERENCES `sessions`(`id`)',]);

    }

    public function down()
    {
        $this->db->query('ALTER TABLE `inventory` DROP FOREIGN KEY `inventory_fk2`');
        $this->dbforge->drop_column('inventory', 'session_id');
        $this->dbforge->drop_table('sessions', TRUE);

    }
}