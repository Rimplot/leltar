<?php

class Boxes_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function add_box()
    {
        $data = array(
            'name' => $this->input->post('name'),
            'parent' => $this->input->post('parent')
        );

        $this->db->insert('boxes', $data);

        return $this->db->insert_id();
    }

    public function delete_box($id = null)
    {
        if ($id !== null) {
            // get the id of the parent box
            $this->db->select('parent');
            $this->db->from('boxes');
            $this->db->where('id', $id);
            $parent_id = $this->db->get()->row_array()['parent'];

            // delete box
            $this->db->delete('boxes', array('id' => $id));

            // get the ids of the items in the deleted box
            $this->db->select('id');
            $this->db->from('items');
            $this->db->where('box_id', $id);
            $result = $this->db->get()->result_array();

            // put them into the parent box
            $update_array = $result;
            
            for ($i = 0; $i < count($update_array); $i++)
                $update_array[$i]['box_id'] = $parent_id;

            $this->db->update_batch('items', $update_array, 'id');
        }
    }

    public function get_boxes($id = false)
    {
        $this->db->select('b1.id, b1.name, b2.name AS parent, b2.id AS parent_id, COUNT(items.name) AS item_num');
        $this->db->from('boxes b1');
        $this->db->join('items', 'items.box_id = b1.id', 'LEFT');
        $this->db->join('boxes b2', 'b1.parent = b2.id', 'LEFT');
        $this->db->group_by('b1.id');

        if ($id === false) {
            $this->db->where('b1.id <> 0');
            $query = $this->db->get();
            return $query->result_array();
        } else {
            $this->db->where('b1.id = ' . $id);
            $query = $this->db->get();
            return $query->row_array();
        }
    }

    public function get_items_in_box($id = null) {
        if ($id !== null) {
            $this->db->select('*');
            $this->db->from('items');
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
            'parent' => $this->input->post('parent')
        );

        $this->db->where('id', $this->input->post('id'));
        return $this->db->update('boxes', $data);
    }
}
