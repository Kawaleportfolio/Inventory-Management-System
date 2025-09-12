<?php
include '../partials/dbconnect.php';

if (isset($_POST['category'])) {
    $tem_category = mysqli_real_escape_string($con, $_POST['category']);

    $category_result = mysqli_query($con, "SELECT category_id FROM categories where category_name='$tem_category'");
    $row = mysqli_fetch_assoc($category_result);
    $category=$row['category_id'];

    $sql = "SELECT DISTINCT supplier_id, supplier_name FROM suppliers WHERE category_id='$category'";
    // Example: supplied_categories = 'pen,notebook'

    $result = mysqli_query($con, $sql);

    echo '<option value="">-- Select Supplier --</option>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='{$row['supplier_id']}'>{$row['supplier_name']}</option>";
    }
}
