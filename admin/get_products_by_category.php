<?php
include '../partials/dbconnect.php';

if (isset($_POST['category'])) {
    $category = mysqli_real_escape_string($con, $_POST['category']);

    $query = "SELECT p_id, p_name FROM products WHERE category = '$category' ORDER BY p_name";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result)) {
        echo '<option value="">-- Choose Product --</option>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $row['p_id'] . '">' . htmlspecialchars($row['p_name']) . '</option>';
        }
    } else {
        echo '<option value="">No products found</option>';
    }
} else {
    echo '<option value="">Invalid request</option>';
}
