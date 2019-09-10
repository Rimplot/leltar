<?php

class Boxes_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->load->model('items_model');
    }

    public function add_box()
    {
        $data = array(
            'barcode' => ($this->input->post('barcode') == 0) ? NULL : $this->input->post('barcode'),
            'type' => BARCODE_TYPE_ID['box']
        );
        $this->db->insert('barcodes', $data);
        $barcode_id = $this->db->insert_id();

        $data = array(
            'name' => $this->input->post('name'),
            'barcode_id' => $barcode_id,
            'box_id' => ($this->input->post('parent') == 0) ? NULL : $this->input->post('parent'),
            'created_by' => $this->session->user['id'],
        );
        $this->db->insert('boxes', $data);
        
        return $this->db->insert_id();
    }

    public function delete_box($id = null)
    {
        if ($id !== null) {
            // get the id of the parent box
            $this->db->select('box_id');
            $this->db->from('boxes');
            $this->db->where('id', $id);
            $parent = $this->db->get()->row_array()['box_id'];

            // delete box
            $this->db->query("DELETE `boxes`, `barcodes` FROM `boxes` INNER JOIN `barcodes` ON `boxes`.`barcode_id` = `barcodes`.`id` WHERE `boxes`.`id` = $id");

            // put the items and the other boxes in the deleted box into the parent box
            $this->set_children('instances', $id, $parent);
            $this->set_children('boxes', $id, $parent);
        }
    }

    private function set_children($table, $box, $parent) {
        $this->db
            ->select('id')
            ->from($table)
            ->where('box_id', $box);
        $update_array = $this->db->get()->result_array();
        
        for ($i = 0; $i < count($update_array); $i++)
            $update_array[$i]['box_id'] = $parent;

        $this->db->update_batch($table, $update_array, 'id');
    }

    public function get_boxes($id = false)
    {
        $this->db->select('b1.*, b2.name AS parent, b2.id AS parent_id, barcodes.barcode');
        $this->db->from('boxes b1');
        $this->db->join('barcodes', 'barcodes.id = b1.barcode_id');
        $this->db->join('boxes b2', 'b2.id = b1.box_id', 'LEFT');
        $this->db->group_by('b1.id');

        if ($id === false) {
            $this->db->where('b1.id <> 0');
            $query = $this->db->get();
            $result = $query->result_array();
            for ($i = 0; $i < count($result); $i++) {
                $result[$i]['item_num'] = count($this->get_items_in_box($result[$i]['id']));
            }
            return $result;
        } else {
            $this->db->where('b1.id = ' . $id);
            $query = $this->db->get();
            $result = $query->row_array();

            $result['item_num'] = count($this->get_items_in_box($id));
            // $result['last_seen'] = $this->items_model->get_last_seen($id);

            return $result;
        }
    }

    public function get_items_in_box($id = null) {
        if ($id !== null) {
            $this->db->select('instances.*, items.category_id, items.type_id, items.name AS name, barcodes.barcode');
            $this->db->from('instances');
            $this->db->join('items', 'items.id = instances.item_id');
            $this->db->join('barcodes', 'barcodes.id = instances.barcode_id');
            $this->db->where('box_id', $id);
            $query = $this->db->get();

            if ($this->db->error()['code']) {
                die($this->db->error()['code'] . ': ' . $this->db->error()['message']);
            }
            
            return $query->result_array();
        }
    }

    public function set_box()
    {
        $data = array(
            'name' => $this->input->post('name'),
            //'barcode' => ($this->input->post('barcode') == 0) ? NULL : $this->input->post('barcode'),
            'box_id' => ($this->input->post('parent') == 0) ? NULL : $this->input->post('parent')
        );
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('boxes', $data);
    }
}
