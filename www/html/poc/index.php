<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Orders</title>
</head>
<body>
    <h2>Search Orders by Username</h2>
    <form method="GET" action="">
        <input type="text" name="username" placeholder="Enter username" required>
        <button type="submit">Search</button>
    </form>
    <hr>

    <?php
    if (isset($_GET['username'])) {
        // Escape input to prevent SQL injection
        $username = mysqli_real_escape_string($conn, $_GET['username']);

        // Build query safely with escaped string
        $sql = "
            SELECT o.order_id, o.product_name, o.order_date
            FROM orders o
            JOIN users u ON o.user_id = u.user_id
            WHERE u.username = '$username'
        ";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<h3>Orders for <em>" . htmlspecialchars($username) . "</em>:</h3>";
            echo "<ul>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<li>Order #" . $row['order_id'] . 
                     " - " . $row['product_name'] . 
                     " (" . $row['order_date'] . ")</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No orders found for user <em>" . htmlspecialchars($username) . "</em>.</p>";
        }
    }
    ?>
</body>
</html>
