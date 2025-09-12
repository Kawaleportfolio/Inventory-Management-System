<?php
include '../partials/dbconnect.php';
include '../partials/auth_check.php';

// Category Insert
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = trim($_POST['category']);
    if (!empty($category)) {
        $stmt = $con->prepare("INSERT INTO categories (category_name) VALUES (?)");
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $stmt->close();
    }
}

// Category Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $con->prepare("DELETE FROM categories WHERE category_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Fetch all categories
$result = $con->query("SELECT * FROM categories ORDER BY category_id DESC");
$categories = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Categories</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- DataTables Buttons JS and Export Dependencies -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: rgb(213, 214, 218);
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .left,
        .right {
            flex: 1;
            padding: 40px;
            overflow-y: auto;
        }

        .left {
            margin-top: 30px;
            background-color: rgb(213, 214, 218);
            border-right: 2px solid #ccc;
        }

        .form-box {
            max-width: 400px;
            margin: auto;
        }

        .form-box h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-box label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        .form-box input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #999;
        }

        .form-box button {
            width: 100%;
            padding: 10px;
            background: #000;
            color: white;
            border: none;
            border-radius: 5px;
        }

        .form-box button:hover {
            background: #333;
        }

        .right {
            background-color: #f9f9f9;
        }

        table.dataTable thead th {
            background-color: #000;
            color: white;
        }

        .delete-btn {
            color: red;
            text-decoration: none;
            font-weight: bold;
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

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .left,
            .right {
                border: none;
                padding: 20px;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="right">
            <div style="position: absolute; top: 20px; right: 30px;">
                <a href="adminpannel.php" class="btn-back">← Back to Admin Panel</a>
            </div>

            <!-- Left Side Form -->
            <div class="left">
                <div class="form-box">
                    <h2>Add Categories</h2>
                    <form method="POST" action="">
                        <label for="category">Category Name</label>
                        <input type="text" id="category" name="category" autocomplete="off" required>
                        <button type="submit">Add Category</button>
                    </form>
                </div>
            </div>

            <!-- Right Side Data Table -->
            <div class="right">
                <h2>Category List</h2>
                <table id="categoryTable" class="display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>Category ID</th>
                            <th>Category Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($categories as $cat): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= $cat['category_id'] ?></td>
                                <td><?= htmlspecialchars($cat['category_name']) ?></td>
                                <td><a href="?delete=<?= $cat['category_id'] ?>" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#categoryTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'Category_List'
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Category_List'
                    }
                ],
                responsive: true
            });
        });
    </script>

</body>

</html>
