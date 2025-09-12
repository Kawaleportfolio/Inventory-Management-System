<?php
include '../partials/auth_check.php';
include '../partials/dbconnect.php';

// Set today's date
$today_date = date("Y-m-d");
// $today_date = "2025-07-25";

// Get employee ID from session
$e_id = $_SESSION['u_id'];

// Fetch today's bills for logged-in employee
$query = "SELECT * FROM bill_master bm WHERE bm.employee_id = $e_id AND bm.bill_date = '$today_date'";
$bills = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Today’s Billing History</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap + DataTables CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        body {
            background: rgb(213, 214, 218);
            padding: 20px;
        }
        .btn-back {
            text-decoration: none;
            background: linear-gradient(to right, #007bff, #0056b3);
            color: white;
            padding: 10px 18px;
            border-radius: 5px;
            font-weight: bold;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.15);
            transition: background 0.3s ease;
        }
        .btn-back:hover {
            background: linear-gradient(to right, #0056b3, #003f7f);
        }
        .card {
            margin-bottom: 20px;
        }
        .item-table th, .item-table td {
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container bg-white p-4 rounded shadow">
    <!-- Back Button -->
    <div class="text-end mt-3">
        <a href="employeepannel.php" class="btn-back">← Back to Panel</a>
    </div>

    <h3 class="text-center mb-4">🧾 Billing History for Today (<?= $today_date ?>)</h3>

    <!-- Bill List -->
    <?php
    if ($bills && mysqli_num_rows($bills) > 0) {
        $i = 1;
        while ($bill = mysqli_fetch_assoc($bills)) {
            $bill_id = $bill['bill_id'];

            // Fetch bill items
            $items = mysqli_query($con, "SELECT * FROM bill_items WHERE bill_id = $bill_id");
    ?>
    <div class="card">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between flex-wrap">
                <div><strong>#<?= $i++ ?> | Bill ID: <?= $bill['bill_id'] ?></strong></div>
                <div><?= $bill['bill_date'] ?> | <?= date('H:i:s', strtotime($bill['created_at'])) ?></div>
                <div><strong>Total: ₹<?= $bill['total_amount'] ?></strong></div>
            </div>
        </div>
        <div class="card-body">
            <p><strong>Customer:</strong> <?= htmlspecialchars($bill['customer_name']) ?> | <strong>Mobile:</strong> <?= htmlspecialchars($bill['c_mobile']) ?></p>

            <div class="table-responsive">
                <table class="table table-sm table-bordered item-table">
                    <thead class="table-light">
                        <tr>
                            <th>Sr No.</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price/Unit</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 1;
                        while ($item = mysqli_fetch_assoc($items)) {
                        ?>
                        <tr>
                            <td><?= $j++ ?></td>
                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>₹<?= $item['price_per_unit'] ?></td>
                            <td>₹<?= $item['total_price'] ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
        }
    } else {
        echo '<div class="alert alert-warning text-center mt-4" role="alert">
            😐 No bills were generated today!
        </div>';
    }
    ?>
</div>

<!-- JS CDN -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
