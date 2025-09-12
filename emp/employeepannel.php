<?php
include '../partials/dbconnect.php';
include '../partials/auth_check.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Employee Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: rgb(213, 214, 218);
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      overflow-x: hidden;
    }

    .admin-panel {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            /* font-size: 36px; */
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 2px;
            background: linear-gradient(90deg, #e64d4dff, #4870b9ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

    .sidebar {
      width: 280px;
      height: 100vh;
      position: fixed;
      top: 56px;
      left: 0;
      background: linear-gradient(to bottom right, #4e54c8, #8f94fb);
      padding: 20px;
      color: white;
      z-index: 1000;
      transition: transform 0.3s ease-in-out;
    }

    .sidebar.hide {
      transform: translateX(-100%);
    }

    .sidebar h5 {
      font-size: 18px;
      margin-top: 20px;
      cursor: pointer;
      color: #ffebcd;
    }

    .sidebar ul {
      list-style: none;
      padding-left: 15px;
      margin-top: 10px;
    }

    .sidebar a {
      color: #ffffff;
      text-decoration: none;
      display: block;
      padding: 6px 0;
    }

    .sidebar a:hover {
      text-decoration: underline;
    }

    .main-content {
      margin-left: 300px;
      padding: 20px;
      transition: margin-left 0.3s ease-in-out;
    }

    .main-content.full {
      margin-left: 0;
    }

    .navbar {
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1050;
      background: linear-gradient(90deg, #4e54c8, #8f94fb);
    }

    .profile-pic {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      cursor: pointer;
    }

    .toggle-btn {
      background: none;
      border: none;
      font-size: 24px;
      color: white;
    }

    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
      }

      .sidebar.show {
        transform: translateX(0);
      }

      .main-content {
        margin-left: 0;
        padding-top: 80px;
      }
    }
  </style>
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg px-4 py-2">
    <div class="container-fluid">
      <button class="toggle-btn me-3 d-lg-none" onclick="toggleSidebar()">☰</button>
      <span class="navbar-brand mb-0 h2 text-white">Welcome <?= $_SESSION['username'] ?> (Employee ID:- <?= $_SESSION['u_id'] ?> )</span>
      <div class="d-flex align-items-center ms-auto gap-3">
        <div class="dropdown">
          <img src="../assets/images/default_user.png" class="profile-pic dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#">View Profile</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item text-danger" href="../partials/logout.php">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <h5 onclick="toggleMenu(this, 'menu1')">👉 Billing</h5>
    <ul class="menu-link" id="menu1" style="display: none;">
      <li><a href="billing_scan.php">Scan Barcode & Bill</a></li>
      <li><a href="billing_history.php">Billing History</a></li>
      <li><a href="today_bill_history.php">Todays Billing History</a></li>
    </ul>

    <h5 onclick="toggleMenu(this, 'menu2')">👉 Stock</h5>
    <ul class="menu-link" id="menu2" style="display: none;">
      <li><a href="view_stock.php">View Stock Only</a></li>
    </ul>

    <!-- <button class="btn btn-light text-success fw-semibold shadow px-3 py-2 border-success">👥 Attended Customers</button> -->
  </div>

  <!-- Main Content -->
  <div class="main-content mt-5" id="mainContent">
    <div class="container-fluid">
      <center>
        <h2 class="admin-panel">Employee Panel</h2>
      </center>
      <p class="text-muted">Hello! Please use the sidebar to perform your assigned actions like billing or viewing stock. For any issues, contact admin or developer.</p>

      <div class="alert alert-info">
        👀 You are under admin monitoring. Please work responsibly.
      </div>
    </div>
  </div>
  <div class="main-content">
    <?php
    if (isset($_GET['month']) && !empty(trim($_GET['month']))) {
      $current_month = $_GET['month'];
      $currentMonthName = $_GET['month'];
    } else {
      $current_month = date('Y-m');
      $currentMonthName = date('Y-m');
    }

    $formatted_month = DateTime::createFromFormat('Y-m', $current_month)->format('F Y');
    ?>
    <!-- <h5 class="text-muted mb-3">📊 Showing data for: <strong></strong></h5> -->
    <h5 class="text-muted mb-3 d-flex align-items-center">
      📊 Showing data for:
      <form method="GET" class="d-inline-block ms-2">
        <input type="month" name="month" value="<?= htmlspecialchars($current_month) ?>" class="form-control form-control-sm d-inline-block" style="width: auto;" onchange="this.form.submit()">
      </form>
    </h5>



    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-2 g-3">
      <!-- Monthly Sales -->
      <div class="col">
        <div class="card border-success shadow-sm">
          <div class="card-body">
            <h6 class="card-title text-success">📦 Monthly Sales</h6>
            <?php
            $e_id = $_SESSION['u_id'];
            $sales = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS count FROM bill_master WHERE employee_id = $e_id and DATE_FORMAT(bill_date, '%Y-%m') = '$currentMonthName'"));
            $monthly_sales = $sales['count'] ?? 0;
            ?>
            <p class="card-text fs-5 fw-bold"><?= $monthly_sales ?> Bills</p>
            <p class="text-muted small mb-0">Bills generated this month.</p>
          </div>
        </div>
      </div>

      <!-- Monthly Sale Amount -->
      <div class="col">
        <div class="card border-warning shadow-sm">
          <div class="card-body">
            <h6 class="card-title text-warning">💰 Monthly Sale Amount</h6>
            <?php
            $sale_amt = mysqli_fetch_assoc(mysqli_query($con, "SELECT SUM(total_amount) AS total FROM bill_master WHERE employee_id = $e_id and DATE_FORMAT(bill_date, '%Y-%m') = '$currentMonthName'"));
            $monthly_sale_amt = $sale_amt['total'] ?? 0;
            ?>
            <p class="card-text fs-5 fw-bold">₹ <?= number_format($monthly_sale_amt, 2) ?></p>
            <p class="text-muted small mb-0">Revenue from sales this month.</p>
          </div>
        </div>
      </div>
    </div>

    <div class="container mt-4">
      <div class="card border-success shadow-sm">
        <div class="card-body">
          <h3 class="card-title text-success text-center mb-4">📈 Today's Sales Overview</h3>
          <div class="row text-center text-md-start">
            <!-- Left Column -->
            <?php
            $today_date = date('Y-m-d');
            $today_sales = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS count FROM bill_master WHERE employee_id = $e_id and bill_date = '$today_date'"));
            $today_sales = $today_sales['count'] ?? 0;
            ?>
            <div class="col-md-4 mb-3 mb-md-0">
              <label class="text-muted">📦 Today's Sales</label>
              <p class="card-text fs-5 fw-bold"><?= $today_sales ?> Bills</p>
            </div>

            <!-- Middle Column -->
            <?php
            $sale_amt_today = mysqli_fetch_assoc(mysqli_query($con, "SELECT SUM(total_amount) AS total FROM bill_master WHERE employee_id = $e_id and bill_date = '$today_date'"));
            $today_sale_amt = $sale_amt_today['total'] ?? 0;
            ?>
            <div class="col-md-4 mb-3 mb-md-0 d-flex flex-column align-items-md-center">
              <label class="text-muted">💰 Today's Sale Amount</label>
              <p class="card-text fs-5 fw-bold">₹ <?= $today_sale_amt ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>



    <p class="mt-3">Select a section from the left menu to continue.</p>
  </div>



  <!-- JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function toggleMenu(header, id) {
      const menu = document.getElementById(id);
      const isVisible = menu.style.display === "block";

      // Toggle display
      menu.style.display = isVisible ? "none" : "block";

      // Toggle arrow icon
      if (!isVisible) {
        header.innerHTML = header.innerHTML.replace("👉", "👇");
      } else {
        header.innerHTML = header.innerHTML.replace("👇", "👉");
      }
    }

    function toggleSidebar() {
      document.getElementById("sidebar").classList.toggle("show");
      document.getElementById("mainContent").classList.toggle("full");
    }
  </script>
</body>

</html>