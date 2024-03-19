<?php 
  $id = getID();
  $category->id = $id ? $id : die();

  if(!$category->delete()) {
    echo json_encode(array('message' => 'Category Not Deleted'));
  }
