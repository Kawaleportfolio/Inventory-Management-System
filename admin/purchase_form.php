<?php
include '../partials/dbconnect.php';
include '../partials/auth_check.php';
$date = new DateTime();
$orderdate = date('Y-m-d');

$maxdate = date('Y-m-d', strtotime('+10 days'));
// echo $maxdate;

$category_result = mysqli_query($con, "SELECT DISTINCT category_name FROM categories");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Purchase Form</title>
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Mobile responsiveness -->

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <style>
    body {
      background-color: rgb(213, 214, 218);
      font-family: 'Segoe UI', sans-serif;
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

    .form-section {
      background-color: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }

    @media (max-width: 768px) {
      .btn {
        width: 100%;
        margin-top: 10px;
      }
    }

    table {
      font-size: 0.95rem;
    }

    h2 {
      font-weight: 600;
      color: #343a40;
    }
  </style>
</head>

<body class="container py-4">

  <!-- Back to Admin Panel Button -->
  <div class="text-end mb-3">
    <a href="adminpannel.php" class="btn-back">← Back to Admin Panel</a>
  </div>

  <h2 class="mb-4 text-center">Purchase Products</h2>

  <!-- Product Selection Section -->
  <div class="form-section mb-4">
    <div class="row g-3">
      <div class="col-md-4 col-sm-6">
        <label class="form-label">Category</label>
        <select id="categorySelect" class="form-select" required>
          <option value="">-- Select Category --</option>
          <?php while ($cat = mysqli_fetch_assoc($category_result)) { ?>
            <option value="<?= htmlspecialchars($cat['category_name']) ?>">
              <?= htmlspecialchars($cat['category_name']) ?>
            </option>
          <?php } ?>
        </select>
        <small id="categoryLockNotice" class="text-danger d-none">Category is locked for this purchase.</small>
      </div>

      <div class="col-md-4 col-sm-6">
        <label class="form-label">Company</label>
        <select id="companySelect" class="form-select" required>
          <option value="">-- Select Company --</option>
        </select>
      </div>

      <div class="col-md-4 col-sm-6">
        <label class="form-label">Product</label>
        <select id="productSelect" class="form-select" required>
          <option value="">-- Select Product --</option>
        </select>
      </div>

      <div class="col-md-3 col-sm-6">
        <label class="form-label">Price (₹)</label>
        <input type="text" id="priceInput" class="form-control" disabled>
      </div>

      <div class="col-md-3 col-sm-6">
        <label class="form-label">Quantity</label>
        <input type="number" id="quantityInput" class="form-control" min="1">
      </div>

      <div class="col-md-3 col-sm-6">
        <label class="form-label">GST (%)</label>
        <input type="number" id="gstInput" class="form-control" disabled>
      </div>

      <div class="col-md-2 col-sm-6 d-flex align-items-end">
        <button id="addbtn" class="btn btn-primary w-100">Add</button>
      </div>
    </div>
  </div>

  <!-- Table Container -->
  <div id="productTableContainer" class="form-section d-none mb-4">
    <h5 class="mb-3">Selected Products</h5>
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
          <tr>
            <th>Category</th>
            <th>Company</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>GST [%]</th>
            <th>GST Amount</th>
            <th>Total</th>
            <th>Total with GST</th>
            <th>Remove</th>
          </tr>
        </thead>
        <tbody id="productTableBody"></tbody>
      </table>
    </div>
    <h5 id="totalPurchaseAmount" class="text-end text-success mt-3"></h5>
  </div>

  <!-- Supplier and Dates -->
  <div id="supplierSection" class="form-section d-none mb-4">
    <div class="row g-3">
      <div class="col-md-4 col-sm-6">
        <label class="form-label">Supplier Name</label>
        <select class="form-select" id="supplierSelect" required>
          <option value="">-- Select Supplier --</option>
          <option value="1">ABC Traders</option>
          <option value="2">XYZ Supplies</option>
        </select>
      </div>

      <div class="col-md-4 col-sm-6">
        <label class="form-label">Order Date</label>
        <input type="date" id="orderDate" class="form-control" value="<?= $orderdate ?>" readonly required>
      </div>

      <div class="col-md-4 col-sm-6">
        <label class="form-label">Expected Delivery</label>
        <input type="date" id="expectedDate" min="<?= date('Y-m-d') ?>" max="<?= $maxdate ?>" class="form-control" required>
      </div>
    </div>

    <!-- Supplier Details -->
    <div class="row g-3 mt-3" id="supplierDetailsRow">
      <div class="col-md-3 col-sm-6">
        <label class="form-label">Company</label>
        <input type="text" id="supplierCompany" class="form-control" disabled>
      </div>
      <div class="col-md-3 col-sm-6">
        <label class="form-label">Phone</label>
        <input type="text" id="supplierPhone" class="form-control" disabled>
      </div>
      <div class="col-md-3 col-sm-6">
        <label class="form-label">Email</label>
        <input type="email" id="supplierEmail" class="form-control" disabled>
      </div>
      <div class="col-md-3 col-sm-6">
        <label class="form-label">Address</label>
        <input type="text" id="supplierAddress" class="form-control" disabled>
      </div>
    </div>

    <div class="text-end mt-4">
      <button class="btn btn-success px-4" onclick="submitPurchase()">Submit Purchase</button>
    </div>
  </div>


  <!-- JS -->
  <script>
    let cart = []; //store products like cart

    $(document).ready(function() {
      $('#categorySelect').on('change', function() {
        let category = $(this).val();
        if (category !== "") {
          // 🔹 Load companies
          $.post('fetch_companies.php', {
            category
          }, function(response) {
            $('#companySelect').html(response);
            $('#productSelect').html('<option value="">-- Select Product --</option>');
          });

          // 🔹 Load suppliers based on category
          $.post('pur_fetch_suppliers.php', {
            category
          }, function(response) {
            $('#supplierSelect').html(response);
          });

        } else {
          $('#companySelect').html('<option value="">-- Select Company --</option>');
          $('#productSelect').html('<option value="">-- Select Product --</option>');
          $('#supplierSelect').html('<option value="">-- Select Supplier --</option>');
        }
      });

      $('#companySelect').on('change', function() {
        let category = $('#categorySelect').val();
        let company = $(this).val();
        if (category !== "" && company !== "") {
          $.post('pur_fetch_products.php', {
            category: category,
            company: company
          }, function(response) {
            $('#productSelect').html(response);
          });
        } else {
          $('#productSelect').html('<option value="">-- Select Product --</option>');
        }
      });

      $('#productSelect').on('change', function() {
        const productName = $(this).val();

        if (productName !== "") {
          $.ajax({
            url: 'get_product_price.php',
            type: 'POST',
            data: {
              product_name: productName
            },
            dataType: 'json',

            success: function(response) {
              if (response.success) {
                $('#priceInput').val(response.cost_price);
                $('#gstInput').val(response.gst_percent);
              } else {
                $('#priceInput').val('');
                $('#gstInput').val('');
              }
            },

            error: function(xhr, status, error) {
              console.log(xhr.responseText);
              $('#priceInput').val('Error');
              $('#gstInput').val('');
            }
          });
        } else {
          $('#priceInput').val('');
          $('#gstInput').val('');
        }
      });


      $('#addbtn').click(function() {
        const category = $('#categorySelect').val();
        const company = $('#companySelect').val();
        const product = $('#productSelect option:selected').text();
        const productId = $('#productSelect').val();

        const rawPrice = $('#priceInput').val().replace(/[^\d]/g, ''); // remove ₹
        const price = parseInt(rawPrice);
        const quantity = parseInt($('#quantityInput').val());
        const gstPercent = $('#gstInput').val();
        const gstamount = Number(((quantity * price * gstPercent) /100).toFixed(2));
        const total = Number((quantity * price).toFixed(2));
        const totalprice = Number(((quantity * price)+gstamount).toFixed(2));


        if (!category || !company || !productId || !quantity || quantity <= 0 || isNaN(price)) {
          alert("Please fill all fields correctly.");
          return;
        }

        cart.push({
          category,
          company,
          product,
          productId,
          quantity,
          gstPercent,     //GST percentage according to selected product
          price,
          gstamount,  //gst amount
          total,      //total without jst amount
          totalprice //total with gst
        });

        renderCart();

        // Total price of purchase order
        let totalAmount = cart.reduce((sum, item) => sum + item.totalprice, 0);
        $('#totalPurchaseAmount').text("Total Purchase Price: ₹" + totalAmount);



        $('#quantityInput').val('');
        $('#productSelect').val('');
        $('#priceInput').val('');

        $('#productTableContainer').removeClass('d-none');
        $('#supplierSection').removeClass('d-none');
        // ✅ Disable category selection after first product is added
        $('#categorySelect').prop('disabled', true);
        $('#categoryLockNotice').removeClass('d-none');
      });
    });

    function renderCart() {
      let tbody = '';
      cart.forEach((item, index) => {
        tbody += `
                    <tr>
                        <td>${item.category}</td>
                        <td>${item.company}</td>
                        <td>${item.product}</td>
                        <td>${item.quantity}</td>
                        <td>${item.price}</td>
                        <td>${item.gstPercent} %</td>
                        <td>${item.gstamount}</td>
                        <td>${item.total}</td>
                        <td>${item.totalprice}</td>
                        <td><button class="btn btn-sm btn-danger" onclick="removeItem(${index})">Remove</button></td>
                    </tr>
                `;
      });
      $('#productTableBody').html(tbody);
      // 🔥 THIS IS IMPORTANT
      let totalAmount =  Number(cart.reduce((sum, item) => sum + Number(item.totalprice), 0).toFixed(2));
      $('#totalPurchaseAmount').text("Total Purchase Price: ₹" + totalAmount);
    }

    function removeItem(index) {
      cart.splice(index, 1);
      renderCart();
      if (cart.length === 0) {
        $('#productTableContainer').addClass('d-none');
        $('#supplierSection').addClass('d-none');
      }
    }


    $('#supplierSelect').on('change', function() {
      let supplierId = $(this).val();

      if (supplierId !== "") {
        $.post('get_supplier_details.php', {
          supplier_id: supplierId
        }, function(data) {
          const supplier = JSON.parse(data);
          $('#supplierCompany').val(supplier.company);
          $('#supplierPhone').val(supplier.phone);
          $('#supplierEmail').val(supplier.email);
          $('#supplierAddress').val(supplier.address);
        });
      } else {
        $('#supplierCompany').val('');
        $('#supplierPhone').val('');
        $('#supplierEmail').val('');
        $('#supplierAddress').val('');
      }
    });


    function submitPurchase() {
      const supplier = $('#supplierSelect').val();
      const orderDate = $('#orderDate').val();
      const expectedDate = $('#expectedDate').val();

      if (!supplier || !orderDate || !expectedDate) {
        alert("Please fill supplier and date details.");
        return;
      }

      const totalAmount = cart.reduce((sum, item) => sum + item.totalprice, 0); // Add this

      const payload = {
        cart,
        supplier,
        orderDate,
        expectedDate,
        totalAmount
      };

      // ✅ Send to backend
      $.ajax({
        url: 'insert_purchase.php',
        type: 'POST',
        data: {
          data: JSON.stringify(payload)
        },
        success: function(response) {
          alert("Purchase saved successfully!");
          console.log(response);
          location.reload(); // optional
        },
        error: function() {
          alert("Error while saving purchase.");
        }
      });
    }
  </script>
</body>

</html>