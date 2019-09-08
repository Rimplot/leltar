<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Migration_Boxes_into_their_own_table extends CI_Migration {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
    }

    public function up()
    {
        $this->db->query('CREATE TABLE `boxes` LIKE `instances`');
        $this->db->query('ALTER TABLE `boxes` ADD `name` VARCHAR(255) NOT NULL;');
        $this->db->query('
            INSERT INTO `boxes`
            SELECT `instances`.*, `items`.`name`
            FROM `instances`
            JOIN `items` ON `items`.`id` = `instances`.`item_id`
            WHERE `items`.`type_id` = 3
        ');
        
        $this->db->query('ALTER TABLE `boxes` MODIFY `name` VARCHAR(255) NOT NULL AFTER `id`');
        $this->db->query('ALTER TABLE `boxes` DROP `item_id`, DROP `date_bought`, DROP `value`, DROP `owner_id`, DROP `stock`');

        $this->db->query('
            DELETE FROM `inventory`
            JOIN `instances` ON `inventory`.`item_id` = `instances`.`id`
            JOIN `items` ON `items`.`id` = `instances`.`item_id`
            WHERE `items`.`type_id` = 3
        ');
        $this->db->query('DELETE FROM `instances` JOIN `items` ON `items`.`id` = `instances`.`item_id` WHERE `items`.`type_id` = 3');
        $this->db->query('DELETE FROM `items` WHERE `type_id` = 3');

        $this->db->query('ALTER TABLE `instances` DROP FOREIGN KEY `items_fk1`');
        $this->db->query('ALTER TABLE `instances` ADD CONSTRAINT `box_id` FOREIGN KEY (box_id) REFERENCES boxes (id)');
    }

    /*public function down()
    {
        
    }*/
}