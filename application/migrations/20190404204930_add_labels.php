<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Migration_Add_labels extends CI_Migration {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
    }

    public function up()
    {

        // add table 'labels'

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
            'content' => array(
                'type' => 'VARCHAR',
                'constraint' => 4095
            )
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('labels', FALSE, $attributes);


        // add label_id column to 'categories' table

        $fields = array(
            'label_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE
            )
        );
        $this->dbforge->add_column('categories', $fields);

        $this->dbforge->add_column('categories',['CONSTRAINT `label` FOREIGN KEY (`label_id`) REFERENCES `labels`(`id`)',]);

    }

    public function down()
    {
        $this->db->query('ALTER TABLE `categories` DROP FOREIGN KEY `label`');
        $this->dbforge->drop_column('categories', 'label_id');
        $this->dbforge->drop_table('labels', TRUE);
    }
}