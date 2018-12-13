<html>
<head>
    <title>Busterblock Welcome</title>
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <h1 id = 'businessName'>Busterblock</h1>
    <?php
    
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

        $queryStr = mysqli_query($connection, $query);

        if ($queryStr->num_rows > 0) {

            echo "<table id='table'><tr><th>Store</th><th>Manager</th><th>Address</th><th>Country</th></tr>";

            while($row = $queryStr->fetch_assoc()) {
                echo "<tr>
                        <td><a href='customers.php?id=" . $row["store_id"] . "'>" . $row["store_id"] . "</a></td>
                        <td>" . $row["name"] . "</td>
                        <td>" . $row["address"] . ", " . $row["city"] . "</td>
                        <td>" . $row["country"] . "</td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "No results found";
        }
    ?>
    <p>Created By: Brandon Conn</p>
</body>
</html>
