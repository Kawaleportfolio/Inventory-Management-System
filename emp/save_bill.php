<?php
// File: save_bill.php
include '../partials/auth_check.php';
include '../partials/dbconnect.php';

$e_name = $_SESSION['username'];
$e_id = $_SESSION['u_id'];


$customer_name = $_POST['customer_name'] ?? '';
$customer_mobile = $_POST['customer_mobile'] ?? '';
$cart = json_decode($_POST['cart'], true);

$grandtotal = 0;
foreach ($cart as $item1) {
  // $price1 = $item1['price'];
  // $qty1 = $item1['qty'];
  // $total1 = $item1['price'] * $item1['qty'];

  $total = $item1['price'] * $item1['qty'];
  $gstamount = round($total * $item1['gst'] / 100, 2);

  $totalAmount = round($total + $gstamount, 2);
  $grandtotal += $totalAmount;
}

// 1. Insert into billing_master
// mysqli_query($con, "INSERT INTO billing_master (customer_name, mobile, billing_date) VALUES ('$customer_name', '$customer_mobile', NOW())");
mysqli_query($con, "INSERT INTO bill_master (employee_id, employee_name, bill_date, customer_name, c_mobile, total_amount) VALUES ('$e_id', '$e_name', NOW(), '$customer_name', '$customer_mobile', '$grandtotal')");
$bill_id = mysqli_insert_id($con);

// 2. Insert each item and reduce stock
// $totalAmount = 0;
foreach ($cart as $item) {
  $name = $item['name'];
  $barcode = $item['barcode'];
  $price = $item['price'];
  $qty = $item['qty'];
  $gst_percent = $item['gst'];

  $total = $price * $qty;
  $gst_amount = round($total * $gst_percent / 100, 2);
  $total_amount = round($total + $gst_amount, 2);
  // $grandtotal += $total_amount;

  $product_id = mysqli_fetch_assoc(mysqli_query($con, "SELECT p_id FROM products WHERE barcode = '$barcode'"));
  $p_id = $product_id['p_id'];

  //insert in bill_items table
  mysqli_query($con, "INSERT INTO bill_items (bill_id, product_name, p_id, quantity, price_per_unit, gst_percent, gst_amount, total, total_amount) VALUES ('$bill_id', '$name', '$p_id', '$qty', '$price', '$gst_percent', '$gst_amount', '$total', '$total_amount')");

  // Reduce stock
  mysqli_query($con, "UPDATE product_stock SET product_qty = product_qty - $qty WHERE p_id = $p_id");
}

// Generate printable invoice with download option
$itemsHtml = '';
$i = 1;
foreach ($cart as $item) {
  // $taxable_amount = $item['price'] * $item['qty'];
  $total = $item['price'] * $item['qty'];
  $gstamount = round($total * $item['gst'] / 100, 2);

  $totalamount = round($total + $gstamount, 2);
  $itemsHtml .= "
    <tr>
      <td>{$i}</td>
      <td>{$item['name']}</td>
      <td>{$item['barcode']}</td>
      <td>₹{$item['price']}</td>
      <td>{$item['qty']}</td>
      <td>{$item['gst']}%</td>      
      <td>₹" . number_format($gstamount, 2) . "</td> 
      <td>₹" . number_format($total, 2) . "</td>
      <td>₹" .number_format($totalamount, 2). "</td>
    </tr>";
  $i++;
}

// HTML output
$html = "
<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='UTF-8'>
  <title>Invoice - INV" . str_pad($bill_id, 4, '0', STR_PAD_LEFT) . "</title>
  <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
  <style>
    body {
      padding: 30px;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f9f9f9;
    }
    .invoice-box {
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .header-title {
      font-size: 32px;
      font-weight: bold;
      color: #2c3e50;
      margin-bottom: 10px;
    }
    .sub-header {
      font-size: 18px;
      margin-bottom: 25px;
      color: #555;
    }
    .table th, .table td {
      vertical-align: middle;
    }
    @media print {
      .no-print {
        display: none;
      }
    }
  </style>
</head>
<body>

<div class='invoice-box container'>
  <div class='text-center'>
    <div class='header-title'>Shree Stationary Shop</div>
    <div class='sub-header'>Customer Billing Receipt</div>
  </div>

  <div class='row mb-3'>
    <div class='col-md-6'>
      <strong>Customer Name:</strong> $customer_name<br>
      <strong>Mobile:</strong> $customer_mobile
    </div>
    <div class='col-md-6 text-md-end'>
      <strong>Invoice No:</strong> INV" . str_pad($bill_id, 4, '0', STR_PAD_LEFT) . "<br>
      <strong>Date:</strong> " . date('d M Y H:i') . "
    </div>
  </div>

  <table class='table table-bordered'>
    <thead class='table-dark'>
      <tr>
        <th>#</th>
        <th>Product Name</th>
        <th>Barcode</th>
        <th>Price</th>
        <th>Qty</th>
        <th>GST [%]</th>
        <th>GST Amount</th>
        <th>Taxable Amount</th>
        <th>Total Amount</th>
      </tr>
    </thead>
    <tbody>$itemsHtml</tbody>
    <tfoot>
      <tr>
        <th colspan='5' class='text-end'>Total Amount</th>
        <th>₹" . number_format($grandtotal, 2) . "</th>
      </tr>
    </tfoot>
  </table>

  <div class='text-center mt-4'>
    <p>Thank you for shopping with <strong>Shree Stationary Shop</strong>!</p>
  </div>

  <div class='no-print text-center mt-3'>
    <button onclick='window.print()' class='btn btn-primary me-2'>🖨️ Print</button>
    <button onclick='downloadPDF()' class='btn btn-secondary'>⬇️ Download PDF</button>
  </div>
</div>

<script src='https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js'></script>
<script>
  function downloadPDF() {
    html2pdf().from(document.body).save('Invoice_INV$bill_id.pdf');
  }

  // Clear cart in parent window
  if (window.opener && window.opener.clearCart) {
    window.opener.clearCart();
  }
</script>

</body>
</html>";

echo $html;
