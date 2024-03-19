<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    $method = $_SERVER['REQUEST_METHOD'];

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';  
    include_once '../../Utilities/utilities.php';

    $database = new Database();
    $db = $database->connect();

    $author = new Author($db);

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
            if (validateAuthorData($method)) { require 'update.php'; }
            break;

        case 'POST':
            if (validateAuthorData($method)) { require 'create.php'; }
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
