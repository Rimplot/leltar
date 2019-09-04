<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Migration_Last_modified extends CI_Migration {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
    }

    public function up()
    {
        $fields = array(
            'last_modified_by' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE,
                'after' => 'date_created'
            ),
            'last_modified_date' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
                'after' => 'last_modified_by'
            )
        );
        $this->dbforge->add_column('instances', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('instances', 'last_modified_by');
        $this->dbforge->drop_column('instances', 'last_modified_date');
    }
}