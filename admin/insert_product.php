<?php
include '../partials/dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $p_name = trim($_POST['product_name']);
    $company_name = trim($_POST['brand_name']);
    $category_id = intval($_POST['category_id']);
    $cost_price = floatval($_POST['cost_price']);
    $selling_price = floatval($_POST['selling_price']);
    $gst_percent = $_POST['gst_percent'];

    // Input validation
    if (
        empty($p_name) || empty($company_name) || $category_id <= 0 ||
        $cost_price <= 0 || $selling_price <= 0
    ) {
        die("Invalid input. Please fill all required fields correctly.");
    }

    // Check if product name already exists (case-insensitive match)
    $check_query = mysqli_query($con, "SELECT * FROM products WHERE LOWER(p_name) = LOWER('$p_name')");
    if (mysqli_num_rows($check_query) > 0) {
        header("Location: add_product.php?exists=1&name=" . urlencode($p_name));
        exit;
    }

    // Get category name
    $cat_result = mysqli_query($con, "SELECT category_name FROM categories WHERE category_id = $category_id");
    $cat_row = mysqli_fetch_assoc($cat_result);
    $category_name = $cat_row['category_name'];
    $prefix = strtoupper(substr($category_name, 0, 3));

    // Insert product without barcode
    $insert = mysqli_query($con, "INSERT INTO products (p_name, company_name, category, cost_price, selling_price, gst_percent)
        VALUES ('$p_name', '$company_name', '$category_name', $cost_price, $selling_price, $gst_percent)");

    if ($insert) {
        $p_id = mysqli_insert_id($con);
        $barcode = $prefix . str_pad($p_id, 6, '0', STR_PAD_LEFT);

        mysqli_query($con, "UPDATE products SET barcode = '$barcode' WHERE p_id = $p_id");

        // ✅ Insert into product_stock table with qty = 0
        mysqli_query($con, "INSERT INTO product_stock (p_id, product_name, product_qty) 
            VALUES ($p_id, '$p_name', 0)");

        header("Location: add_product.php?success=1&name=" . urlencode($p_name));
        exit;
    } else {
        echo "❌ Error inserting product: " . mysqli_error($con);
    }
}
?>
