<?php
include '../partials/dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['p_id']);
    $name = mysqli_real_escape_string($con, $_POST['product_name']);
    $brand = mysqli_real_escape_string($con, $_POST['brand_name']);
    $category = intval($_POST['category_id']);
    
    #fetch category name by category id 
    $sql=mysqli_fetch_assoc(mysqli_query($con,"SELECT category_name FROM categories where category_id='$category'"));
    $category_name=$sql['category_name'];
    ##

    $cost = floatval($_POST['cost_price']);
    $selling = floatval($_POST['selling_price']);

    $query = "UPDATE products SET p_name = '$name',company_name = '$brand',category = '$category_name',cost_price = $cost,selling_price = $selling WHERE p_id = $id";
    // echo "<script>console.log(" . json_encode($query) . ");</script>";
    mysqli_query($con, $query);

    //update product_stock table also 
    mysqli_query($con, "UPDATE product_stock SET product_name = '$name', UpdatedDateTime = CURRENT_TIMESTAMP WHERE p_id = $id");

    echo json_encode(['status' => 'success']);
}
?>
