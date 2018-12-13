<html>
<head>
    <title>Busterblock History</title>
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

        echo "
            <h2 id='formHeader'>
                Rental history of " . urldecode($_GET['name']) . "
            </h2>
        ";

        $query='SELECT  rental.rental_date,
                        rental.return_date,
                        inventory.inventory_id,
                        film.title
                FROM rental, film, inventory
                WHERE   rental.customer_id = '. $_GET['id'] .' AND
                        rental.inventory_id = inventory.inventory_id AND
                        inventory.film_id = film.film_id
                ORDER BY rental.return_date ASC
        ';

	$queryStr = mysqli_query($connection, $query);

        if ($queryStr->num_rows > 0) {

            echo "<table id='table'><tr><th></th><th>Film Title</th><th>Rental Date</th><th>Return Date</th></tr>";

            $count = 1;

            while($row = $queryStr->fetch_assoc()) {

                if ($row["return_date"] === null) {
                    $row["return_date"] = "<a href='checkin.php?inv=" . $row['inventory_id'] . "&film=" . urlencode($row['title']) . "&name=" . urlencode($_GET['name']) . "&prevId=" . $_GET['prevId'] . "&id=" . $_GET['id'] . "'>Check-In</a>";
                }

                echo "<tr>
                        <td>" . $count . "</td>
                        <td>" . $row["title"] . "</td>
                        <td>" . $row["rental_date"] . "</td>
                        <td>" . $row["return_date"] . "</td>
                    </tr>";

                $count += 1;
            }
            echo "</table>";
        } else {
            echo "No results found";
        }
        echo "<a id='back' href='customers.php?id=" . $_GET["prevId"] . "'>Back</a>";
    ?>
</body>
</html>


