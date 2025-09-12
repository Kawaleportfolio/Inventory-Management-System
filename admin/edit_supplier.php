<?php
include '../partials/dbconnect.php';

if (!isset($_GET['id'])) {
    die("No supplier ID provided.");
}

$id = intval($_GET['id']);
$supplier = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM suppliers WHERE supplier_id = $id"));
$categories = mysqli_query($con, "SELECT category_id, category_name FROM categories");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplier_name = trim($_POST['supplier_name']);
    $company_name = trim($_POST['company_name']);
    $category_id = intval($_POST['category_id']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $gst_number = trim($_POST['gst_number']);
    $address = trim($_POST['address']);

    $update = mysqli_query($con, "UPDATE suppliers SET
        supplier_name = '$supplier_name',
        company_name = '$company_name',
        category_id = $category_id,
        phone = '$phone',
        email = '$email',
        gst_number = '$gst_number',
        address = '$address'
        WHERE supplier_id = $id");

    if ($update) {
        header("Location: add_supplier.php?updated=1&name=" . urlencode($supplier_name));
        exit;
    } else {
        echo "❌ Update failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Supplier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    </style>
</head>

<body>
    <div class="container mt-5">
        <!-- <h2 class="text-primary mb-4">Edit Supplier</h2> -->
        <div class="form-box mt-4">
            <form method="POST">
                <h1 class="text-primary text-center">Add Supplier</h1>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label>Supplier Name</label>
                        <input type="text" name="supplier_name" value="<?= htmlspecialchars($supplier['supplier_name']) ?>" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label>Company Name</label>
                        <input type="text" name="company_name" value="<?= htmlspecialchars($supplier['company_name']) ?>" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Category</label>
                        <select name="category_id" class="form-select" required>
                            <?php while ($cat = mysqli_fetch_assoc($categories)) { ?>
                                <option value="<?= $cat['category_id'] ?>" <?= $cat['category_id'] == $supplier['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['category_name']) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Phone</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($supplier['phone']) ?>" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($supplier['email']) ?>" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>GST Number</label>
                        <input type="text" name="gst_number" value="<?= htmlspecialchars($supplier['gst_number']) ?>" class="form-control">
                    </div>
                    <div class="col-md-12">
                        <label>Address</label>
                        <textarea name="address" class="form-control" rows="2"><?= htmlspecialchars($supplier['address']) ?></textarea>
                    </div>
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-success">Update Supplier</button>
                        <a href="add_supplier.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>

</html>