<?php
include 'partials/dbconnect.php';
session_start();

// if (isset($_GET['loginsuccess'])) {
//     if ($_GET['loginsuccess'] == "true") {
//         echo '<div class="alert alert-success alert-dismissible fade show my-0" role="alert">
//                 <strong>Login successful!</strong> Welcome to the iDiscuss.
//                 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
//               </div>';
//     } elseif ($_GET['loginsuccess'] == "false") {
//         echo '<div class="alert alert-danger alert-dismissible fade show my-0" role="alert">
//                 <strong>Access denied!</strong> Please verify your login details.
//                 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
//               </div>';
//     }
// }


?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Inventory Management System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
    }

    html,
    body {
      margin: 0;
      padding: 0;
      height: 100%;
      overflow: hidden;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: url('assets/images/stationery_bg.jpg') no-repeat center center fixed;
      background-size: cover;
    }

    .overlay {
      background: rgba(0, 0, 0, 0.6);
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 0;
    }

    .heading {
      position: absolute;
      top: 30px;
      width: 100%;
      text-align: center;
      color: white;
      font-size: 2.5rem;
      font-weight: 700;
      text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
      z-index: 2;
    }

    .container-box {
      height: 100vh;
      display: flex;
      justify-content: flex-end;
      align-items: center;
      padding-right: 5%;
      padding-left: 5%;
      /* Add padding to allow shift left */
      z-index: 2;
      position: relative;
    }

    .login-card {
      background: rgba(255, 255, 255, 0.95);
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
      width: 100%;
      max-width: 400px;
      margin-right: 30px;
      /* Move 30px to left */
    }

    .form-control:focus {
      box-shadow: none;
      border-color: #343a40;
    }

    @media (max-width: 768px) {
      .heading {
        font-size: 2rem;
      }

      .container-box {
        justify-content: center;
        padding: 1rem;
      }

      .login-card {
        margin: 0;
      }
    }

    @media (max-width: 480px) {
      .heading {
        font-size: 1.5rem;
        top: 20px;
      }

      .login-card {
        padding: 25px;
      }
    }
  </style>
</head>

<body>

  <div class="overlay"></div>

  <div class="heading">Inventory Management System</div>

  <div class="container-box">
    <div class="login-card">

      <form action="partials/handlelogin.php" id="loginForm" method="POST">
        <h4 class="text-center mb-4">Login to Continue</h4>
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-dark w-100">Login</button>
        <a class="btn btn-dark w-100 mt-2" id="forgotLink">Forget Password</a>
      </form>

      <form action="partials/handleforget.php" id="forgotForm" method="POST" style="display: none;">
        <h4 class="text-center mb-4">Forget Password</h4>

        <input type="hidden" name="step" value="verify">

        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" id="f_username" name="username" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Email ID</label>
          <input type="email" id="f_email" name="email" class="form-control" required>
          <small id="empty_msg" class="text-danger"></small>
        </div>

        <!-- Password field will come here -->
        <div class="mb-3" id="password_section"></div>

        <button type="submit" class="btn btn-dark w-100" id="updateBtn" style="display:none;">
          Update Password
        </button>

        <a class="btn btn-dark w-100 mt-2" id="backToLogin">Back to Login</a>
      </form>
      <?php
      if (isset($_GET['loginsuccess'])) {
        if ($_GET['loginsuccess'] == "false") {
          echo '<p style="color:red" >Invalid username or password. Please try again.</p>';
        }
      }
      if (isset($_GET['updatepassword'])) {
        if ($_GET['updatepassword'] == "true") {
          echo '<p style="color:green" >Password Changed successfully.</p>';
        }
      }
      ?>
      <!-- <p style="color:red" >Creditionals Not Match Try again</p> -->
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Jquery  -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $('#f_email').on('blur', function() {

      let f_email = $('#f_email').val().trim();
      let f_username = $('#f_username').val().trim();

      if (f_username === "") {
        $('#empty_msg').text('Please enter Username first');
        return;
      }

      if (f_email === "") {
        $('#empty_msg').text('Please enter Email ID');
        return;
      }

      $('#empty_msg').text('');

      $.ajax({
        url: 'partials/check_user_email.php',
        type: 'POST',
        data: {
          username: f_username,
          email: f_email
        },
        success: function(response) {

          if (response === 'success') {

            $('#empty_msg').text('');

            // Show new password field
            $('#password_section').html(`
          <label class="form-label">New Password</label>
          <input type="password" name="new_password" class="form-control" required>
        `);

            $('#updateBtn').show();

          } else {
            $('#password_section').html('');
            $('#updateBtn').hide();
            $('#empty_msg').text(response);
          }
        }
      });
    });


    $(document).ready(function() {

      $("#forgotLink").click(function() {
        $("#loginForm").fadeOut(200, function() {
          $("#forgotForm").fadeIn(200);
        });
      });

      $("#backToLogin").click(function() {
        $("#forgotForm").fadeOut(200, function() {
          $("#loginForm").fadeIn(200);
        });
      });

    });
  </script>
</body>

</html>