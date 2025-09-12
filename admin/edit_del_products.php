<?php
include '../partials/dbconnect.php';
include '../partials/auth_check.php';

// Fetch categories for the modal form
$category_query = mysqli_query($con, "SELECT category_id, category_name FROM categories");
$category_options = "";
while ($cat = mysqli_fetch_assoc($category_query)) {
    $category_options .= "<option value='{$cat['category_id']}'>{$cat['category_name']}</option>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Users Data Table</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body { background: rgb(213, 214, 218); font-family: 'Segoe UI', sans-serif; padding: 20px; }
        #usersTable_wrapper { background: white; padding: 20px; border-radius: 10px; max-width: 95%; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .btn-action { margin: 0 5px; }

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
    </style>
</head>
<body>
    <div class="right">
    <div style="position: absolute; top: 20px; right: 30px;">
        <a href="adminpannel.php" class="btn-back">← Back to Admin Panel</a>
    </div>
</div>

<h2 class="text-center mb-4">Product List</h2>

<table id="usersTable" class="display nowrap" style="width:100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Product Name</th>
            <th>Company</th>
            <th>Category</th>
            <th>Cost Price</th>
            <th>Selling Price</th>
            <th>Barcode</th>
            <th>Operations</th>
        </tr>
    </thead>
</table>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm">
                <div class="modal-body row g-3 p-3">
                    <input type="hidden" name="p_id" id="edit_id">
                    <div class="col-md-6">
                        <label>Product Name</label>
                        <input type="text" name="product_name" id="edit_product_name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Brand Name</label>
                        <input type="text" name="brand_name" id="edit_brand_name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Category</label>
                        <select name="category_id" id="edit_category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            <?= $category_options ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Cost Price</label>
                        <input type="number" step="0.01" name="cost_price" id="edit_cost_price" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Selling Price</label>
                        <input type="number" step="0.01" name="selling_price" id="edit_selling_price" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    let table = $('#usersTable').DataTable({
        dom: 'Bfrtip',
        buttons: ['excelHtml5', 'pdfHtml5'],
        ajax: 'fetch_products.php',
        columns: [
            { data: null, render: (_, __, ___, meta) => meta.row + 1 },
            { data: 'p_name' },
            { data: 'company_name' },
            { data: 'category' },
            { data: 'cost_price' },
            { data: 'selling_price' },
            { data: 'barcode' },
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <button class='btn btn-sm btn-warning btn-action' onclick='editProduct(${JSON.stringify(row)})'>Edit</button>
                        <button class='btn btn-sm btn-danger btn-action' onclick='deleteProduct(${row.p_id})'>Delete</button>
                    `;
                }
            }
        ]
    });

    window.editProduct = function (row) {
        $('#edit_id').val(row.p_id);
        $('#edit_product_name').val(row.p_name);
        $('#edit_brand_name').val(row.company_name);
        $('#edit_category_id').val(row.category_id);
        $('#edit_cost_price').val(row.cost_price);
        $('#edit_selling_price').val(row.selling_price);
        new bootstrap.Modal(document.getElementById('editModal')).show();
    };

    $('#editForm').submit(function (e) {
        e.preventDefault();
        $.post('update_product.php', $(this).serialize(), function (res) {
            alert('Product updated successfully!');
            $('#editModal').modal('hide');
            table.ajax.reload();
        });
    });

    window.deleteProduct = function (id) {
        if (confirm('Are you sure to delete this product?')) {
            $.post('delete_product.php', { id }, function (res) {
                alert('Product deleted successfully!');
                table.ajax.reload();
            });
        }
    }
});
</script>
</body>
</html>
