<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Migration_Stock_in_items_table_temporary extends CI_Migration {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
    }

    public function up()
    {
        // add stock column to 'categories' table

        $fields = array(
            'stock' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE
            )
        );
        $this->dbforge->add_column('items', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('items', 'stock');
    }
}