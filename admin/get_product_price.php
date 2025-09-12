<?php
include '../partials/dbconnect.php';

if (isset($_POST['product_name'])) {
    $product_name = mysqli_real_escape_string($con, $_POST['product_name']);
    $result = mysqli_query($con, "SELECT cost_price FROM products WHERE p_name = '$product_name'");

    if ($row = mysqli_fetch_assoc($result)) {
        echo $row['cost_price'];
    } else {
        echo '0';
    }
}
?>
