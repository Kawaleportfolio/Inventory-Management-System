<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'dbconnect.php';

    $u_name = $_POST['username'];
    $u_pass = $_POST['password'];

    // Directly check for username and password match
    $sql = "SELECT * FROM users WHERE u_name = '$u_name' AND password = '$u_pass'";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        session_start();
        $_SESSION['loggedin'] = true;
        // $_SESSION['sno'] = $row['sno']; // Adjust column name if different
        $_SESSION['useremail'] = $row['u_email'];
        $_SESSION['username'] = $row['u_name'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['u_id'] = $row['u_id'];

        if($row['role']=="admin"){
            header("Location: /IMWBI/admin/adminpannel.php?loginsuccess=true");
        }elseif($row['role']=="employee"){
            header("Location: /IMWBI/emp/employeepannel.php?loginsuccess=true");
        }

        // header("Location: /pkcode/FORUM/index.php?loginsuccess=true");
        // echo "You are logged in as".$u_name;
        exit();
    } else {
        header("Location: /IMWBI/index.php?loginsuccess=false");
        exit();
    }
}
?>
