<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Orders</title>
</head>
<body>
    <h2>Search Orders</h2>
    <form method="GET" action="">
        <label for="odate">Enter order date:</label>
        <input type="date" id="odate" name="odate" required>
        <!--<input type="text" id=" -->
        <button type="submit">Search by Date</button>
     
    </form>
       <form method="GET" action="">
        <label for="prodname">Enter Product Name:</label>
        <input type="text" id="prodname" name="prodname" required>
        <button type="submit">Search by Product </button>
    </form>

    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" required>
        <input type="text" name="age" placeholder="age" required>
        <input type="number" id="income" name="income" placeholder="income" step="0.01" min="0" required>
        <button type="submit" name="add_user" value="Add User">Add User</button>
    </form>
    <hr>

    <?php
    if (isset($_GET['odate'])) {
        $odate = $_GET['odate'];
        $query = "SELECT o.order_id, o.product_name, o.order_date, u.username
                FROM orders o JOIN users u ON o.user_id = u.user_id
                WHERE DATE(o.order_date) = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $odate);
        $stmt->execute();

        $result = $stmt->get_result();

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
    if (isset($_GET['prodname'])) {
        $prodname = mysqli_real_escape_string($conn,$_GET['prodname']);
        $query = "SELECT * FROM orders WHERE product_name = '$prodname'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            echo "<h3>Orders for product <em>" . htmlspecialchars($prodname) . "</em>:</h3>";
            echo "<ul>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo    "<li>Order #" . $row['order_id'] .
                        " - " . $row['product_name'] .
                        " (" . $row['order_date'] . ")";            }
                echo "</ul>";
        } else {
            echo "<p>No orders found for product <em>" . htmlspecialchars($prodname) . "</em>.</p>";
        }
    }
    if (isset($_POST['add_user'])) {
        // $username = mysqli_real_escape_string($conn,$_POST['username']);
        $username = $_POST['username'];
        $income = $_POST['income'];
        $age = $_POST['age'];
        if ($_POST['age'] > 17){
            $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
            $query = "INSERT INTO users (username, age, income) VALUES ('$username', $age, $income)";
            $result = mysqli_query($conn, $query);
        }else{
            echo "<p><em>A user must be older than 17.  </em> </p>"; 
        }
    }
    ?>
</body>
</html>
