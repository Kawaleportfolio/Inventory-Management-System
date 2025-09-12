<?php
include '../partials/dbconnect.php';

if (isset($_POST['category']) && isset($_POST['company'])) {
    $category = mysqli_real_escape_string($con, $_POST['category']);
    $company = mysqli_real_escape_string($con, $_POST['company']);

    $query = "SELECT p_name, cost_price FROM products WHERE category = '$category' AND company_name = '$company'";
    $result = mysqli_query($con, $query);

    echo '<option value="">-- Select Product --</option>';
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $product = htmlspecialchars($row['p_name']);
            $price= htmlspecialchars($row['cost_price']);
            echo "<option value='$product'>$product</option>";
           
        }
    } else {
        echo "<option value=''>No products found</option>";
    }
}
?>
