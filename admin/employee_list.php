<?php
    include '../partials/dbconnect.php';
    include '../partials/auth_check.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Users Data Table</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

    <style>
        body {
            background: rgb(213, 214, 218);
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        #usersTable_wrapper {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 95%;
            margin: auto;
        }

        table.dataTable {
            border: 1px solid #ccc;
            width: 100% !important;
        }

        table.dataTable th,
        table.dataTable td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        .btn-back {
            text-decoration: none;
            background-color: #007bff;
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


    </style>
</head>
<body>

<div class="right">
    <div style="position: absolute; top: 20px; right: 30px;">
        <a href="adminpannel.php" class="btn-back">← Back to Admin Panel</a>
    </div>
</div>

<h2>Users List</h2>

<table id="usersTable" class="display">
    <thead>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Password</th>
            <th>Role</th>
            <th>Join Date</th>
        </tr>
    </thead>
</table>

<script>
$(document).ready(function() {
    $('#usersTable').DataTable({
        dom: 'Bfrtip', // Buttons, filtering, table, pagination
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Users Data',
                className: 'buttons-excel'
            },
            {
                extend: 'pdfHtml5',
                title: 'Users Data',
                className: 'buttons-pdf',
                orientation: 'landscape',
                pageSize: 'A4',
            }
        ],
        "ajax": "fetch_users.php",
        "columns": [
            {
                "data": null,
                "render": function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            { "data": "u_id" },
            { "data": "u_name" },
            { "data": "u_email" },
            { "data": "password" },
            { "data": "role" },
            { "data": "join_date" }
        ]
    });
});
</script>

</body>
</html>
