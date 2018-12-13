<html>
<head>
    <title>Busterblock Checkout</title>
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
                Checkout Movie
            </h2>
        ";

        $query='SELECT title
                FROM film
                WHERE film_id = ' . $_GET['film'] . '
        ';

        $queryStr = mysqli_query($connection, $query);

        $row = mysqli_fetch_array($queryStr);

        if ($queryStr->num_rows > 0) {

            echo "<table id='table'>";

            echo "<tr>
                    <th>Customer</th>
                    <td>" . urldecode($_GET["name"]) . "</td>
                </tr>
                <tr>
                    <th>Movie Title</th>
                    <td>" . $row['title'] . "</td>
                </tr>
                <tr>
                    <th>Inventory ID</th>
                    <td>" . $_GET['inv'] . "</td>
                </tr>
            ";

            echo "</table>";

            if (isset($_POST['confirm'])) {

                $date = date('Y-m-d H:i:s', time());

                $query="INSERT INTO rental (rental_date, customer_id, staff_id, inventory_id)
                        VALUES ('" . $date . "', '" . $_GET['id'] . "', '" . $_GET['prevId'] . "', '" . $_GET['inv'] . "')
                ";

                if (mysqli_query($connection, $query)) {
                    echo "<p id='checked-out'>Movie checked out successfully!</p>";
                }
            } else {
                echo "<div id='submitButtons'>
                        <form id='confirm' method='POST'>
                            <input type='submit' name='confirm' value='Confirm'>
                        </form>";
                echo "<div id='cancel'>
                        <a href='newRental.php?prevId=" . $_GET["prevId"] . "&id=" . $_GET['id'] . "&name=" . $_GET['name']. "'>
                        <input type='submit' class='button_active' value='Cancel'</a>
                      </div>
                    </div>";
            }
        } else {
            echo "No results found";
        }
        echo "<a id='back' href='newRental.php?prevId=" . $_GET["prevId"] . "&id=" . $_GET['id'] . "&name=" . $_GET['name']. "'>Back</a>";
    ?>
</body>
</html>
