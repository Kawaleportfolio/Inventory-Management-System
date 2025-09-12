<?php
include '../partials/dbconnect.php';

if (isset($_POST['supplier_id'])) {
    $supplier_id = intval($_POST['supplier_id']);
    $result = mysqli_query($con, "SELECT * FROM suppliers WHERE supplier_id = $supplier_id");

    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode([
            'company' => $row['company_name'],
            'phone' => $row['phone'],
            'email' => $row['email'],
            'address' => $row['address']
        ]);
    }
}
