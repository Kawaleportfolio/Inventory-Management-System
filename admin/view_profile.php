<?php
include '../partials/dbconnect.php';
include '../partials/auth_check.php';

$id = (int) $_SESSION['u_id'];   // type cast for safety

$sql = "SELECT u_name, u_email, role FROM users WHERE u_id = $id";
$result = $con->query($sql);

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $username = $user['u_name'];
    $email    = $user['u_email'];
    $rol      = $user['role'];
}

// data insert successfully popup
// session_start();
// popup message
if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    echo "<script>
        window.onload = function() {
            alert('{$msg['text']}');
        };
    </script>";
    unset($_SESSION['msg']);
}

// handle form submit
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $username1 = $_POST['username'];
    $email1    = $_POST['email'];
    $role1     = $_POST['role'];
    $id1 = $_SESSION['u_id'];

    // validation
    if (empty($username1) || empty($email1) || empty($role1)) {
        $_SESSION['msg'] = ['type' => 'error', 'text' => 'All fields are required.'];
        header("Location: view_profile.php");
        exit();
    }

    // ✅ prepared statement (correct way)
    $sql = "UPDATE users SET u_name = ?, u_email = ?, role = ? WHERE u_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssi", $username1, $email1, $role1, $id1);

    if ($stmt->execute()) {
        $_SESSION['msg'] = ['type' => 'success', 'text' => 'Updated successfully.'];
    } else {
        $_SESSION['msg'] = ['type' => 'error', 'text' => 'Data not updated.'];
    }

    header("Location: view_profile.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile</title>
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
            background: url('../assets/images/profile.png') no-repeat center center/contain;
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

        .form-box h1 {
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

        /*  */
        .form-wrapper {
            position: relative;
            width: 100%;
            max-width: 400px;
            perspective: 1000px;
        }

        .form-inner {
            position: relative;
            width: 100%;
            transition: transform 0.7s;
            transform-style: preserve-3d;
        }

        .form-wrapper.flip .form-inner {
            transform: rotateY(180deg);
        }

        .form-box {
            backface-visibility: hidden;
        }

        .form-edit {
            position: absolute;
            top: 0;
            left: 0;
            transform: rotateY(180deg);
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
            <div class="form-wrapper" id="formWrapper">
                <div class="form-inner">

                    <!-- VIEW PROFILE -->
                    <form class="form-box">
                        <h1>Profile</h1>

                        <label>User Name</label>
                        <input type="text" value="<?php echo $username ?>" readonly>

                        <label>User EmailID</label>
                        <input type="text" value="<?php echo $email ?>" readonly>

                        <label>Role</label>
                        <input type="text" value="<?php echo $rol ?>" readonly>

                        <button type="button" onclick="flipForm()">Edit Profile</button>
                    </form>

                    <!-- EDIT PROFILE -->
                    <form class="form-box form-edit" method="POST" action="">
                        <h1>Edit Profile</h1>

                        <label>User Name</label>
                        <input type="text" name="username" value="<?php echo $username ?>">

                        <label>User EmailID</label>
                        <input type="email" name="email" value="<?php echo $email ?>">

                        <label>Role</label>
                        <select name="role" id="role" required>
                            <option value="employee" >Employee</option>
                            <option value="admin" selected>Admin</option>
                        </select>

                        <button type="submit">Update</button>
                        <br><br>
                        <button type="button" onclick="flipForm()">Back to View</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

</body>
<script>
    function flipForm() {
        document.getElementById("formWrapper").classList.toggle("flip");
    }
</script>


</html>