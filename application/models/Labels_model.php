<?php

class Labels_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function add_label()
    {
        $data = array(
            'name' => $this->input->post('name'),
            'content' => $this->input->post('content')
        );

        $this->db->insert('labels', $data);

        return $this->db->insert_id();
    }

    public function delete_label($id = null)
    {
        if ($id !== null) {
            // get the id of the parent category
            /*$this->db->select('parent');
            $this->db->from('categories');
            $this->db->where('id', $id);
            $parent_id = $this->db->get()->row_array()['parent'];*/

            // get the ids of the categories with the deleted label
            $this->db->select('id');
            $this->db->from('categories');
            $this->db->where('label_id', $id);
            $result = $this->db->get()->result_array();

            // set them to NULL
            $update_array = $result;
            
            for ($i = 0; $i < count($update_array); $i++)
                $update_array[$i]['label_id'] = NULL;

            $this->db->update_batch('categories', $update_array, 'id');

            // delete label
            $this->db->delete('labels', array('id' => $id));
        }
    }

    public function get_labels($id = false)
    {
        $this->db->select('id, name, content');
        $this->db->from('labels');

        if ($id === false) {
            $query = $this->db->get();
            return $query->result_array();
        } else {
            $this->db->where('id = ' . $id);
            $query = $this->db->get();
            return $query->row_array();
        }
    }

    public function get_categories_with_label($id = null) {
        if ($id !== null) {
            $this->db->select('*');
            $this->db->from('categories');
            $this->db->where('label_id', $id);
            $query = $this->db->get();

            if ($this->db->error()['code']) {
                die($this->db->error()['code'] . ': ' . $this->db->error()['message']);
            }
            
            return $query->result_array();
        }
    }

    public function set_label()
    {
        $data = array(
            'name' => $this->input->post('name'),
            'content' => $this->input->post('content')
        );

        $this->db->where('id', $this->input->post('id'));
        return $this->db->update('labels', $data);
    }
}
