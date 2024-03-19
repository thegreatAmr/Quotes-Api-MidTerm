<?php 
  $data = json_decode(file_get_contents("php://input"));

  $category->id = $data->id;
  $category->category = $data->category;

  if(!$category->update()) {
    echo json_encode(array('message' => 'Category Not Updated'));
  }
