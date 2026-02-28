<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Product Name</title>
</head>
<body>
    <h2>Update Product Name for an Order</h2>

    <!-- Step 1: Select an order -->
    <form method="GET" action="">
        <label for="order_id">Select an order:</label>
        <select name="order_id" id="order_id" required>
            <option value="">-- Select Order --</option>
            <?php
            $sql = "SELECT order_id, product_name, order_date FROM orders ORDER BY order_date";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row['order_id'] . '">Order #' . $row['order_id'] . ' - ' . htmlspecialchars($row['product_name']) . ' (' . $row['order_date'] . ')</option>';
            }
            ?>
        </select>
        <button type="submit" name="select_order">Select</button>
    </form>

    <hr>

<?php
// Step 2: Show update form if an order is selected
if (isset($_GET['select_order']) && !empty($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Fetch current product name
    $stmt = $conn->prepare("SELECT product_name FROM orders WHERE order_id = ?"); //domain violation
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->bind_result($current_name);
    $stmt->fetch();
    $stmt->close();

    ?>
    <h3>Update Product Name for Order #<?php echo $order_id; ?></h3>
    <form method="POST" action="">
        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
        <label for="product_name">Product Name:</label>
        <input type="text" name="product_name" id="product_name" maxlength="20" value="<?php echo htmlspecialchars($current_name); ?>" required>
        <button type="submit" name="update">Update</button>
    </form>
    <?php
}

// Step 3: Handle the update
if (isset($_POST['update'])) {
    $order_id = $_POST['order_id'];
    $new_name = $_POST['product_name'];

    $stmt = $conn->prepare("UPDATE orders SET product_name = ? WHERE order_id = ?");
    $stmt->bind_param("si", $new_name, $order_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<p>Order #$order_id updated successfully to <strong>" . htmlspecialchars($new_name) . "</strong>.</p>";
    } else {
        echo "<p>No changes made to Order #$order_id.</p>";
    }

    $stmt->close();
}
?>

</body>
</html>
