<?php

class Sessions_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function start_session($name) {
        $data = array(
            'name' => $name
        );
        $this->db->insert('sessions', $data);

        return $this->db->insert_id();
    }

    public function stop_session($id = null) {
        if ($id !== null) {
            $this->db->where('id', $id);
            return $this->db->update('sessions', ['end' => date('Y-m-d H:i:s')]);
        }
    }

    public function get_sessions($id = null) {
        $this->db->select('*');
        $this->db->from('sessions');
        $this->db->where('end', null);

        if ($id === null) {
            $query = $this->db->get();
            return $query->result_array();
        } else {
            $this->db->where('id', $id);
            return $this->db->get()->row_array();
        }
    }
}
