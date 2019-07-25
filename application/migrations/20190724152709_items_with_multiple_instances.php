<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Migration_Items_with_multiple_instances extends CI_Migration {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
    }

    public function up()
    {
        $this->dbforge->rename_table('items', 'instances');

        $this->db->query('CREATE TABLE items LIKE instances');
        $this->db->query('INSERT items SELECT * FROM instances GROUP BY name');

        $this->db->query('ALTER TABLE items DROP id');
        $this->db->query('ALTER TABLE items ADD id INT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (id)');

        $fields = array(
            'item_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE,
                'after' => 'id'
            )
        );
        $this->dbforge->add_column('instances', $fields);

        $this->db->query('
            UPDATE instances
            INNER JOIN items ON instances.name = items.name
            SET instances.item_id = items.id
        ');

        $this->dbforge->drop_column('instances', 'name');
        $this->dbforge->drop_column('instances', 'type_id');
        $this->db->query('ALTER TABLE `instances` DROP FOREIGN KEY `items_fk2`');
        $this->dbforge->drop_column('instances', 'category_id');

        $this->dbforge->drop_column('items', 'barcode');
        $this->dbforge->drop_column('items', 'date_created');
        $this->dbforge->drop_column('items', 'date_bought');
        $this->dbforge->drop_column('items', 'value');
        $this->dbforge->drop_column('items', 'box_id');
        $this->dbforge->drop_column('items', 'owner_id');
        $this->dbforge->drop_column('items', 'stock');
    }

    /*public function down()
    {
        
    }*/
}