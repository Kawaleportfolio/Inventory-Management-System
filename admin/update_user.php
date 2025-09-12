<?php
include '../partials/dbconnect.php';
include '../partials/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u_id = $_POST['u_id'];
    $u_name = $_POST['u_name'];
    $u_email = $_POST['u_email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $query = "UPDATE users SET u_name='$u_name', u_email='$u_email', password='$password', role='$role' WHERE u_id=$u_id";

    if (mysqli_query($con, $query)) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
