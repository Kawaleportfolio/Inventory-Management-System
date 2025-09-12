<?php
include '../partials/dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $supplier_name = trim($_POST['supplier_name']);
    $company_name = trim($_POST['company_name']);
    $gst_number = trim($_POST['gst_number']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $category_id = intval($_POST['category_id']);

    // Input validation
    if (
        empty($supplier_name) || empty($company_name) || $category_id <= 0 ||
        empty($phone) || empty($gst_number)
    ) {
        die("❌ Invalid input. Please fill all required fields correctly.");
    }

    // Check if supplier already exists (case-insensitive)
    $check_query = mysqli_query($con, "SELECT * FROM suppliers WHERE LOWER(supplier_name) = LOWER('$supplier_name')");
    if (mysqli_num_rows($check_query) > 0) {
        header("Location: add_supplier.php?exists=1&name=" . urlencode($supplier_name));
        exit;
    }

    // Insert supplier
    $insert = mysqli_query($con, "INSERT INTO suppliers (supplier_name, company_name, gst_number, phone, email, address, category_id) VALUES ('$supplier_name', '$company_name', '$gst_number', '$phone', '$email', '$address', $category_id)");

    if ($insert) {
        header("Location: add_supplier.php?success=1&name=" . urlencode($supplier_name));
        exit;
    } else {
        echo "❌ Error inserting supplier: " . mysqli_error($con);
    }
}
?>
