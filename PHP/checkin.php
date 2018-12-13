<html>
<head>
    <title>Busterblock Check-In</title>
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

        echo "<h2 id='formHeader'>Check-in Movie</h2>";

        echo "<table id='table'>";

        echo "<tr>
                <th>Customer</th>
                <td>" . urldecode($_GET["name"]) . "</td>
            </tr>
            <tr>
                <th>Movie Title</th>
                <td>" . urldecode($_GET['film']) . "</td>
            </tr>
            <tr>
                <th>Inventory ID</th>
                <td>" . $_GET['inv'] . "</td>
            </tr>
        ";
        echo "</table>";

        if (isset($_POST['confirm'])) {

            $date = date('Y-m-d H:i:s', time());

            $query="UPDATE rental
                    SET return_date = '" . $date . "'
                    WHERE inventory_id = " . $_GET['inv'] . "
            ";

            if (mysqli_query($connection, $query)) {
                echo "<p id='checked-out'>Movie checked in successfully!</p>";
            }
        } else {
            echo "<div id='confirm-cancel'>
                    <form id='confirm' method='POST'>
                        <input type='submit' name='confirm' value='Confirm'>
                    </form>";
            echo    "<div id='cancel'>
                        <a href='newRental.php?prevId=" . $_GET["prevId"] . "&id=" . $_GET['id'] . "&name=" . $_GET['name']. "'>
                        <input type='submit' class='button_active' value='Cancel'</a>
                    </div>
                  </div>";
        }

        echo "<a id='back' href='rentalHistory.php?prevId=" . $_GET["prevId"] . "&id=" . $_GET['id'] . "&name=" . $_GET['name']. "'>Back</a>";
    ?>
</body>
</html>

