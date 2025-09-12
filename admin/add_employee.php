<?php
include '../partials/dbconnect.php';
include '../partials/auth_check.php';

// data insert successfully popup
// session_start();
if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    echo "<script>
        window.onload = function() {
            alert('{$msg['text']}');
        };
    </script>";
    unset($_SESSION['msg']);
}
//end here popup    

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    echo $username;

    if (empty($username) || empty($email) || empty($password) || empty($role)) {     #if admin submit without entered data this script active
        $_SESSION['msg'] = ['type' => 'error', 'text' => 'All fields are required.'];
        header("Location: add_employee.php");
        exit();
    }  # and end here

    $sql = "INSERT INTO users (u_name,u_email,password,role) VALUES (?,?,?,?)";
    $stmt = $con->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssss", $username, $email, $password, $role);
        if ($stmt->execute()) {
            $_SESSION['msg'] = ['type' => 'success', 'text' => 'Employee added successfully.'];
        } else {
            $_SESSION['msg'] = ['type' => 'error', 'text' => 'Database error: ' . $stmt->error];
        }
        $stmt->close();
    } else {
        $_SESSION['msg'] = ['type' => 'error', 'text' => 'Prepare failed: ' . $con->error];
    }

    $con->close();
    header("Location: add_employee.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Employee</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            /* font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; */
            background-color: rgb(213, 214, 218);
            display: flex;
            min-height: 100vh;
        }

        .container {
            display: flex;
            flex-direction: row;
            width: 100%;
            height: 100vh;
        }

        .left {
            flex: 1;
            background: url('../assets/images/addemployee.png') no-repeat center center/contain;
            background-size: 50%;
            background-repeat: no-repeat;
            background-position: center;
        }

        .right {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-box {
            background: white;
            color: black;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        .form-box h2 {
            text-align: center;
            margin-bottom: 30px;
            color: black;
        }

        .form-box label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-box input,
        .form-box select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #999;
            border-radius: 5px;
        }

        .form-box button {
            width: 100%;
            padding: 10px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .form-box button:hover {
            background-color: #333;
        }

        /* Back to Admin Panel button style */
        .btn-back {
            text-decoration: none;
            background-color: #007bff;
            /* Bootstrap-like blue */
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            font-weight: bold;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease;
        }

        .btn-back:hover {
            background-color: #0056b3;
        }


        @media (max-width: 850px) {
            .container {
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }

            .left {
                display: none;
            }

            .right {
                width: 100%;
                height: 100vh;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="left">
            <!-- Image as background -->
        </div>
        <div class="right">
            <div style="position: absolute; top: 20px; right: 30px;">
                <!-- Button that takes you back to admin panel -->
                <a href="adminpannel.php" class="btn-back">← Back to Admin Panel</a>
            </div>
            <form class="form-box" action="" method="POST" autocomplete="off">
                <h2>Add Employee</h2>
                <label for="username">User Name</label>
                <input type="text" name="username" id="username" required autocomplete="off">

                <label for="email">User EmailID</label>
                <input type="text" name="email" id="email" required autocomplete="off">

                <label for="password">User Password</label>
                <input type="password" name="password" id="password" required autocomplete="off">

                <label for="role">Role</label>
                <select name="role" id="role" required>
                    <option value="employee" selected>Employee</option>
                    <option value="admin">Admin</option>
                </select>

                <button type="submit">Add Employee</button>
            </form>
        </div>
    </div>

</body>

</html>