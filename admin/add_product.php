<?php
// Fetch categories from DB
// include '../partials/dbconnect.php';
include '../partials/auth_check.php';
include '../partials/dbconnect.php';
$categories = mysqli_query($con, "SELECT category_id, category_name FROM categories");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
        ✅ Product <strong><?= htmlspecialchars($_GET['name']) ?></strong> added successfully with a unique barcode!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php elseif (isset($_GET['exists']) && isset($_GET['name'])): ?>
    <div class="alert alert-danger alert-dismissible fade show alert-box position-relative text-center fw-bold shadow-sm mx-auto mt-4" style="max-width: 600px; font-size: 18px;">
        ❌ Product <strong><?= htmlspecialchars($_GET['name']) ?></strong> already exists. Please try another name.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

    <div class="container">
        <!-- Back to Admin Panel Button -->
        <div class="text-end mt-3">
            <a href="adminpannel.php" class="btn-back">← Back to Admin Panel</a>
        </div>

        <!-- Form Box -->
        <div class="form-box mt-4">
            <h2 class="mb-4 text-center text-primary">Add New Product</h2>

            <form action="insert_product.php" method="POST" enctype="multipart/form-data">
                <div class="row g-4">
                    <div class="col-md-4">
                        <label for="product_name" class="form-label">Product Name *</label>
                        <input type="text" name="product_name" id="product_name" class="form-control form-control-lg" required>
                    </div>

                    <div class="col-md-4">
                        <label for="brand_name" class="form-label">Company / Brand *</label>
                        <input type="text" name="brand_name" id="brand_name" class="form-control form-control-lg" required>
                    </div>

                    <div class="col-md-4">
                        <label for="category_id" class="form-label">Category *</label>
                        <select name="category_id" id="category_id" class="form-select form-select-lg" required>
                            <option value="">-- Select Category --</option>
                            <?php while ($cat = mysqli_fetch_assoc($categories)) { ?>
                                <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="cost_price" class="form-label">Cost Price (₹) *</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" min="0.01" name="cost_price" id="cost_price" class="form-control form-control-lg" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="selling_price" class="form-label">Selling Price (₹) *</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" min="0.01" name="selling_price" id="selling_price" class="form-control form-control-lg" required>
                        </div>
                    </div>
                    <!-- <div class="col-md-4">
                        <label for="barcode" class="form-label">Barcode</label>
                        <input type="text" name="barcode" id="barcode" class="form-control form-control-lg" value="<?php echo 'BAR' . rand(100000, 999999); ?>" disabled>
                    </div> -->

                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn-submit"> Add Product</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</body>
<script>
    // Auto close alert after 5 seconds
    document.addEventListener("DOMContentLoaded", function () {
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
</script>

</html>