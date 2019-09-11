<?php

class ItemTypes {

    function GetItemTypes() {

        define('ITEM_TYPE_ID', array(
            'asset' => 1,
            'stock' => 2,
            'other' => 3
        ));

        $item_types = array();
        foreach (ITEM_TYPE_ID as $type => $id) {
            $item_types[$id] = $type;
        }
        define('ITEM_TYPES', $item_types);

        define('BARCODE_TYPE_ID', array(
            'item' => 1,
            'box' => 2,
            'sector' => 3
        ));
        
        $barcode_types = array();
        foreach (BARCODE_TYPE_ID as $type => $id) {
            $barcode_types[$id] = $type;
        }
        define('BARCODE_TYPE', $barcode_types);

    }

}