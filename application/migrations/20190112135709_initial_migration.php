<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Migration_Initial_migration extends CI_Migration {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
    }

    public function up()
    {

        // boxes

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
            'barcode' => array(
                'type' => 'VARCHAR',
                'constraint' => 32,
                'null' => TRUE
            ),
            'parent' => array(
                'type' => 'INT',
                'constraint' => 11
            )
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('parent');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('boxes', FALSE, $attributes);


        // categories

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
            'parent' => array(
                'type' => 'INT',
                'constraint' => 11
            )
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('parent');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('categories', FALSE, $attributes);


        // inventory

        $fields = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'item_id' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'sector_id' => array(
                'type' => 'INT',
                'constraint' => 11
            )
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_field('time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_field(array(
            'latest' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1
            )
        ));

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('item_id');
        $this->dbforge->add_key('sector_id');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('inventory', FALSE, $attributes);


        // items

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
            'barcode' => array(
                'type' => 'VARCHAR',
                'constraint' => 32,
                'null' => TRUE,
                'unique' => TRUE
            ),
            'category_id' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'type' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'box_id' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('category_id');
        $this->dbforge->add_key('box_id');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('items', FALSE, $attributes);


        // sectors

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
            'barcode' => array(
                'type' => 'VARCHAR',
                'constraint' => 32,
                'null' => TRUE,
                'unique' => TRUE
            ),
            'storage_id' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'archived' => array(
                'type' => 'TINYINT',
                'constraint' => 1
            )
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('storage_id');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('sectors', FALSE, $attributes);


        // storages

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
            'address' => array(
                'type' => 'VARCHAR',
                'constraint' => 255
            ),
            'archived' => array(
                'type' => 'TINYINT',
                'constraint' => 1
            )
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('storages', FALSE, $attributes);

    }

    public function down()
    {
        $this->dbforge->drop_table('boxes', TRUE);
        $this->dbforge->drop_table('categories', TRUE);
        $this->dbforge->drop_table('inventory', TRUE);
        $this->dbforge->drop_table('items', TRUE);
        $this->dbforge->drop_table('sectors', TRUE);
        $this->dbforge->drop_table('storages', TRUE);
    }
}