<?php
include '../partials/dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $delete = mysqli_query($con, "DELETE FROM suppliers WHERE supplier_id = $id");

    if ($delete) {
        echo "✅ Supplier deleted successfully.";
    } else {
        echo "❌ Error deleting supplier.";
    }
} else {
    echo "Invalid request.";
}
?>
