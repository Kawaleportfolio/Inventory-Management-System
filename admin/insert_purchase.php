<?php
include '../partials/dbconnect.php';
include '../partials/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data'])) {
    $data = json_decode($_POST['data'], true);

    $supplier_id = intval($data['supplier']);
    $order_date = $data['orderDate'];
    $expected_date = $data['expectedDate'];
    $total_amount = number_format((float)$data['totalAmount'], 2, '.', '');

    $cart = $data['cart'];

    if (empty($cart)) {
        http_response_code(400);
        echo "Cart is empty!";
        exit;
    }

    // 🔹 Step 1: Insert into purchases table
    $insertPurchaseQuery = "INSERT INTO purchases (supplier_id, order_date, expected_delivery_date, total_amount, status) VALUES ('$supplier_id', '$order_date', '$expected_date', '$total_amount', 'Pending')";

    if (mysqli_query($con, $insertPurchaseQuery)) {
        $purchase_id = mysqli_insert_id($con); // get last inserted purchase_id

        // 🔹 Step 2: Insert into purchase_items
        foreach ($cart as $item) {
            $product = strval($item['product']);
            $sql = mysqli_query($con, "SELECT p_id FROM products where p_name='$product'");
            $row = mysqli_fetch_assoc($sql);
            $product_id = $row['p_id'];
            // $p_id = intval($item['productId']);
            // echo "Product ID".$p_id;

            $cost_price = intval($item['price']);
            $qty = intval($item['quantity']);

            $gst= intval($item['gstPercent']);
            $gst_amount = number_format((float)$item['gstamount'], 2, '.', '');
            $total= intval($item['total']);

            $totalprice = number_format((float)$item['totalprice'], 2, '.', '');


            $insertItemQuery = "INSERT INTO purchase_items (purchase_id, p_id, cost_price, quantity, gst_percent, gst_amount, total, total_price) VALUES ('$purchase_id', '$product_id', '$cost_price', '$qty', '$gst', '$gst_amount', '$total', '$totalprice')";
            mysqli_query($con, $insertItemQuery);
        }

        echo "Purchase successfully inserted with ID: $purchase_id";
    } else {
        http_response_code(500);
        echo "Error inserting purchase: " . mysqli_error($con);
    }
} else {
    http_response_code(405);
    echo "Invalid request";
}
