<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Migration_Created_by extends CI_Migration {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
    }

    public function up()
    {
        $fields = array(
            'created_by' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE,
                'after' => 'barcode'
            )
        );
        $this->dbforge->add_column('instances', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('instances', 'created_by');
    }
}