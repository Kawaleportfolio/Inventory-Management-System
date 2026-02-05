<?php
include '../partials/dbconnect.php';
include '../partials/auth_check.php';

$barcode = $_POST['barcode'] ?? '';

$response = ['success' => false];

if ($barcode) {
    $barcode = mysqli_real_escape_string($con, $barcode);

    $query = mysqli_query($con, "SELECT p_id, p_name, barcode, selling_price, gst_percent FROM products WHERE barcode = '$barcode' LIMIT 1");

    if ($row = mysqli_fetch_assoc($query)) {
        $p_id = $row['p_id'];

        $stockResult = mysqli_query($con, "SELECT product_qty FROM product_stock WHERE p_id = $p_id");
        $stock = mysqli_fetch_assoc($stockResult);

        $response = [
            'success' => true,
            'name' => $row['p_name'],
            'barcode' => $row['barcode'],
            'price' => $row['selling_price'],
            'ava_qty' => $stock['product_qty'],
            'gst' => $row['gst_percent'] ?? 0
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
