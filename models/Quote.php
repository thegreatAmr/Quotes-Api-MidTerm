<?php
  class Quote {
    private $cn;
    private $table = 'quotes';

    public $id;
    public $quote;
    public $author_id;
    public $author_name;
    public $category_id;
    public $category_name;
    
    public function __construct($db) {
        $this->cn = $db;
    }

    public function read() {
      $query = "SELECT
                  c.category as category_name,
                  a.author as author_name,
                  q.id, q.quote, q.author_id, q.category_id
                FROM " . $this->table . " q
                  LEFT JOIN categories c ON q.category_id = c.id
                  LEFT JOIN authors a ON q.author_id = a.id
                ORDER BY id ASC";

        $stmt = $this->cn->prepare($query);

        $stmt->execute();

      return $stmt;
    }

    public function read_single(){
        $query = 'SELECT
                    c.category as category_name,
                    a.author as author_name,
                    q.id, q.quote, q.author_id, q.category_id
                  FROM ' . $this->table . ' q
                    LEFT JOIN categories c ON q.category_id = c.id
                    LEFT JOIN authors a ON q.author_id = a.id
                  WHERE q.id = :id
                  LIMIT 1';

        $stmt = $this->cn->prepare($query);

        $stmt->bindParam(':id', $this->id);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
          $this->id = $row['id'];
          $this->quote = $row['quote'];
          $this->author_id = $row['author_id'];
          $this->author_name = $row['author_name'];
          $this->category_id = $row['category_id'];
          $this->category_name = $row['category_name'];  
        }
        else {
          $this->id = null;
          $this->quote = null;
          $this->author_id = null;
          $this->author_name = null;
          $this->category_id = null;
          $this->category_name = null;
          echo json_encode(array("message" => "No Quotes Found"));          
        }        
    }

    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (quote, author_id, category_id) 
                  VALUES (:quote, :author_id, :category_id) RETURNING id';

        $stmt = $this->cn->prepare($query);

        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        $stmt-> bindParam(':quote', $this->quote);
        $stmt-> bindParam(':author_id', $this->author_id);
        $stmt-> bindParam(':category_id', $this->category_id);

        if($stmt->execute()) {
          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          if ($row) {
            $quote_ary = array(
              'id' => $row['id'],
              'quote' => $this->quote,
              'author_id' => $this->author_id,
              'category_id' => $this->category_id
            );
            print_r(json_encode($quote_ary));
          }

          return true;
        }

        printf("Error: $s.\n", $stmt->error);

        return false;
    }


    public function update() {
        $query = 'UPDATE ' . $this->table . '
                  SET
                    quote = :quote,
                    author_id = :author_id,
                    category_id = :category_id
                  WHERE id = :id
                  RETURNING id';

        $stmt = $this->cn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        $stmt-> bindParam(':id', $this->id);
        $stmt-> bindParam(':quote', $this->quote);
        $stmt-> bindParam(':author_id', $this->author_id);
        $stmt-> bindParam(':category_id', $this->category_id);

        if($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $quote_ary = array(
                  'id' => $this->id,
                  'quote' => $this->quote,
                  'author_id' => $this->author_id,
                  'category_id' => $this->category_id
                );
                print_r(json_encode($quote_ary));
              }
              else {
                echo json_encode(array("message" => "No Quotes Found"));
                exit();
              }  

            return true;
        }

        printf("Error: $s.\n", $stmt->error);

        return false;
    }


    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id RETURNING id';

        $stmt = $this->cn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt-> bindParam(':id', $this->id);

        if($stmt->execute()) {
          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          if ($row) {
              $quote_ary = array(
                'id' => $this->id
              );
              print_r(json_encode($quote_ary));
          }
          else {
            echo json_encode(array("message" => "No Quotes Found"));
            exit();
          }  

          return true;
        }

        printf("Error: $s.\n", $stmt->error);

        return false;
    }

  }
