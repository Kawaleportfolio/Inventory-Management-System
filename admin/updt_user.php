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

    <!-- Bootstrap CSS for modal -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Bootstrap JS for modal -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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

        .edit-btn {
            color: #007bff;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 18px;
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
<div style="position: absolute; top: 20px; right: 30px;">
    <a href="adminpannel.php" class="btn-back">← Back to Admin Panel</a>
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
            <th>Edit</th>
        </tr>
    </thead>
</table>

<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editForm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="u_id" id="edit_u_id">
                <div class="mb-3">
                    <label>Name:</label>
                    <input type="text" name="u_name" id="edit_u_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Email:</label>
                    <input type="email" name="u_email" id="edit_u_email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password:</label>
                    <input type="text" name="password" id="edit_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Role:</label>
                    <input type="text" name="role" id="edit_role" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update User</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </form>
  </div>
</div>

<script>
$(document).ready(function() {
    var table = $('#usersTable').DataTable({
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
            { "data": "join_date" },
            {
                "data": null,
                "render": function (data, type, row) {
                    return '<button class="edit-btn" data-id="' + row.u_id + '" data-name="' + row.u_name + '" data-email="' + row.u_email + '" data-password="' + row.password + '" data-role="' + row.role + '">📝</button>';
                }
            }
        ]
    });

    $('#usersTable').on('click', '.edit-btn', function() {
        const btn = $(this);
        $('#edit_u_id').val(btn.data('id'));
        $('#edit_u_name').val(btn.data('name'));
        $('#edit_u_email').val(btn.data('email'));
        $('#edit_password').val(btn.data('password'));
        $('#edit_role').val(btn.data('role'));

        var modal = new bootstrap.Modal(document.getElementById('editModal'));
        modal.show();
    });

    $('#editForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: 'update_user.php',
            type: 'POST',
            data: $('#editForm').serialize(),
            success: function(response) {
                if (response.trim() === 'success') {
                    Swal.fire('Updated!', 'User updated successfully.', 'success');
                    $('#editModal').modal('hide');
                    table.ajax.reload();
                } else {
                    Swal.fire('Error!', 'Failed to update user.', 'error');
                }
            },
            error: function() {
                Swal.fire('Error!', 'AJAX error occurred.', 'error');
            }
        });
    });
});
</script>

</body>
</html>
