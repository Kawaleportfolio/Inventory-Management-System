<?php
// Fetch categories from DB
include '../partials/auth_check.php';
include '../partials/dbconnect.php';
$categories = mysqli_query($con, "SELECT category_id, category_name FROM categories");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Supplier</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <style>
        body {
            background-color: rgb(213, 214, 218);
            font-family: 'Segoe UI', sans-serif;
        }

        .form-box {
            max-width: 1200px;
            margin: 60px auto;
            padding: 40px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: 600;
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

        .btn-submit {
            background: linear-gradient(to right, #28a745, #218838);
            color: white;
            padding: 12px 25px;
            font-size: 18px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            box-shadow: 0px 5px 12px rgba(0, 0, 0, 0.15);
            transition: 0.3s ease;
        }

        .btn-submit:hover {
            background: linear-gradient(to right, #218838, #19692c);
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

        /* Alert msg box */
        .alert-box {
            animation: fadeSlideDown 0.6s ease-in-out;
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        @keyframes fadeSlideDown {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-dismissible .btn-close {
            position: absolute;
            top: 0.75rem;
            right: 1rem;
        }
    </style>
</head>

<body>
    <?php if (isset($_GET['success']) && isset($_GET['name'])): ?>
        <div class="alert alert-success alert-dismissible fade show alert-box position-relative text-center fw-bold shadow-sm mx-auto mt-4" style="max-width: 600px; font-size: 18px;">
            ✅ Supplier <strong><?= htmlspecialchars($_GET['name']) ?></strong> added successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif (isset($_GET['exists']) && isset($_GET['name'])): ?>
        <div class="alert alert-danger alert-dismissible fade show alert-box position-relative text-center fw-bold shadow-sm mx-auto mt-4" style="max-width: 600px; font-size: 18px;">
            ❌ Supplier <strong><?= htmlspecialchars($_GET['name']) ?></strong> already exists. Please try another name.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif (isset($_GET['updated']) && isset($_GET['name'])): ?>
        <div class="alert alert-info alert-dismissible fade show alert-box position-relative text-center fw-bold shadow-sm mx-auto mt-4" style="max-width: 600px; font-size: 18px;">
            ✏️ Supplier <strong><?= htmlspecialchars($_GET['name']) ?></strong> updated successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <div class="container mt-4">

        <!-- Back to Admin Panel Button -->
        <div class="text-end mt-3">
            <a href="adminpannel.php" class="btn-back">← Back to Admin Panel</a>
        </div><br><br>

        <div class="d-flex justify-content-between align-items-center">
            <h1 class="text-primary">Supplier Management</h2>
                <button class="btn btn-success" id="viewSuppliersBtn">View Suppliers</button>
        </div>

        <div class="form-box mt-4">
            <form action="insert_supplier.php" method="POST">
                <h1 class="text-primary text-center">Add Supplier</h1>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Supplier Name</label>
                        <input type="text" name="supplier_name" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company_name" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Category Supplied</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- Select Category --</option>
                            <?php while ($cat = mysqli_fetch_assoc($categories)) { ?>
                                <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phone</label>
                        <input type="tel" name="phone" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">GST Number</label>
                        <input type="text" name="gst_number" class="form-control">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary px-4">Add Supplier</button>
                    </div>
                </div>
            </form>
        </div>

        <div id="supplierTableContainer" class="mt-5" style="display: none;">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="text-primary">All Suppliers</h4>
                <button id="closeTableBtn" class="btn btn-danger">Close Table</button>
            </div>

            <div class="mt-3">
                <label class="form-label">Filter by Category:</label>
                <select id="filterCategory" class="form-select w-auto d-inline-block">
                    <option value="all">All</option>
                    <?php mysqli_data_seek($categories, 0);
                    while ($cat = mysqli_fetch_assoc($categories)) { ?>
                        <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="table-responsive mt-3">
                <table id="supplierTable" class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Sr No</th>
                            <th>Supplier Name</th>
                            <th>Company</th>
                            <th>Category</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>GST</th>
                            <th>Address</th>
                            <th>Operation</th>
                        </tr>
                    </thead>
                    <tbody id="supplierData"></tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Auto close alert after 5 seconds
        document.addEventListener("DOMContentLoaded", function() {
            const alert = document.querySelector(".alert-box");
            if (alert) {
                setTimeout(() => {
                    alert.classList.add("fade");
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            }
        });
        $(document).ready(function() {
            let table;

            $('#viewSuppliersBtn').on('click', function() {
                $('.form-box').hide();
                $('#supplierTableContainer').show();
                fetchSuppliers('all');
            });

            $('#closeTableBtn').on('click', function() {
                $('#supplierTableContainer').hide();
                $('.form-box').show();
                // if (table) table.destroy();
            });

            $('#filterCategory').on('change', function() {
                const category = $(this).val();
                fetchSuppliers(category);
            });

            function fetchSuppliers(category) {
                $.ajax({
                    url: 'fetch_suppliers.php',
                    type: 'POST',
                    data: {
                        category: category
                    },
                    success: function(data) {
                        if (table) table.destroy();
                        $('#supplierData').html(data);
                        table = $('#supplierTable').DataTable({
                            dom: 'Bfrtip',
                            buttons: ['excelHtml5', 'pdfHtml5']
                        });
                    }
                });
            }

            $(document).on('click', '.deleteBtn', function() {
                const id = $(this).data('id');
                if (confirm("Are you sure to delete this supplier?")) {
                    $.post('delete_supplier.php', {
                        id: id
                    }, function(response) {
                        alert(response);
                        $('#filterCategory').trigger('change');
                    });
                }
            });

            $(document).on('click', '.editBtn', function() {
                const id = $(this).data('id');
                window.location.href = 'edit_supplier.php?id=' + id;
            });
        });
    </script>
</body>

</html>