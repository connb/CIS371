<?php
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");
    
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

        session_start();

        require_once ('../../file.php');

        $connection = new mysqli($server, $user, $pass, $db);

        $query='SELECT  staff_list.name, 
                        store.store_id, 
                        address.address, 
                        staff_list.city, 
                        staff_list.country
                FROM    staff_list, 
                        store, 
                        address
                WHERE   staff_list.SID = store.store_id AND 
                        address.address_id = store.store_id
        ';

        $result = $connection->query($query);
        $allrows = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($allrows)
?>
