<?php
  class Category {
    private $cn;
    private $table = 'categories';

    public $id;
    public $category;

    public function __construct($db) {
      $this->cn = $db;
    }

    public function read() {
      $query = 'SELECT id, category
                FROM ' . $this->table . '
                ORDER BY category ASC';

      $stmt = $this->cn->prepare($query);

      $stmt->execute();

      return $stmt;
    }


    public function read_single(){
        $query = 'SELECT id, category
                FROM ' . $this->table . '
                WHERE id = :id
                LIMIT 1';

        $stmt = $this->cn->prepare($query);

        $stmt->bindParam(':id', $this->id);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
          $this->id = $row['id'];
          $this->category = $row['category'];  
        }
        else {
          $this->id = null;
          $this->category = null;
          echo json_encode(array("message" => "category_id Not Found"));
        }
        
    }

    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (category) VALUES (:category) RETURNING id';

        $stmt = $this->cn->prepare($query);

        $this->category = htmlspecialchars(strip_tags($this->category));

        $stmt-> bindParam(':category', $this->category);

        if($stmt->execute()) {
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          if ($row) {
            $category_ary = array(
              'id' => $row['id'],
              'category' => $this->category,
            );
            print_r(json_encode($category_ary));
          }
          return true;
        }

        printf("Error: $s.\n", $stmt->error);

        return false;
    }

    public function update() {
        $query = 'UPDATE ' . $this->table . '
                  SET category = :category
                  WHERE id = :id';

        $stmt = $this->cn->prepare($query);

        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt-> bindParam(':category', $this->category);
        $stmt-> bindParam(':id', $this->id);

        if($stmt->execute()) {
            $category_ary = array(
              'id' => $this->id,
              'category' => $this->category,
            );
            print_r(json_encode($category_ary));

            return true;
        }

        printf("Error: $s.\n", $stmt->error);

        return false;
    }

    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        $stmt = $this->cn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt-> bindParam(':id', $this->id);

        if($stmt->execute()) {
          $category_ary = array(
            'id' => $this->id
          );
          print_r(json_encode($category_ary));

        return true;
        }

        printf("Error: $s.\n", $stmt->error);

        return false;
    }

  }
