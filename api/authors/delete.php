<?php 
  $id = getID();
  $author->id = $id ? $id : die();

  if(!$author->delete()) {
      echo json_encode(array('message' => 'Author Not Deleted'));
  }
