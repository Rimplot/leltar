<?php

class Owners_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function add_owner()
    {
        $data = array(
            'name' => $this->input->post('name')
        );
        $this->db->insert('owners', $data);

        return $this->db->insert_id();
    }

    public function delete_owner($id = null)
    {
        if ($id !== null) {
            // get the ids of the items owned by the selected owner
            $this->db->select('id');
            $this->db->from('items');
            $this->db->where('owner_id', $id);
            $result = $this->db->get()->result_array();

            // set them to owned by nobody
            $update_array = $result;
            
            for ($i = 0; $i < count($update_array); $i++)
                $update_array[$i]['owner_id'] = NULL;

            $this->db->update_batch('items', $update_array, 'id');


            // delete owner
            $this->db->delete('owners', array('id' => $id));
        }
    }

    public function get_owners($id = false)
    {
        $this->db->select('*');
        $this->db->from('owners');

        if ($id === false) {
            $query = $this->db->get();
            $result = $query->result_array();
            for ($i = 0; $i < count($result); $i++) {
                $result[$i]['item_num'] = count($this->get_items_of_owner($result[$i]['id']));
            }
            return $result;
        } else {
            $this->db->where('id = ' . $id);
            $query = $this->db->get();
            return $query->row_array();
        }
    }

    public function get_items_of_owner($id = null) {
        if ($id !== null) {
            $this->db->select('instances.*, items.name AS name');
            $this->db->from('instances');
            $this->db->join('items', 'items.id = instances.item_id');
            $this->db->where('owner_id', $id);
            $query = $this->db->get();

            if ($this->db->error()['code']) {
                die($this->db->error()['code'] . ': ' . $this->db->error()['message']);
            }
            
            return $query->result_array();
        }
    }

    public function set_owner()
    {
        $data = array(
            'name' => $this->input->post('name')
        );

        $this->db->where('id', $this->input->post('id'));
        return $this->db->update('owners', $data);
    }
}
