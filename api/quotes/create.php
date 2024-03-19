<?php 
    $data = json_decode(file_get_contents("php://input"));

    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    if(!$quote->create()) {
        echo json_encode(array('message' => 'Quote Not Created'));
    }

