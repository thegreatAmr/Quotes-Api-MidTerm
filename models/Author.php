<?php
  class Author {
    private $cn;
    private $table = 'authors';

    public $id;
    public $author;

    public function __construct($db) {
      $this->cn = $db;
    }

    public function read() {
      $query = 'SELECT id, author
                FROM ' . $this->table . ' 
                ORDER BY author ASC';

      $stmt = $this->cn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }

    public function read_single(){
        $query = 'SELECT id, author
                FROM ' . $this->table . '
                WHERE id = :id
                LIMIT 1';

        $stmt = $this->cn->prepare($query);

        $stmt->bindParam(':id', $this->id);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
          $this->id = $row['id'];
          $this->author = $row['author'];  
        }
        else {
          $this->id = null;
          $this->author = null;
          echo json_encode(array('message' => 'author_id Not Found'));
        }
    }

    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (author) VALUES (:author) RETURNING id';

        $stmt = $this->cn->prepare($query);

        $this->author = htmlspecialchars(strip_tags($this->author));

        $stmt-> bindParam(':author', $this->author);

        if($stmt->execute()) {
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          if ($row) {
            $author_ary = array(
              'id' => $row['id'],
              'author' => $this->author,
            );
            print_r(json_encode($author_ary));
          }
          return true;
        }

        printf("Error: $s.\n", $stmt->error);

        return false;
    }


    public function update() {
        $query = 'UPDATE ' . $this->table . '
                  SET author = :author
                  WHERE id = :id';

        $stmt = $this->cn->prepare($query);

        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt-> bindParam(':author', $this->author);
        $stmt-> bindParam(':id', $this->id);

        if($stmt->execute()) {
            $author_ary = array(
              'id' => $this->id,
              'author' => $this->author,
            );
            print_r(json_encode($author_ary));

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
          $author_ary = array(
            'id' => $this->id
          );
          print_r(json_encode($author_ary));

        return true;
        }

        printf("Error: $s.\n", $stmt->error);

        return false;
    }

  }
