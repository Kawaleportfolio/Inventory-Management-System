<?php
include '../partials/auth_check.php';
include '../partials/dbconnect.php';
$purchases = mysqli_query($con, "select * from purchases where status='Pending'");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Pending Purchase Orders</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- annimated alert msg -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: #f1f3f6;
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

    .card {
      margin-top: 12px;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
      border: none;
    }

    .table thead th {
      background-color: #2a2e37;
      color: #fff;
      vertical-align: middle;
    }

    .btn-view {
      background-color: #0d6efd;
      color: #fff;
    }

    .btn-deliver {
      background-color: #28a745;
      color: #fff;
    }

    .modal-header {
      background-color: #0d6efd;
      color: white;
    }

    @media (max-width: 576px) {
      .table-responsive {
        font-size: 14px;
      }
    }
  </style>
</head>

<body>

  <div class="container my-5">
    <!-- Back to Admin Panel Button -->
    <div class="text-end mt-3">
      <a href="adminpannel.php" class="btn-back">← Back to Admin Panel</a>
    </div>
    <div class="card p-4">
      <h3 class="mb-4 text-center text-primary">📦 Pending Purchase Orders</h3>

      <div class="table-responsive">
        <table class="table align-middle table-hover">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Supplier Name</th>
              <th>Supplier Number</th>
              <th>Order Date</th>
              <th>Expected Delivery</th>
              <th>Total Purchase</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($purchases)) { 
                $display_amt= $row['total_amount'];
              ?>
              <tr>
                <td><?= $row['purchase_id']; ?></td>
                <!-- fetch supplier name by supplier id -->
                <?php
                $supp = mysqli_fetch_assoc(mysqli_query($con, "SELECT supplier_name, phone as supp_number FROM suppliers WHERE supplier_id = " . $row['supplier_id']));
                ?>
                <td><?= $supp['supplier_name']; ?></td>
                <td><?= $supp['supp_number']; ?></td>
                <td><?= $row['order_date']; ?></td>
                <td><?= $row['expected_delivery_date']; ?></td>
                <td>₹<?= $row['total_amount']; ?></td>
                <td>
                  <button class="btn btn-sm btn-view me-2" data-id="<?= $row['purchase_id']; ?>" data-bs-toggle="modal" data-bs-target="#productModal">View</button>
                  <button class="btn btn-sm btn-deliver me-2" data-id="<?= $row['purchase_id']; ?>">Delivered</button>
                </td>
              </tr>
            <?php } ?>

            <!-- Repeat Rows Dynamically -->
          </tbody>
        </table>
      </div>
    </div>
  </div>


  <!-- Modal -->
  <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="productModalLabel"><!-- this line display by ajax script --></h4><h5 class="mb-0 text-center w-100">Total: <?= $display_amt ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div id="modal-content">
            <!-- AJAX data will be inserted here -->
          </div>
        </div>
      </div>
    </div>
  </div>



</body>
<!-- Bootstrap JS (for modal) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    $('.btn-view').click(function() {
      var orderId = $(this).data('id');

      $('#productModalLabel').text('Order #' + orderId + ' – Products');

      $.ajax({
        url: 'fetch_pur_items.php',
        type: 'GET',
        data: {
          order_id: orderId
        },
        success: function(data) {
          $('#modal-content').html(data);
        },
        error: function() {
          $('#modal-content').html('<p class="text-danger">Error loading data.</p>');
        }

      });
    });


    $('.btn-deliver').click(function() {
      var orderId = $(this).data('id');
      $.ajax({
        url: 'pur_deliver.php',
        type: 'GET',
        data: {
          order_id: orderId
        },
        success: function(response) {
          // Show beautiful animated message
          $('body').append(`
                <div id="successMsg" class="position-fixed top-0 start-50 translate-middle-x mt-3 z-3" style="z-index:9999;">
                    <div class="alert alert-success alert-dismissible fade show shadow-lg animate__animated animate__fadeInDown" role="alert">
                        <strong>✅ Success!</strong><br> ${response}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            `);

          // Auto-hide after 5 seconds
          setTimeout(() => {
            $('#successMsg').fadeOut('slow', function() {
              $(this).remove();
             location.reload();
            });
          }, 1000);
        },
        error: function() {
          alert("❌ Error while delivering order.");
        }
      });
    });
  });
</script>

</html>