<?php 
  $result = $category->read();

  $num = $result->rowCount();

  if($num > 0) {
    $categories_arr = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      extract($row);

      $category_item = array(
        'id' => $id,
        'category' => $category
      );

      array_push($categories_arr, $category_item);
    }

    echo json_encode($categories_arr);

  } else {
    echo json_encode(
      array('message' => 'No Categories Found!')
    );

  }
