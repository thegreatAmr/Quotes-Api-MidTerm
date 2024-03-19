<?php 
  $id = getID();
  $quote->id = $id ? $id : die();

  if(!$quote->delete()) {
    echo json_encode(array('message' => 'Quote Not Deleted'));
  }
