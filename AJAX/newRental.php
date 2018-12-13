<html>
<head>
    <title>Busterblock New Rental</title>
    <link rel="stylesheet" href="styles.css">
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
                New rental for customer " . urldecode($_GET['name']) . "
            </h2>
        ";

        echo "
            <form id='search' method='POST'>
                <input type='text' name='query'>
                <input type='submit' name='search' value='Search'>
            </form>
        ";

        if (isset($_POST['query'])) {
            if ($_POST['query'] != '') {

                $searchQuery = "'%" . $_POST['query'] . "%'";

		echo "<p>Movie titles containing: <b>" . $_POST['query'] . "</b></p>";

                $query = '  SELECT  film.title,
                                    film.rating,
                                    film.length,
                                    film.film_id
                            FROM film
                            WHERE film.title LIKE ' . $searchQuery . '
                ';

                $queryStr = mysqli_query($connection, $query);

                if ($queryStr->num_rows > 0) {

                    echo "<table id='table'><tr><th>Title</th><th>Rating</th><th>Duration</th><th>Actors</th><th>Available Inventory</th></tr>";

                    while($row = $queryStr->fetch_assoc()) {
                        $keyword = $_POST['query'];
                        $row["title"] = strtoupper(str_ireplace($keyword, '<span style="color: red;">'.$keyword.'</span>', $row["title"]));

                        echo "<tr>
                                <td>" . $row["title"] . "</td>
                                <td>" . $row["rating"] . "</td>
                                <td>" . $row["length"] . "</td>
                        ";

                        $filmId = $row['film_id'];

                        $actorQuery = " SELECT   first_name,
                                                last_name
                                        FROM    actor,
                                                film_actor
                                        WHERE   film_actor.film_id = " . $filmId . " AND
                                                film_actor.actor_id = actor.actor_id
                        ";

                        $myActorQuery = mysqli_query($connection, $actorQuery);

                        if ($myActorQuery->num_rows > 0) {
                            echo "<td><ul>";
                            while ($row = $myActorQuery->fetch_assoc()) {
                                echo "<li>" . $row["first_name"] . " " . $row["last_name"] . "</li>";
                            }
                            echo "</ul></td>";
                        }

                        $inventoryQuery = " SELECT inventory_id
                                            FROM inventory
                                            WHERE " . $filmId  . " = film_id
                        ";

                        $myInventoryQuery = mysqli_query($connection, $inventoryQuery);

                        if ($myInventoryQuery->num_rows > 0) {
                            echo "<td><ul>";
                            while ($row = $myInventoryQuery->fetch_assoc()) {
                                echo "<li><a href='" . "checkout.php?prevId=" . $_GET['prevId'] . "&id=" . $_GET['id'] . "&name=" . urlencode($_GET['name']) . "&film=" . $filmId . "&inv=" . $row["inventory_id"] .  "'>" . $row["inventory_id"] . "</a></li>";
                            }
                            echo "</ul></td>";
                        }
                    }
                    echo "</table>";
                } else {
                    echo "No results found";
                }
            } else {
                echo "
                    <table id='table'>
                        <tr>
                            <th>Title</th>
                            <th>Rating</th>
                            <th>Duration</th>
                            <th>Actors</th>
                            <th>Available Inventory</th>
                        </tr>
                        <tr>
                            <td colspan='100%' style='text-align: center;'>No matching titles</td>
                        </tr>
                    </table>
                ";
            }
        } else {
            echo "
                <table id='table'>
                    <tr>
                        <th>Title</th>
                        <th>Rating</th>
                        <th>Duration</th>
                        <th>Actors</th>
                        <th>Available Inventory</th>
                    </tr>
                    <tr>
                        <td colspan='100%' style='text-align: center;'>No matching titles</td>
                    </tr>
                </table>
            ";
        }
        echo "<a id='back' href='customers.php?id=" . $_GET["prevId"] . "'>Back</a>";
    ?>
</body>
</html>


