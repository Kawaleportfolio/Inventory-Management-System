<?php

include '../partials/dbconnect.php';
include '../partials/auth_check.php';

if (isset($_POST['u_id'])) {
    $id = intval($_POST['u_id']);

    // Optional: check if the user exists first (for safety)
    $check = mysqli_query($con, "SELECT * FROM users WHERE u_id = $id");

    if (mysqli_num_rows($check) > 0) {
        $query = "DELETE FROM users WHERE u_id = $id";
        if (mysqli_query($con, $query)) {
            echo "success";
        } else {
            echo "error: " . mysqli_error($conn); // <--- This will help debug
        }
    } else {
        echo "error: user not found";
    }
} else {
    echo "error: id not set";
}
 ?>