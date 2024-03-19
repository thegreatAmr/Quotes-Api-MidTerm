<?php 
    $id = getID();
    $author->id = $id ? $id : die();

    $author->read_single();

    if (!is_null($author->id) and !is_null($author->author)) {
        $author_arr = array(
            'id' => $author->id,
            'author' => $author->author
        );

        print_r(json_encode($author_arr));            
    }
