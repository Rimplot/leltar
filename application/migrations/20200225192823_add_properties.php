<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Migration_Add_properties extends CI_Migration {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
    }

    public function up()
    {
        $fields = array(
            'properties' => array(
                'type' => 'JSON'
            )
        );
        $this->dbforge->add_column('instances', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('instances', 'properties');
    }
}