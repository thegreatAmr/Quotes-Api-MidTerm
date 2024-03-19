<?php 
    $id = getID();

    $category->id = $id ? $id : die();

    $category->read_single();

    if (!is_null($category->id) and !is_null($category->category)) {
        $category_arr = array(
            'id' => $category->id,
            'category' => $category->category
        );

        print_r(json_encode($category_arr));            
    }
