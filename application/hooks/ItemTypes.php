<?php

class ItemTypes {

    function GetItemTypes() {

        $CI =& get_instance();

        $CI->load->database();
        $rows = $CI->db->query('SELECT * FROM types ORDER BY id')->result_array();

        $item_types = array();
        $item_type_id = array();
        foreach ($rows as $row) {
            $item_types[$row['id']] = $row['name'];
            $item_type_id[$row['name']] = $row['id'];
        }
        define('ITEM_TYPES', $item_types);
        define('ITEM_TYPE_ID', $item_type_id);
        define('BOX_TYPE_ID', $item_type_id['box']);

    }

}