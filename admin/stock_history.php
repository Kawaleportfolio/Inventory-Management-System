<?php
include '../partials/auth_check.php';
include '../partials/dbconnect.php';

$where        = "p.status = 'Delivered'";
$filterLabel  = "All Inward History";

/* ----- date filter ----- */
if (!empty($_GET['from']) && !empty($_GET['to'])) {
    $from = $_GET['from'];
    $to   = $_GET['to'];
    $where       .= " AND p.delivered_date BETWEEN '$from' AND '$to'";
    $filterLabel  = "Inward from $from to $to";
}

/* ----- view toggle ----- */
$view      = $_GET['view'] ?? 'detailed';
$isSummary = $view === 'summary';

/* ----- query ----- */
if ($isSummary) {
    $sql = "
        SELECT
            pr.p_name AS product_name,
            SUM(pi.quantity) AS total_inserted,
            MAX(ps.product_qty) AS current_qty
        FROM purchase_items pi
        JOIN purchases p ON pi.purchase_id = p.purchase_id
        JOIN products pr ON pi.p_id = pr.p_id
        LEFT JOIN product_stock ps ON pr.p_id = ps.p_id
        WHERE $where
        GROUP BY pr.p_id, pr.p_name
        ORDER BY pr.p_name
    ";
} else {
    $sql = "
        SELECT
            pr.p_name AS product_name,
            pi.quantity AS inserted_qty,
            ps.product_qty AS current_qty,
            p.delivered_date
        FROM purchase_items pi
        JOIN purchases p ON pi.purchase_id = p.purchase_id
        JOIN products pr ON pi.p_id = pr.p_id
        LEFT JOIN product_stock ps ON pr.p_id = ps.p_id
        WHERE $where
        ORDER BY p.delivered_date DESC
    ";
}

$result = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
<html>

<head>
    <title>📦 Stock Inward History</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: rgb(213, 214, 218);
        }

        .card {
            margin-top: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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

        .table thead {
            background-color: #343a40;
            color: white;
        }

        .table-striped>tbody>tr:nth-of-type(odd) {
            background-color: #f2f2f2;
        }

        .btn-primary {
            background-color: #4b6cb7;
            border-color: #4b6cb7;
        }

        .btn-primary:hover {
            background-color: #3a56a3;
        }
    </style>
</head>

<body>
    <div class="container py-4">
        <!-- Back to amdin Panel Button -->
        <div class="text-end mt-3">
            <a href="adminpannel.php" class="btn-back">← Back to Panel</a>
        </div>

        <div class="card p-4">
            <h2 class="mb-4 text-center">📋 Stock Inward History</h2>

            <!-- Filter Form -->
            <form method="get" class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">From Date</label>
                    <input type="date" class="form-control" name="from" value="<?= $_GET['from'] ?? '' ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">To Date</label>
                    <input type="date" class="form-control" name="to" value="<?= $_GET['to'] ?? '' ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">🔍 Filter</button>
                    <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-outline-secondary">🔄 Reset</a>
                </div>
            </form>

            <!-- Toggle View -->
            <div class="mb-3 text-end">
                <?php if ($isSummary): ?>
                    <a href="?view=detailed<?= isset($_GET['from']) ? '&from=' . $_GET['from'] . '&to=' . $_GET['to'] : '' ?>"
                        class="btn btn-outline-dark">⬅ Back to Detailed View</a>
                <?php else: ?>
                    <a href="?view=summary<?= isset($_GET['from']) ? '&from=' . $_GET['from'] . '&to=' . $_GET['to'] : '' ?>"
                        class="btn btn-dark">🔢 View Total Inserted Qty by Product</a>
                <?php endif; ?>
            </div>

            <!-- Filter Label -->
            <h6 class="text-muted mb-3">
                Showing: <strong><?= $filterLabel ?></strong> — <?= $isSummary ? 'Summary View' : 'Detailed View' ?>
            </h6>

            <!-- Data Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center">
                    <thead>
                        <tr>
                            <?php if ($isSummary): ?>
                                <th>🏷️ Product Name</th>
                                <th>➕ Total Inserted Qty</th>
                                <th>📦 Current Stock</th>
                            <?php else: ?>
                                <th>📅 Inward Date</th>
                                <th>🏷️ Product Name</th>
                                <th>➕ Inserted Qty</th>
                                <th>📦 Current Stock</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result)): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <?php if ($isSummary): ?>
                                        <td><?= $row['product_name'] ?></td>
                                        <td><?= $row['total_inserted'] ?></td>
                                        <td><?= $row['current_qty'] ?></td>
                                    <?php else: ?>
                                        <td><?= date('Y-m-d', strtotime($row['delivered_date'])) ?></td>
                                        <td><?= $row['product_name'] ?></td>
                                        <td><?= $row['inserted_qty'] ?></td>
                                        <td><?= $row['current_qty'] ?></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?= $isSummary ? 3 : 4 ?>" class="text-danger">No records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>