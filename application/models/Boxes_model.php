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
            'type_id' => BOX_TYPE_ID
        );
        $this->db->insert('items', $data);

        $data = array(
            'item_id' => $this->db->insert_id(),
            'barcode' => ($this->input->post('barcode') == 0) ? NULL : $this->input->post('barcode'),
            'box_id' => ($this->input->post('parent') == 0) ? NULL : $this->input->post('parent'),
        );
        $this->db->insert('instances', $data);
        
        return $this->db->insert_id();
    }

    public function delete_box($id = null)
    {
        if ($id !== null) {
            // get the id of the parent box
            $this->db->select('item_id, box_id');
            $this->db->from('instances');
            $this->db->where('id', $id);
            $box = $this->db->get()->row_array();

            // delete box
            $this->db->delete('instances', array('id' => $id));
            $this->db->delete('items', array('id' => $box['item_id']));

            // get the ids of the items in the deleted box
            $this->db->select('id');
            $this->db->from('instances');
            $this->db->where('box_id', $id);
            $result = $this->db->get()->result_array();

            // put them into the parent box
            $update_array = $result;
            
            for ($i = 0; $i < count($update_array); $i++)
                $update_array[$i]['box_id'] = $box['box_id'];

            $this->db->update_batch('instances', $update_array, 'id');
        }
    }

    public function get_boxes($id = false)
    {
        $this->db->select('b1.name, i1.*, b2.name AS parent, i2.id AS parent_id');
        $this->db->from('items b1');
        $this->db->join('instances i1', 'i1.item_id = b1.id');
        $this->db->join('instances i2', 'i1.box_id = i2.id', 'LEFT');
        $this->db->join('items b2', 'i2.item_id = b2.id', 'LEFT');
        $this->db->where('b1.type_id = ' . BOX_TYPE_ID);
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
            $this->db->where('i1.id = ' . $id);
            $query = $this->db->get();
            $result = $query->row_array();

            $result['item_num'] = count($this->get_items_in_box($result['id']));
            $result['last_seen'] = $this->items_model->get_last_seen($id);

            return $result;
        }
    }

    public function get_items_in_box($id = null) {
        if ($id !== null) {
            $this->db->select('instances.*, items.category_id, items.type_id, items.name AS name');
            $this->db->from('instances');
            $this->db->join('items', 'items.id = instances.item_id');
            $this->db->where('box_id', $id);
            $this->db->where('items.type_id <> ' . BOX_TYPE_ID);
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
            'barcode' => ($this->input->post('barcode') == 0) ? NULL : $this->input->post('barcode'),
            'box_id' => ($this->input->post('parent') == 0) ? NULL : $this->input->post('parent')
        );
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('instances', $data);

        $data = array(
            'name' => $this->input->post('name')
        );
        $this->db->where('id', $this->input->post('item_id'));
        $this->db->update('items', $data);
    }
}
