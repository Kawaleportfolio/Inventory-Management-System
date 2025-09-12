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

    html, body {
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
      top: 0; left: 0;
      width: 100%; height: 100%;
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
      text-shadow: 2px 2px 5px rgba(0,0,0,0.7);
      z-index: 2;
    }

    .container-box {
      height: 100vh;
      display: flex;
      justify-content: flex-end;
      align-items: center;
      padding-right: 5%;
      padding-left: 5%; /* Add padding to allow shift left */
      z-index: 2;
      position: relative;
    }

    .login-card {
      background: rgba(255, 255, 255, 0.95);
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 0 20px rgba(0,0,0,0.4);
      width: 100%;
      max-width: 400px;
      margin-right: 30px; /* Move 30px to left */
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
      <h4 class="text-center mb-4">Login to Continue</h4>
      <form action="partials/handlelogin.php" method="POST">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-dark w-100">Login</button>
      </form>
      <?php
      if (isset($_GET['loginsuccess'])) {
        if ($_GET['loginsuccess'] == "false") {
            echo '<p style="color:red" >Invalid username or password. Please try again.</p>';
        }
      }
    ?>
      <!-- <p style="color:red" >Creditionals Not Match Try again</p> -->
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
