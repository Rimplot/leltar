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
            'name' => $this->input->post('name'),
            'barcode' => ($this->input->post('barcode') == 0) ? NULL : $this->input->post('barcode'),
            'box_id' => ($this->input->post('parent') == 0) ? NULL : $this->input->post('parent'),
            'type_id' => BOX_TYPE_ID
        );

        $this->db->insert('items', $data);

        return $this->db->insert_id();
    }

    public function delete_box($id = null)
    {
        if ($id !== null) {
            // get the id of the parent box
            $this->db->select('box_id');
            $this->db->from('items');
            $this->db->where('id', $id);
            $parent_id = $this->db->get()->row_array()['box_id'];

            // delete box
            $this->db->delete('items', array('id' => $id));

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
        $this->db->select('b1.id, b1.name, b1.barcode AS barcode, b2.name AS parent, b2.id AS parent_id');
        $this->db->from('items b1');
        $this->db->where('b1.type_id = ' . BOX_TYPE_ID);
        $this->db->join('items b2', 'b1.box_id = b2.id', 'LEFT');
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

            $result['item_num'] = count($this->get_items_in_box($result['id']));
            $result['last_seen'] = $this->items_model->get_last_seen($id);

            return $result;
        }
    }

    public function get_items_in_box($id = null) {
        if ($id !== null) {
            $this->db->select('*');
            $this->db->from('items');
            $this->db->where('box_id', $id);
            $this->db->where('type_id <> ' . BOX_TYPE_ID);
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
            'barcode' => ($this->input->post('barcode') == 0) ? NULL : $this->input->post('barcode'),
            'box_id' => ($this->input->post('parent') == 0) ? NULL : $this->input->post('parent')
        );

        $this->db->where('id', $this->input->post('id'));
        return $this->db->update('items', $data);
    }
}
