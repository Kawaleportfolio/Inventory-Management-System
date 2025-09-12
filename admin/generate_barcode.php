<?php
require '../vendor/autoload.php';
include '../partials/dbconnect.php';
include '../partials/auth_check.php';

use Picqer\Barcode\BarcodeGeneratorPNG;

// Handle barcode generation and download
$downloaded = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['p_id'])) {
    $p_id = intval($_POST['p_id']);

    $res = mysqli_query($con, "SELECT p_name, barcode FROM products WHERE p_id = $p_id");
    if ($row = mysqli_fetch_assoc($res)) {
        $productName = preg_replace('/[^A-Za-z0-9_]/', '_', $row['p_name']);
        $barcodeValue = $row['barcode'];

        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($barcodeValue, $generator::TYPE_CODE_128, 2, 60);

        // Create barcode image from string
        $barcodeImage = imagecreatefromstring($barcode);
        $barcodeWidth = imagesx($barcodeImage);
        $barcodeHeight = imagesy($barcodeImage);

        // Settings for text
        $textFont = 5;
        $textHeight = imagefontheight($textFont);
        $textWidth = imagefontwidth($textFont) * strlen($barcodeValue);

        // Padding and final image size
        $padding = 20;
        $finalWidth = max($barcodeWidth, $textWidth) + $padding;
        $finalHeight = $barcodeHeight + $textHeight + $padding;

        // Create white background
        $finalImage = imagecreatetruecolor($finalWidth, $finalHeight);
        $white = imagecolorallocate($finalImage, 255, 255, 255);
        $black = imagecolorallocate($finalImage, 0, 0, 0);
        imagefill($finalImage, 0, 0, $white);

        // Copy barcode image to center
        imagecopy(
            $finalImage,
            $barcodeImage,
            ($finalWidth - $barcodeWidth) / 2,
            10,
            0,
            0,
            $barcodeWidth,
            $barcodeHeight
        );

        // Add barcode text under the image
        imagestring(
            $finalImage,
            $textFont,
            ($finalWidth - $textWidth) / 2,
            $barcodeHeight + 12,
            $barcodeValue,
            $black
        );

        // Save to file
        if (!is_dir("temp_barcodes")) mkdir("temp_barcodes");
        $filePath = "temp_barcodes/{$productName}.png";
        imagepng($finalImage, $filePath);

        // Clean up
        imagedestroy($barcodeImage);
        imagedestroy($finalImage);

        $downloaded = $filePath;
    } else {
        $error = "Product not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Generate & Download Barcode</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: rgb(213, 214, 218);
            min-height: 100vh;
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

        .barcode-card {
            max-width: 520px;
            margin: auto;
            margin-top: 60px;
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #0d6efd;
        }

        .barcode-img {
            background-color: #fff;
            display: block;
            margin: 10px auto;
            padding: 10px;
            max-width: 100%;
        }

        .barcode-value {
            text-align: center;
            font-weight: bold;
            margin-top: 8px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Back to Admin Panel Button -->
        <div class="text-end mt-3">
            <a href="adminpannel.php" class="btn-back">← Back to Admin Panel</a>
        </div>

        <div class="barcode-card p-4">
            <h4 class="text-center mb-4 text-primary">📦 Product Barcode Generator</h4>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <?php if ($downloaded): ?>
                <div class="alert alert-success">
                    ✅ Barcode generated successfully!
                    <a href="<?= $downloaded ?>" download class="btn btn-success btn-sm mt-2">⬇ Download Barcode (PNG)</a>
                </div>
                <img src="<?= $downloaded ?>" alt="Barcode Image" class="barcode-img">
                <div class="barcode-value"><?= htmlspecialchars($barcodeValue) ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label for="category" class="form-label">Select Category</label>
                    <select name="category" id="category" class="form-select" required>
                        <option value="">-- Choose Category --</option>
                        <?php
                        $cats = mysqli_query($con, "SELECT DISTINCT category_name FROM categories ORDER BY category_name");
                        while ($cat = mysqli_fetch_assoc($cats)) {
                            echo '<option value="' . htmlspecialchars($cat['category_name']) . '">' . htmlspecialchars($cat['category_name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="p_id" class="form-label">Select Product</label>
                    <select name="p_id" id="product" class="form-select" required>
                        <option value="">-- First select category --</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100">Generate & Download</button>
            </form>
        </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#category').on('change', function() {
            let category = $(this).val();
            $('#product').html('<option>Loading...</option>');
            if (category !== '') {
                $.ajax({
                    url: 'get_products_by_category.php',
                    type: 'POST',
                    data: {
                        category: category
                    },
                    success: function(data) {
                        $('#product').html(data);
                    },
                    error: function() {
                        $('#product').html('<option>Error loading products</option>');
                    }
                });
            } else {
                $('#product').html('<option value="">-- First select category --</option>');
            }
        });
    </script>
</body>

</html>
