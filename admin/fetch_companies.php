<?php
include '../partials/dbconnect.php';

if (isset($_POST['category'])) {
    $category = mysqli_real_escape_string($con, $_POST['category']);

    $query = "SELECT DISTINCT company_name FROM products WHERE category = '$category'";
    $result = mysqli_query($con, $query);

    echo '<option value="">-- Select Company --</option>';
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . htmlspecialchars($row['company_name']) . '">' . htmlspecialchars($row['company_name']) . '</option>';
        }
    } else {
        echo '<option value="">No companies found</option>';
    }
}
?>
