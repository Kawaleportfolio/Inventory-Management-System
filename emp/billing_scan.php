<?php include '../partials/dbconnect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Billing Page</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://unpkg.com/@zxing/library@latest"></script>
  <style>
    body {
      background: rgb(213, 214, 218);
      font-family: 'Segoe UI', sans-serif;
    }
    .container {
      max-width: 960px;
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

    video {
      width: 100%;
      max-width: 500px;
      border: 2px solid #444;
      border-radius: 10px;
      margin-bottom: 10px;
    }
    .card {
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .note-box {
      background: #fff3cd;
      border-left: 5px solid #ffc107;
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 8px;
      font-size: 0.95rem;
    }
    @media (max-width: 576px) {
      .btn { width: 100%; margin-top: 5px; }
    }
  </style>
</head>
<body>

<div class="container mt-4">

<!-- Back to Admin Panel Button -->
    <div class="text-end mt-3">
      <a href="employeepannel.php" class="btn-back">← Back to Admin Panel</a>
    </div>

  <div class="card p-4 mt-4">
    <h2 class="text-center mb-4">🧾 Billing System</h2>

    <div class="note-box">
      <strong>Note:</strong> Always scan the correct barcode before adding products. Verify the quantity before billing. All bills are final after generation.
    </div>

    <!-- Scanner -->
    <div class="text-center mb-3">
      <video id="scanner"></video>
      <div class="d-flex justify-content-center gap-2">
        <button class="btn btn-success" id="startScanner">📷 Start Camera</button>
        <button class="btn btn-danger" id="stopScanner">⛔ Stop Camera</button>
      </div>
    </div>

    <!-- Barcode Input -->
    <div class="row g-2 mb-4">
      <div class="col-md-8">
        <input type="text" id="barcodeInput" class="form-control" placeholder="Scan or Enter Barcode">
      </div>
      <div class="col-md-4">
        <button class="btn btn-primary w-100" id="fetchProduct">🔍 Fetch Product</button>
      </div>
    </div>

    <!-- Cart Table -->
    <h4 class="mb-3">🛒 Cart</h4>
    <div class="table-responsive">
      <table class="table table-bordered table-striped" id="cartTable">
        <thead class="table-dark text-center">
          <tr>
            <th>#</th>
            <th>Product</th>
            <th>Barcode</th>
            <th>Price</th>
            <th>Qty</th>
            <th>GST Percent(%)</th>
            <th>GST Amount</th>
            <th>Taxable Amount</th>
            <th>Total</th>
            <th>Remove</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>

    <!-- Generate Bill -->
    <div class="text-end mt-3">
      <button class="btn btn-outline-primary btn-lg" id="generateBill">💳 Generate Bill</button>
    </div>
  </div>
</div>

<!-- Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Product Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="modalBarcode">
        <div><strong>Name:</strong> <span id="modalName"></span></div>
        <div><strong>Price:</strong> ₹<span id="modalPrice"></span></div>
        <div><strong>Available Qty:</strong> <span id="modalavailableqty" class="text-success fw-bold"></span></div>
        <div><strong>GST:</strong><span id="gstpercent"></span>%</div>
        <input type="number" id="modalQty" class="form-control mt-2" placeholder="Enter Quantity" min="1" value="1">
      </div>
      <div class="modal-footer">
        <button class="btn btn-success" id="addToCartModal">Add to Cart</button>
      </div>
    </div>
  </div>
</div>

<!-- Customer Modal -->
<div class="modal fade" id="customerModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="customerForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Customer Info</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="text" id="customerName" class="form-control" placeholder="Customer Name" required>
        <input type="text" id="customerMobile" class="form-control mt-2" placeholder="Mobile Number (optional)">
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success w-100">🧾 Create Bill</button>
      </div>
    </form>
  </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
let cart = [];
const codeReader = new ZXing.BrowserMultiFormatReader();

// Start Scanner
$('#startScanner').click(() => {
  codeReader.listVideoInputDevices().then(devices => {
    codeReader.decodeFromVideoDevice(devices[0].deviceId, 'scanner', (result, err) => {
      if (result) {
        const scannedBarcode = result.text;
        if ($('#barcodeInput').val() !== scannedBarcode) {
          $('#barcodeInput').val(scannedBarcode).focus();
          $('#fetchProduct').click();
        }
      }
    });
  });
});

// Stop Scanner
$('#stopScanner').click(() => {
  codeReader.reset();
});

// Fetch Product
$('#fetchProduct').click(() => {
  const barcode = $('#barcodeInput').val().trim();
  if (!barcode) return alert("Enter or scan barcode");

  $.ajax({
    url: 'fetch_product.php',
    method: 'POST',
    data: { barcode },
    dataType: 'json',
    success: function (data) {
      if (data.success) {
        $('#modalName').text(data.name);
        $('#modalPrice').text(data.price);
        $('#modalavailableqty').text(data.ava_qty);
        $('#gstpercent').text(data.gst);
        $('#modalBarcode').val(data.barcode);
        $('#modalQty').val(1);
        new bootstrap.Modal(document.getElementById('productModal')).show();
      } else {
        alert("Product not found");
      }
    },
    error: function () {
      alert("Error fetching product");
    }
  });
});

// Add to cart
$('#addToCartModal').click(() => {
  const name = $('#modalName').text();
  const price = parseFloat($('#modalPrice').text());
  const barcode = $('#modalBarcode').val();
  const qty = parseInt($('#modalQty').val());
  const gst = parseInt($('#gstpercent').text());

  if (!qty || qty < 1) return alert("Enter valid quantity");

  cart.push({ name, barcode, price, qty, gst});
  updateCart();
  bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();
});

function updateCart() {
  const tbody = $('#cartTable tbody');
  tbody.empty();
  cart.forEach((item, i) => {
    const gst_amount=Number(((item.qty * item.price * item.gst) / 100).toFixed(2));
    // const taxable_gst = item
    const taxable_gst =Number((item.price * item.qty).toFixed(2));
    const total = Number( (taxable_gst + gst_amount).toFixed(2));
    tbody.append(`
      <tr>
        <td>${i + 1}</td>
        <td>${item.name}</td>
        <td>${item.barcode}</td>
        <td>₹${item.price}</td>
        <td>${item.qty}</td>
        <td>${item.gst}%</td>
        <td>₹${gst_amount}</td>
        <td>₹${taxable_gst}</td>
        <td>₹${total}</td>
        <td><button class="btn btn-danger btn-sm" onclick="removeItem(${i})">X</button></td>
      </tr>
    `);
  });
}

function removeItem(index) {
  cart.splice(index, 1);
  updateCart();
}

function clearCart() {
  cart = [];
  updateCart();
}

// Generate Bill
$('#generateBill').click(() => {
  if (cart.length === 0) {
    alert("Cart is empty.");
    return;
  }
  new bootstrap.Modal(document.getElementById('customerModal')).show();
});

// Submit Customer Form
$('#customerForm').submit(function(e) {
  e.preventDefault();
  const customerName = $('#customerName').val().trim();
  const customerMobile = $('#customerMobile').val().trim();

  $.ajax({
    url: 'save_bill.php',
    method: 'POST',
    data: {
      customer_name: customerName,
      customer_mobile: customerMobile,
      cart: JSON.stringify(cart)
    },
    success: function (billHTML) {
      bootstrap.Modal.getInstance(document.getElementById('customerModal')).hide();
      const billWindow = window.open('', '_blank');
      billWindow.document.write(billHTML);
      billWindow.document.close();
    },
    error: function () {
      alert("Error saving bill.");
    }
  });
});
</script>
</body>
</html>
