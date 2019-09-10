<?php

class Categories_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function add_category()
    {
        $data = array(
            'name' => $this->input->post('name'),
            'parent' => ($this->input->post('parent') == 0) ? NULL : $this->input->post('parent'),
            'label_id' => ($this->input->post('label') == 0) ? NULL : $this->input->post('label')
        );

        $this->db->insert('categories', $data);

        return $this->db->insert_id();
    }

    public function delete_category($id = null)
    {
        if ($id !== null) {
            // get the id of the parent category
            $this->db->select('parent');
            $this->db->from('categories');
            $this->db->where('id', $id);
            $parent_id = $this->db->get()->row_array()['parent'];

            // delete category
            $this->db->delete('categories', array('id' => $id));

            // get the ids of the items in the deleted category
            $this->db->select('id');
            $this->db->from('items');
            $this->db->where('category_id', $id);
            $result = $this->db->get()->result_array();

            // put them into the parent category
            $update_array = $result;
            
            for ($i = 0; $i < count($update_array); $i++)
                $update_array[$i]['category_id'] = $parent_id;

            $this->db->update_batch('items', $update_array, 'id');
        }
    }

    public function get_categories($id = false)
    {
        $this->db->select('c1.id, c1.name, c1.label_id, c2.name AS parent, c2.id AS parent_id, COUNT(items.name) AS item_num, labels.name AS label');
        $this->db->from('categories c1');
        $this->db->join('items', 'items.category_id = c1.id', 'LEFT');
        $this->db->join('categories c2', 'c1.parent = c2.id', 'LEFT');
        $this->db->join('labels', 'labels.id = c1.label_id', 'LEFT');
        $this->db->group_by('c1.id');

        if ($id === false) {
            $this->db->where('c1.id <> 0');
            $query = $this->db->get();
            return $query->result_array();
        } else {
            $this->db->where('c1.id = ' . $id);
            $query = $this->db->get();
            return $query->row_array();
        }
    }

    public function get_items_in_category($id = null) {
        if ($id !== null) {
            $this->db->select('items.*, instances.*, barcodes.barcode');
            $this->db->from('items');
            $this->db->join('instances', 'instances.item_id = items.id');
            $this->db->join('barcodes', 'barcodes.id = instances.barcode_id');
            $this->db->where('items.category_id', $id);
            $query = $this->db->get();

            if ($this->db->error()['code']) {
                die($this->db->error()['code'] . ': ' . $this->db->error()['message']);
            }
            
            return $query->result_array();
        }
    }

    public function set_category()
    {
        $data = array(
            'name' => $this->input->post('name'),
            'parent' => ($this->input->post('parent') == 0) ? NULL : $this->input->post('parent'),
            'label_id' => ($this->input->post('label') == 0) ? NULL : $this->input->post('label')
        );

        $this->db->where('id', $this->input->post('id'));
        return $this->db->update('categories', $data);
    }
}
