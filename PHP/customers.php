<html>
<head>
    <title>Busterblock Customers</title>
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

        $id = $_GET['id'];

        $query='SELECT address FROM address WHERE address_id = ' . $id;

        $queryStr = mysqli_query($connection, $query);

        if ($queryStr->num_rows > 0) {

            while($row = $queryStr->fetch_assoc()) {
                echo "<h2 id='formHeader'>List of customers at " . $row["address"] . "</h2>";
            }
        } else {

            echo "No results found";
        }

        $query='SELECT  customer.first_name,
                        customer.last_name,
                        customer.email,
                        customer.customer_id
                FROM customer
                WHERE customer.store_id = ' . $id . '
                ORDER BY customer.last_name ASC
        ';

        $lineNumber = 1;
        $queryStr = mysqli_query($connection, $query);
    echo "<h3>Customers With Outstanding Rentals</h3>";    
	if ($queryStr->num_rows > 0) {

        echo "<table id='table'><tr><th></th><th>Name</th><th>Email</th><th>Rental History</th><th>New Rental</th></tr>";

            while($row = $queryStr->fetch_assoc()) {
                $check = 0;

                $query2='SELECT return_date
                        FROM rental
                        WHERE customer_id = ' . $row['customer_id'] . '
                ';

                $queryStr2 = mysqli_query($connection, $query2);

                if ($queryStr2->num_rows > 0) {
                    while($row2 = $queryStr2->fetch_assoc()) {
                        if ($row2['return_date'] == NULL) {
                            $check += 1;
                        }
                    }
                }
                if ($check > 0) {
                    echo "<tr>
                            <td>" . $lineNumber . "</td>
                            <td>" . $row["first_name"] . " " . $row["last_name"] . "</td>
                            <td>" . $row["email"] . "</td>
                            <td><a href='rentalHistory.php?prevId=" . $id . "&id=" . $row["customer_id"] . "&name=" . urlencode($row["first_name"] . " ".$row["last_name"])."'>View</a></td>
                            <td><a <a href='newRental.php?prevId=" . $id . "&id=" . $row["customer_id"] . "&name=" . urlencode($row["first_name"] . " ".$row["last_name"])."'>Rent</a></td>
                        </tr>";
                    $lineNumber += 1;
                }
            }
            echo "</table>";
        } else {
            echo "No results found";
        }

        $lineNumber = 1;
        $myQuery = mysqli_query($connection, $query);
       
        echo "<h3>Customers With NO Outstanding Rentals</h3>";
        if ($myQuery->num_rows > 0) {
            
            echo "<table id='table'><tr><th></th><th>Name</th><th>Email</th><th>Rental History</th><th>New Rental</th></tr>";

            while($row = $myQuery->fetch_assoc()) {
               
                $check = 0;

                $query2='SELECT
                            rental.return_date
                        FROM
                            rental
                        WHERE
                            rental.customer_id = ' . $row['customer_id'] . '
                ';

                $myQuery2 = mysqli_query($connection, $query2);

                if ($myQuery2->num_rows > 0) {
                    while($row2 = $myQuery2->fetch_assoc()) {
                        if ($row2['return_date'] == NULL) {
                            $check += 1;
                        }
                    }
                }
                if ($check < 1) {
                    echo "<tr>
                            <td>" . $lineNumber . "</td>
                            <td>" . $row["first_name"] . " " . $row["last_name"] . "</td>
                            <td>" . $row["email"] . "</td>
                            <td><a href='./rental-history.php?prevId=" . $id . "&id=" . $row["customer_id"] . "&name=" . urlencode($row["first_name"] . " ".$row["last_name"])."'>View</a></td>
                            <td><a <a href='./new-rental.php?prevId=" . $id . "&id=" . $row["customer_id"] . "&name=" . urlencode($row["first_name"] . " ".$row["last_name"])."'>Rent</a></td>
                        </tr>";
                    $lineNumber += 1;
                }
            }
            echo "</table>";
        } else {

            echo "No results found";
        }

        echo "<a id='back' href='./'>Back</a>";
    ?>
</body>
</html>
