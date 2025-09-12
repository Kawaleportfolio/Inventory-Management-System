<?php
    include '../partials/dbconnect.php';
    include '../partials/auth_check.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Users Data Table</title>
    <!-- Include jQuery and DataTables CSS/JS -->
     <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        .delete-btn {
            color: red;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 18px;
        }

        .delete-btn:hover {
            text-decoration: underline;
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
    </style>
</head>
<body>
<div class="right">
            <div style="position: absolute; top: 20px; right: 30px;">
                <!-- Button that takes you back to admin panel -->
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
            <th>Delete</th>
        </tr>
    </thead>
</table>

<script>
$(document).ready(function() {
    var table = $('#usersTable').DataTable({
        "ajax": "fetch_users.php",
        "columns": [
            {
                "data": null,
                "title": "ID",
                "render": function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            { "data": "u_id" },
            { "data": "u_name" },
            { "data": "u_email" },
            { "data": "password" },
            { "data": "role" },
            { "data": "join_date" },
            {
                "data": null,
                "render": function (data, type, row) {
                    return '<button class="delete-btn" data-id="' + row.u_id + '">🗑️</button>';
                }
            }
        ]
    });

    $('#usersTable').on('click', '.delete-btn', function() {
        var u_id = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this user deletion!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "delete_user.php",
                    type: "POST",
                    data: { u_id: u_id },
                    success: function(response) {
                        if (response.trim() === "success") {
                            Swal.fire(
                                'Deleted!',
                                'User has been deleted.',
                                'success'
                            );
                            table.ajax.reload();
                        } else {
                            Swal.fire(
                                'Failed!',
                                'There was an error deleting the user.',
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'AJAX request failed.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
</script>

</body>
</html>
