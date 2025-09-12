<?php
include '../partials/dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // First delete from product_stock [because we refer the FK with products table]
    mysqli_query($con, "DELETE FROM product_stock WHERE p_id = $id");

    mysqli_query($con, "DELETE FROM products WHERE p_id = $id");
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
