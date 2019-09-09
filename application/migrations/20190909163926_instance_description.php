<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Migration_Instance_description extends CI_Migration {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
    }

    public function up()
    {
        $fields = array(
            'desc' => array(
                'type' => 'VARCHAR',
                'constraint' => 2047,
                'null' => TRUE,
                'after' => 'barcode'
            )
        );
        $this->dbforge->add_column('instances', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('instances', 'desc');
    }
}