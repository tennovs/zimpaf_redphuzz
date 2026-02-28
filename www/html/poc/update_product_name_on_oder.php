<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Product Name</title>
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
// Handle updates first
if (isset($_POST['update'])) {
    $order_id = $_POST['order_id'];
    $new_name = $_POST['product_name'];

    $update_sql = "UPDATE orders SET product_name = ? WHERE order_id = ?";
    $stmt = $conn->prepare($update_sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("si", $new_name, $order_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<p>Order #$order_id updated successfully!</p>";
    } else {
        echo "<p>No changes made to Order #$order_id.</p>";
    }

    $stmt->close();
}

// Handle search
if (isset($_GET['odate'])) {
    $odate = $_GET['odate'];

    $sql = "SELECT o.order_id, o.product_name, o.order_date, u.username
            FROM orders o JOIN users u ON o.user_id = u.user_id
            WHERE DATE(o.order_date) = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) die("Prepare failed: " . $conn->error);

    $stmt->bind_param("s", $odate);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h3>Orders issued on <em>" . htmlspecialchars($odate) . "</em>:</h3>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            ?>
            <li>
                <form method="POST" style="display:inline;">
                    Order #<?php echo $row['order_id']; ?> -
                    <input type="text" name="product_name" value="<?php echo htmlspecialchars($row['product_name']); ?>" required>
                    (<?php echo $row['order_date']; ?>) by <?php echo $row['username']; ?>
                    <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                    <button type="submit" name="update">Update</button>
                </form>
            </li>
            <?php
        }
        echo "</ul>";
    } else {
        echo "<p>No orders found for date <em>" . htmlspecialchars($odate) . "</em>.</p>";
    }

    $stmt->close();
}
?>
</body>
</html>
