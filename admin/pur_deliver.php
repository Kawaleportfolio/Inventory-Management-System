    <?php
    include '../partials/dbconnect.php';

    if (isset($_GET['order_id'])) {
        $order_id = intval($_GET['order_id']);
        // $result = mysqli_query($con, "select p_id, cost_price, quantity, total_price from purchase_items where purchase_id=$order_id");
        // in this query purchase status change into Delivered
        $sql = mysqli_query($con, "update purchases set status='Delivered', delivered_date = NOW() where purchase_id=$order_id");

        $result = mysqli_query($con, "SELECT p_id, quantity FROM purchase_items WHERE purchase_id = $order_id");
        while ($row = mysqli_fetch_assoc($result)) {
            // echo "Product ID: " . $row['p_id'] . " | Quantity: " . $row['quantity'] . "<br>";
            $product_id=$row['p_id'];
            $product_qty=$row['quantity'];

            mysqli_query($con, "UPDATE product_stock SET product_qty =product_qty+$product_qty, UpdatedDateTime = NOW() WHERE p_id = $product_id");
        }
        echo "Order Delivered Successfully.<br>Product Stock Updated!";
    } else {
        echo "<p>Invalid request</p>";
    }
    ?>
