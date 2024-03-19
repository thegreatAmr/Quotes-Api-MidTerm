<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    $method = $_SERVER['REQUEST_METHOD'];

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';  
    include_once '../../Utilities/utilities.php';

    $database = new Database();
    $db = $database->connect();

    $category = new Category($db);

     switch ($method) {
        case 'GET':
            if (!GetID()) { require 'read.php'; }
            else {
                if (validateID($method)) { require 'read_single.php'; }
                else {
                    die();
                }
            }
            break;

        case 'PUT':
            if (validateCategoryData($method)) { require 'update.php'; }
            break;

        case 'POST':
            if (validateCategoryData($method)) { require 'create.php'; }
            break;

        case 'DELETE':
            require 'delete.php';
            break;

        case 'OPTIONS':
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
            header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
            exit();
            break;
    }
