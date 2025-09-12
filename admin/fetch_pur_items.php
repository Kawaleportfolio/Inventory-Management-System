    <?php
    include '../partials/dbconnect.php';

    if (isset($_GET['order_id'])) {
        $order_id = intval($_GET['order_id']);
        $result = mysqli_query($con, "select p_id, cost_price, quantity, total_price from purchase_items where purchase_id=$order_id");

        if (mysqli_num_rows($result) > 0) {
            echo "<table class='table table-bordered table-sm'>
                    <thead class='table-light'>
                    <tr>
                        <th>Product Name</th>
                        <th>Qty Ordered</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>";
            while ($row = mysqli_fetch_assoc($result)) {
                $product = mysqli_fetch_assoc(mysqli_query($con, "SELECT p_name from products WHERE p_id = " . $row['p_id']));
                echo "<tr>
                        <td>{$product['p_name']}</td>
                        <td>{$row['quantity']}</td>
                        <td>₹{$row['cost_price']}</td>
                        <td>₹{$row['total_price']}</td>
                    </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No products found.</p>";
        }
    } else {
        echo "<p>Invalid request</p>";
    }
    ?>
