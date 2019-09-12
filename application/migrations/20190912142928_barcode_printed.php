<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Migration_Barcode_printed extends CI_Migration {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
    }

    public function up()
    {
        $fields = array(
            'printed' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            )
        );
        $this->dbforge->add_column('barcodes', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('barcodes', 'printed');
    }
}