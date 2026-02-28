<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Orders</title>
</head>
<body>
    <h2>Search Orders by Order Date</h2>
    <form method="GET" action="">
        <label for="odate">Enter order date:</label>
        <input type="date" id="odate" name="odate" required>
        <button type="submit">Search</button>
    </form>
    <hr>

    <?php
    if (isset($_GET['odate'])) {
        // Escape input to prevent SQL injection
        $odate = mysqli_real_escape_string($conn, $_GET['odate']);

        // Build query safely with escaped string
        $sql = "SELECT o.order_id, o.product_name, o.order_date, u.username
                FROM orders o JOIN users u ON o.user_id = u.user_id
                WHERE DATE(o.order_date) = '$odate'";


        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<h3>Orders issued on <em>" . htmlspecialchars($odate) . "</em>:</h3>";
            echo "<ul>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo    "<li>Order #" . $row['order_id'] .
                        " - " . $row['product_name'] .
                        " (" . $row['order_date'] . ")" .
                        " by " . $row['username'] . "</li>";
            }
                echo "</ul>";
        } else {
            echo "<p>No orders found for date <em>" . htmlspecialchars($odate) . "</em>.</p>";
        }
    }
    ?>
</body>
</html>
