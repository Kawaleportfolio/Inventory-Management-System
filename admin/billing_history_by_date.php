<?php
include '../partials/auth_check.php';
include '../partials/dbconnect.php';

// $e_id = $_SESSION['u_id'];

// Default filter
$where = "";

// If filtered by date
if (isset($_GET['from']) && isset($_GET['to']) && !empty($_GET['from']) && !empty($_GET['to'])) {
    $from = $_GET['from'];
    $to = $_GET['to'];
    $where = "WHERE bm.bill_date BETWEEN '$from' AND '$to'";
}

// Fetch purchases
$query = "SELECT * FROM bill_master bm $where ORDER BY bm.bill_date DESC";
$bill_arr = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sale History</title>
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
    <!-- Back to amdin Panel Button -->
        <div class="text-end mt-3">
            <a href="adminpannel.php" class="btn-back">← Back to Panel</a>
        </div>
    <h3 class="text-center mb-4">Billing History</h3>

    <!-- Filter Form -->
    <form method="get" class="row g-3 mb-4">
        <div class="col-md-4">
            <label for="from" class="form-label">From Date</label>
            <input type="date" name="from" id="from" class="form-control" value="<?= $_GET['from'] ?? '' ?>">
        </div>
        <div class="col-md-4">
            <label for="to" class="form-label">To Date</label>
            <input type="date" name="to" id="to" class="form-control" value="<?= $_GET['to'] ?? '' ?>">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <!-- purchases List -->
    <?php
    if ($bill_arr && mysqli_num_rows($bill_arr) > 0) {
    $i=1;
    while ($bill = mysqli_fetch_assoc($bill_arr)) {
        $bill_id = $bill['bill_id'];

        // Fetch Sold items
        $items = mysqli_query($con, "SELECT * FROM bill_items WHERE bill_id = $bill_id");
        $Sold_item=mysqli_num_rows($items);
        // $supp = mysqli_fetch_assoc(mysqli_query($con, "SELECT s.supplier_name, s.phone, s.email FROM purchases p JOIN suppliers s ON p.supplier_id = s.supplier_id WHERE p.purchase_id = $pur_id"));

    ?>
    <div class="card">
        <div class="card-header bg-primary text-white cart_header">
            <div class="d-flex justify-content-between">
                #<?= $i++ ?> <div><strong>Employee Name:</strong> <?= htmlspecialchars($bill['employee_name']) ?> | <strong>products_sold: </strong><?= $Sold_item ?> | <strong>Billing Date: </strong><?= $bill['bill_date'] ?> </div>
                <div><strong>Total: ₹<?= $bill['total_amount'] ?></strong></div>
            </div>
        </div>
        <div class="card-body card_body" style="display: none;">
            <p><strong>Bill_ID: <?= $bill_id ?></strong> <br> <strong>Customer Name: </strong> <?= htmlspecialchars($bill['customer_name']) ?> | <strong>Customer Mobile Number: </strong> <?= htmlspecialchars($bill['c_mobile']) ?> </p> 

            <div class="table-responsive">
                <table class="table table-sm table-bordered item-table">
                    <thead class="table-light">
                        <tr>
                            <th>Sr No.</th>
                            <th>Product Name</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total Price</th>
                            <th>By Sale</th>
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
                            <td><?= htmlspecialchars($bill['employee_name']) ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php }
    }  else {
        echo '<div class="alert alert-warning text-center mt-4" role="alert">
            😐 No Billing were generated!
        </div>';
    }?>

</div>

<!-- JS CDN -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery Library -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
    $('.cart_header').click(function(){
        $(this).next('.card_body').slideToggle();
    });
});
</script>

</body>
</html>
