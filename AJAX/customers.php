<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    session_start();

    require_once ('../../file.php');

    $connection = new mysqli($server, $user, $pass, $db);

    $id = $_GET['id'];

    $query='SELECT  customer.first_name,
                    customer.last_name,
                    customer.email,
                    customer.customer_id
            FROM customer
            WHERE customer.store_id = ' . $id . '
            ORDER BY customer.last_name ASC
    ';

    $result = $connection->query($query);
    $allrows = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($allrows);
?>