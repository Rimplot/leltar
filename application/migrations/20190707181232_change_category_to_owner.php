<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Migration_Change_category_to_owner extends CI_Migration {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
    }

    public function up()
    {
        // add table 'owners'
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
        $this->dbforge->add_key('id', TRUE);

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('owners', FALSE, $attributes);

        // add column 'owner_id' to table 'items'
        $fields = array(
            'owner_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE,
                'after' => 'category_id'
            )
        );
        $this->dbforge->add_column('items', $fields);

        $this->dbforge->add_column('items',['CONSTRAINT `owner_id` FOREIGN KEY (`owner_id`) REFERENCES `owners`(`id`)',]);
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `items` DROP FOREIGN KEY `owner_id`');
        $this->dbforge->drop_column('items', 'owner_id');
        $this->dbforge->drop_table('owners', TRUE);
    }
}