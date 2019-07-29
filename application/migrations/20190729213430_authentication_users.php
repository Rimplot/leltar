<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Migration_Authentication_users extends CI_Migration {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
    }

    public function up()
    {
        // add table 'users'
        $fields = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'role' => array(
                'type' => 'INT',
                'constraint' => 11
            )
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('users', FALSE, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('users');
    }
}