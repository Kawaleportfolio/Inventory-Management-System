<?php
include 'dbconnect.php'; // DB connection file
$username = $_POST['username'];
$email = $_POST['email'];
$new_password = $_POST['new_password']; // for now (bcrypt better later)

$update = "UPDATE users SET password='$new_password' WHERE u_name='$username' AND u_email='$email'";

if (mysqli_query($con, $update)) {
    // echo "Password updated successfully";
    header("Location: /IMWBI/index.php?updatepassword=true");
} else {
    echo "Error updating password";
}
?>
