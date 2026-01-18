<?php
include '../partials/dbconnect.php';
include '../partials/auth_check.php';
?>
<!-- Upload on Github -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
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
            margin: 20px 0;
        }

        .sidebar {
            width: 280px;
            height: 100vh;
            position: fixed;
            top: 56px;
            left: 0;
            overflow-y: auto;
            background: linear-gradient(to bottom right, rgb(67, 21, 233), rgb(177, 165, 245));
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

        .sidebar .menu-link {
            display: none;
            list-style: none;
            padding-left: 15px;
            margin-top: 10px;
        }

        .sidebar .menu-link a {
            color: #ffffff;
            text-decoration: none;
            display: block;
            padding: 5px 0;
        }

        .sidebar .menu-link a:hover {
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
            object-fit: cover;
            border-radius: 50%;
            cursor: pointer;
        }

        .toggle-btn {
            background: none;
            border: none;
            font-size: 24px;
            color: white;
        }

        .card-body::-webkit-scrollbar {
            width: 6px;
        }

        .card-body::-webkit-scrollbar-thumb {
            background-color: rgba(220, 53, 69, 0.6);
            border-radius: 4px;
        }

        @media (max-width: 768px) {
            .sidebar {
                top: 56px;
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding-top: 80px;
            }

            .main-content .d-flex {
                flex-direction: column;
            }

            .main-content>.d-flex>div {
                width: 100% !important;
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg px-4 py-2">
        <div class="container-fluid">
            <button class="toggle-btn me-3 d-lg-none" onclick="toggleSidebar()">☰</button>
            <span class="navbar-brand mb-0 h2 text-white">Welcome <?= $_SESSION['username'] ?></span>
            <div class="d-flex align-items-center ms-auto gap-4">
                <a href="add_product.php" class="btn btn-success">Add Product</a>
                <a href="generate_barcode.php" class="btn btn-primary">Generate Barcode</a>
                <div class="dropdown">
                    <img src="../assets/images/default_user.png" class="profile-pic dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="view_profile.php">View Profile</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
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
        <div class="sidebar-toggle">
            <h5 onclick="toggleMenu('menu1')">👉 Product Management</h5>
            <ul class="menu-link" id="menu1">
                <li><a href="add_category.php">Add Category</a></li>
                <li><a href="add_product.php">Add New Product</a></li>
                <li><a href="edit_del_products.php">Edit / Delete Product</a></li>
                <li><a href="display_products.php">View Products</a></li>
            </ul>

            <h5 onclick="toggleMenu('menu2')">👉 Purchase Management</h5>
            <ul class="menu-link" id="menu2">
                <li><a href="purchase_form.php">New Purchase</a></li>
                <li><a href="add_supplier.php">Supplier Management</a></li>
                <li><a href="pending_deli_order.php">Pending Delivery Orders</a></li>
                <li><a href="purchase_history.php">All Purchase History</a></li>
            </ul>

            <h5 onclick="toggleMenu('menu3')">👉 Sales / Issued Stock</h5>
            <ul class="menu-link" id="menu3">
                <li><a href="billing_history_by_date.php">View Sales History By Date</a></li>
                <li><a href="billing_history_by_emp.php">View Sales History By Employee</a></li>
                <!-- <li><a href="#">Date Range Filter</a></li> -->
            </ul>

            <h5 onclick="toggleMenu('menu4')">👉 Stock Inward</h5>
            <ul class="menu-link" id="menu4">
                <li><a href="view_stock.php">Check Stock</a></li>
                <li><a href="stock_history.php">Stock History</a></li>
            </ul>

            <h5 onclick="toggleMenu('menu5')">👉 Employee Management</h5>
            <ul class="menu-link" id="menu5">
                <li><a href="add_employee.php">Add Employee</a></li>
                <li><a href="del_user.php">Delete Employee</a></li>
                <li><a href="updt_user.php">Edit Employee</a></li>
                <li><a href="employee_list.php">Employee List</a></li>
            </ul>

            <h5 onclick="toggleMenu('menu6')">👉 Reports</h5>
            <ul class="menu-link" id="menu6">
                <li><a href="todays_report.php">Today Report</a></li>
                <li><a href="monthly_report.php">Monthly Report</a></li>
                <li><a href="by_date_report.php">By Date Report</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content mt-5" id="mainContent">
        <div class="d-flex flex-wrap gap-4">

            <!-- Dashboard Section (Left Side) -->
            <div style="flex: 1 1 60%;">
                <center>
                    <h2 class="admin-panel">Admin Panel</h2>
                </center>


                <?php
                // // $currentMonthName = date('Y-m'); // Ex: August 2025
                // $currentMonthName = $_GET['month'] ?? date('Y-m');
                // // $currentMonthName="2025-07";

                // $current_month = $_GET['month'] ?? date('Y-m');
                // if(empty($current_month)){
                //     $current_month=date('Y-m'); 
                // }

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
                    <!-- Monthly Purchase -->
                    <div class="col">
                        <div class="card border-primary shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title text-primary">📥 Monthly Purchases</h6>
                                <?php
                                // $month = date('Y-m');
                                // $month = "2025-07";
                                $purchase = mysqli_fetch_assoc(mysqli_query($con, "SELECT SUM(total_amount) AS total FROM purchases WHERE status='Delivered' AND DATE_FORMAT(order_date, '%Y-%m') = '$currentMonthName'"));
                                $monthly_purchase = $purchase['total'] ?? 0;
                                ?>
                                <p class="card-text fs-5 fw-bold">₹ <?= number_format($monthly_purchase, 2) ?></p>
                                <p class="text-muted small mb-0">All delivered purchases this month.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Sales -->
                    <div class="col">
                        <div class="card border-success shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title text-success">📦 Monthly Sales</h6>
                                <?php
                                $sales = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS count FROM bill_master WHERE DATE_FORMAT(bill_date, '%Y-%m') = '$currentMonthName'"));
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
                                $sale_amt = mysqli_fetch_assoc(mysqli_query($con, "SELECT SUM(total_amount) AS total FROM bill_master WHERE DATE_FORMAT(bill_date, '%Y-%m') = '$currentMonthName'"));
                                $monthly_sale_amt = $sale_amt['total'] ?? 0;
                                ?>
                                <p class="card-text fs-5 fw-bold">₹ <?= number_format($monthly_sale_amt, 2) ?></p>
                                <p class="text-muted small mb-0">Revenue from sales this month.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Profit -->
                    <div class="col">
                        <div class="card border-danger shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title text-danger">📈 Estimated Profit</h6>
                                <?php
                                $sale_costprice = mysqli_fetch_assoc(mysqli_query($con, "SELECT SUM(p.cost_price * bi.quantity) AS total_costing_value FROM bill_master bm JOIN bill_items bi ON bm.bill_id = bi.bill_id JOIN products p ON bi.p_id = p.p_id WHERE DATE_FORMAT(bm.bill_date, '%Y-%m') = '$currentMonthName'"));
                                $total_cost_price = $sale_costprice['total_costing_value'];
                                $profit = $monthly_sale_amt - $total_cost_price;
                                ?>
                                <p class="card-text fs-5 fw-bold <?= ($profit >= 0) ? 'text-success' : 'text-danger' ?>">
                                    ₹ <?= number_format($profit, 2) ?>
                                </p>
                                <p class="text-muted small mb-0">Estimated: total_cost_price</p>
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
                                $today_sales = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS count FROM bill_master WHERE bill_date = '$today_date'"));
                                $today_sales = $today_sales['count'] ?? 0;
                                ?>
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <label class="text-muted">📦 Today's Sales</label>
                                    <p class="card-text fs-5 fw-bold"><?= $today_sales ?> Bills</p>
                                </div>

                                <!-- Middle Column -->
                                <?php
                                $sale_amt_today = mysqli_fetch_assoc(mysqli_query($con, "SELECT SUM(total_amount) AS total FROM bill_master WHERE bill_date = '$today_date'"));
                                $today_sale_amt = $sale_amt_today['total'] ?? 0;
                                ?>
                                <div class="col-md-4 mb-3 mb-md-0 d-flex flex-column align-items-md-center">
                                    <label class="text-muted">💰 Today's Sale Amount</label>
                                    <p class="card-text fs-5 fw-bold">₹ <?= $today_sale_amt ?></p>
                                </div>

                                <!-- Right Column -->
                                 <?php
                                $today_cost_price = mysqli_fetch_assoc(mysqli_query($con, "SELECT SUM(p.cost_price * bi.quantity) AS total_costing_value FROM bill_master bm JOIN bill_items bi ON bm.bill_id = bi.bill_id JOIN products p ON bi.p_id = p.p_id WHERE bm.bill_date = '$today_date'"));
                                $total_today_cost = $today_cost_price['total_costing_value'];
                                $today_profit = $today_sale_amt - $total_today_cost;
                                ?>
                                <div class="col-md-4 d-flex flex-column align-items-md-end">
                                    <label class="text-muted">📈 Estimated Profit</label>
                                    <p class="card-text fs-5 fw-bold">₹ <?= $today_profit ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <p class="mt-3">Select a section from the left menu to continue.</p>

            </div>

            <!-- 🔴 Alert Box (Right Side) -->
            <div style="flex: 1 1 35%;">
                <div class="card border-danger" style="max-height: 400px; overflow-y: auto;">
                    <div class="card-header bg-danger text-white fw-semibold">
                        🔔 Important Alerts
                    </div>

                    <div class="card-body p-2">

                        <!-- 🔻 Section: Low Stock Alerts -->
                        <h6 class="text-danger fw-bold mb-2 border-bottom pb-1">⚠️ Low Stock Alerts</h6>
                        <?php
                        $products = mysqli_query($con, "SELECT product_name, product_qty FROM product_stock WHERE product_qty <= 90 and product_qty !=0");
                        $hasLowStock = false;
                        while ($row = mysqli_fetch_assoc($products)) {
                            $hasLowStock = true;
                        ?>
                            <div class="alert alert-warning py-2 px-3 mb-2">
                                Low stock for <strong><?= $row['product_name']; ?></strong>. Only <?= $row['product_qty']; ?> units left.
                                <a href="purchase_form.php" class="btn btn-sm btn-outline-dark ms-2">Order Now</a>
                            </div>
                        <?php }
                        if (!$hasLowStock) {
                            echo "<div class='text-muted small'>✅ All stock levels are good.</div>";
                        }
                        ?>

                        <!-- 🔻 Section: Pending Deliveries -->
                        <h6 class="text-primary fw-bold mt-3 mb-2 border-bottom pb-1">📦 Pending Delivery Alerts</h6>
                        <?php
                        $products = mysqli_query($con, "SELECT supplier_id, order_date, expected_delivery_date FROM purchases WHERE status='Pending'");
                        $hasPending = false;
                        while ($row1 = mysqli_fetch_assoc($products)) {
                            $hasPending = true;
                            $s_id = $row1['supplier_id'];
                            $supplier = mysqli_fetch_assoc(mysqli_query($con, "SELECT supplier_name FROM suppliers WHERE supplier_id='$s_id'"));

                            // Calculate days left
                            $order_date = $row1['order_date'];
                            $expected_date = $row1['expected_delivery_date'];
                            $today = date('Y-m-d');
                            $days_left = (strtotime($expected_date) - strtotime($today)) / 86400;
                            $order_date_fmt = date('d M Y', strtotime($order_date));
                            $expected_date_fmt = date('d M Y', strtotime($expected_date));
                        ?>
                            <div class="alert alert-info py-2 px-3 mb-2">
                                Order from <strong><?= $supplier['supplier_name']; ?></strong> placed on <strong><?= $order_date_fmt; ?></strong><br>
                                Expected in <strong><?= $days_left; ?> day<?= $days_left != 1 ? 's' : ''; ?></strong> (by <?= $expected_date_fmt; ?>).
                                <a href="pending_deli_order.php" class="btn btn-sm btn-outline-primary mt-1">View</a>
                            </div>
                        <?php }
                        if (!$hasPending) {
                            echo "<div class='text-muted small'>✅ No pending deliveries.</div>";
                        }
                        ?>

                    </div>
                </div>
            </div>

            <!-- Pie chart for months -->
            <div class="col">
                <div class="card border-danger shadow-sm">
                    <div class="card-body">
                        <center>
                            <h6 class="card-title text-danger">📊 Monthly Business Summary: <?= $currentMonthName ?></h6>
                        </center>
                        <div style="display: flex; align-items: center; justify-content: center;">
                            <!-- Chart on the left -->
                            <div style="width: 150px; height: 150px;">
                                <canvas id="monthlyPieChart" width="150" height="150"></canvas>
                            </div>
                            <!-- Legend on the right -->
                            <div style="margin-left: 60px;">
                                <ul style="list-style: none; padding: 0; margin: 0;">
                                    <li><span style="display:inline-block;width:12px;height:12px;background:#f39c12;margin-right:8px;"></span>Purchase</li>
                                    <li><span style="display:inline-block;width:12px;height:12px;background:#3498db;margin-right:8px;"></span>Sale</li>
                                    <li><span style="display:inline-block;width:12px;height:12px;background:#2ecc71;margin-right:8px;"></span>Profit</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        function toggleMenu(id) {
            const menu = document.getElementById(id);
            const header = menu.previousElementSibling;

            if (header.innerHTML.includes("👉")) {
                header.innerHTML = header.innerHTML.replace("👉", "👇");
            } else {
                header.innerHTML = header.innerHTML.replace("👇", "👉");
            }

            menu.style.display = (menu.style.display === "block") ? "none" : "block";
        }

        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            const content = document.getElementById("mainContent");
            sidebar.classList.toggle("show");
            content.classList.toggle("full");
        }




        // this is pie chart js
        const ctx = document.getElementById('monthlyPieChart').getContext('2d');
        const monthlyPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Purchase', 'Sale', 'Profit'],
                datasets: [{
                    data: [<?= $monthly_purchase ?>, <?= $monthly_sale_amt ?>, <?= $profit ?>],
                    backgroundColor: ['#f39c12', '#3498db', '#2ecc71'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: false,
                plugins: {
                    legend: {
                        display: false // Hide default legend (we’re showing it manually on the right)
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.parsed;
                                return `${label}: ₹${value}`;
                            }
                        }
                    }
                }
            }
        });
    </script>

</body>

</html>