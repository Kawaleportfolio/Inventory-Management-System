<?php
include 'dbconnect.php';

$username = $_POST['username'];
$email    = $_POST['email'];

$sql = "SELECT * FROM users WHERE u_name='$username' AND u_email='$email'";
$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "success";
} else {
    echo "Username and Email ID do not match";
}
?>