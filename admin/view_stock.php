<?php
include '../partials/auth_check.php';
include '../partials/dbconnect.php';

$sql1 = mysqli_query($con, "SELECT product_name, product_qty, UpdatedDateTime FROM product_stock;");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Stock - Inventory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <style>
        body {
            background: rgb(213, 214, 218);
        }

        .container {
            background-color: white;
            padding: 30px;
            margin-top: 40px;
            border-radius: 12px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
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

        h2 {
            margin-bottom: 25px;
            font-weight: bold;
        }

        table.dataTable thead th {
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Back to emp Panel Button -->
        <div class="text-end mt-3">
            <a href="adminpannel.php" class="btn-back">← Back to Panel</a>
        </div>
        <h2 class="text-center">Current Product Stock Overview</h2>

        <table id="stockTable" class="display nowrap table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>Sr No.</th>
                    <th>Product Name</th>
                    <th>Product Quantity</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                while ($row = mysqli_fetch_assoc($sql1)) {
                ?>
                    <tr>
                        <td style="color: <?= ($row['product_qty'] < 100) ? 'red' : 'black' ?>;"><?= $i++ ?></td>
                        <td style="color: <?= ($row['product_qty'] < 100) ? 'red' : 'black' ?>;"><?= htmlspecialchars($row['product_name']) ?></td>
                        <td style="color: <?= ($row['product_qty'] < 100) ? 'red' : 'black' ?>;">
                            <?= htmlspecialchars($row['product_qty']) ?>
                        </td>
                        <td style="color: <?= ($row['product_qty'] < 100) ? 'red' : 'black' ?>;"><?= htmlspecialchars($row['UpdatedDateTime']) ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script>
        $(document).ready(function() {
            $('#stockTable').DataTable({
                dom: 'Bfrtip',
                buttons: ['excelHtml5', 'pdfHtml5', 'print'],
                responsive: true
            });
        });
    </script>

</body>

</html>