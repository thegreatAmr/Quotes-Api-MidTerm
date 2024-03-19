<?php

    function GetID() {
        if (isset($_GET['id'])) {
            return $_GET['id'];
        }
        else {
            $data = json_decode(file_get_contents("php://input"));
            if ($data) {
                if (isset($data->id)) {
                    return $data->id;
                }
                else {
                    return null;
                }
            }
            return null;
        }
    }

    function validateQuoteData($method) {
        $valid = true;
        $msgAry = array();

        $data = json_decode(file_get_contents("php://input"));
        if (!$data) {
            echo json_encode(array('message' => 'Missing Required Parameters'));
            exit();
        }
        else {
            if (!isset($data->quote) or 
                !isset($data->author_id) or 
                !isset($data->category_id)) {
                echo json_encode(array('message' => 'Missing Required Parameters'));
                exit();
            }

            $valid = validateID($method);

            $result = Get_Author($data->author_id);
            if (!$result->rowCount()) {
                echo json_encode(array('message' => 'author_id Not Found'));
                exit();
            }

            $result = Get_Category($data->category_id);
            if (!$result->rowCount()) {
                echo json_encode(array('message' => 'category_id Not Found'));
                exit();
            }            
            
        }
        return $valid;
    }

    function validateAuthorData($method) {
        $valid = true;
        $msgAry = array();

        $data = json_decode(file_get_contents("php://input"));

        if (!$data) {
            echo json_encode(array("message" => "Missing Required Parameters"));
            exit();
        }
        else {
            $valid = validateID($method);
        
            if (!isset($data->author)) {
                echo json_encode(array("message" => "Missing Required Parameters"));
                exit();
                }
        }
        return $valid;
    }

    function validateCategoryData($method) {
        $valid = true;
        $msgAry = array();

        $data = json_decode(file_get_contents("php://input"));
        if (!$data) {
            echo json_encode(array("message" => "Missing Required Parameters"));
            exit();
        }
        else {
            $valid = validateID($method);
        
            if (!isset($data->category)) {
                echo json_encode(array("message" => "Missing Required Parameters"));
                exit();
                }
        }
        return $valid;
    }

    function validateID($method) {
        $id = GetID();

        if ($method === 'POST') { return true; }

        if (!$id) {
            echo json_encode(array('ValidateID' => 'Missing data: ID is null.'));
            return false;
        }
        else {
            if (!is_int($id) and (!is_numeric($id) or !ctype_digit($id))) {    
                echo json_encode(array('ValidateID' => 'Invalid data: ID must be an integer.'));
                return false;
            }
        }
        return true;
    }

    function Get_Author($id){
        $db = new Database();
        $cn = $db->connect();

        $query = 'SELECT * from authors where id = :id';

        $stmt = $cn->prepare($query);

        $stmt->bindParam(':id', $id);

        $stmt->execute();
        
        return $stmt;
    }

    function Get_Category($id){
        $db = new Database();
        $cn = $db->connect();

        $query = 'SELECT * from categories where id = :id';

        $stmt = $cn->prepare($query);

        $stmt->bindParam(':id', $id);

        $stmt->execute();
        
        return $stmt;
    }
