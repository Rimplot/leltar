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
            'parent' => $this->input->post('parent')
        );

        $this->db->insert('category', $data);

        return $this->db->insert_id();
    }

    public function delete_category($id = null)
    {
        if ($id !== null) {
            $data['id'] = $id;
            $this->db->delete('category', $data);
        }
    }

    public function get_categories($id = false)
    {
        $this->db->select('c1.id AS id, c1.name AS name, c2.name AS parent, c2.id AS parent_id');
        $this->db->from('category c1');
        $this->db->join('category c2', 'c1.parent = c2.id');

        if ($id === false) {
            $this->db->where('c1.id <> 0');
            $this->db->order_by('id');
            $query = $this->db->get();
            $result = $query->result_array();

            for ($i = 0; $i < count($result); $i++) {
                $result[$i]['item_num'] = count($this->get_items_in_category($result[$i]['id']));
            }

            return $result;

            return $query->result_array();
        } else {
            $this->db->where('c1.id = ' . $id);
            $query = $this->db->get();
            $result = $query->row_array();
            $result['item_num'] = count($this->get_items_in_category($id));

            if ($this->db->error()['code'] || !$query->num_rows()) {
                show_404();
            }
            
            return $result;
        }
    }

    public function get_items_in_category($id = null) {
        if ($id !== null) {
            $this->db->select('*');
            $this->db->from('items');
            $this->db->where('category_id', $id);
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
            'id' => $this->input->post('id'),
            'name' => $this->input->post('name'),
            'parent' => $this->input->post('parent')
        );

        return $this->db->replace('category', $data);
    }
}
