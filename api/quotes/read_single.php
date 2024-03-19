<?php 
    $id = getID();
    $quote->id = $id ? $id : die();

    $quote->read_single();

    if (!is_null($quote->id) and !is_null($quote->quote)) {
        $quote_arr = array(
            'id' => $quote->id,
            'quote' => $quote->quote,
            'author' => $quote->author_name,
            'category' => $quote->category_name
        );

        print_r(json_encode($quote_arr));
    }    
