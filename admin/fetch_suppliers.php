<?php
include '../partials/dbconnect.php';
$category = $_POST['category'];

$sql = "SELECT s.*, c.category_name FROM suppliers s JOIN categories c ON s.category_id = c.category_id";
if ($category !== 'all') {
    $category = intval($category);
    $sql .= " WHERE s.category_id = $category";
}
$sql .= " ORDER BY s.supplier_id DESC";

$result = mysqli_query($con, $sql);
$sr = 1;

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$sr}</td>
        <td>" . htmlspecialchars($row['supplier_name']) . "</td>
        <td>" . htmlspecialchars($row['company_name']) . "</td>
        <td>" . htmlspecialchars($row['category_name']) . "</td>
        <td>" . htmlspecialchars($row['phone']) . "</td>
        <td>" . htmlspecialchars($row['email']) . "</td>
        <td>" . htmlspecialchars($row['gst_number']) . "</td>
        <td>" . htmlspecialchars($row['address']) . "</td>
        <td>
            <button class='btn btn-sm btn-warning editBtn' data-id='{$row['supplier_id']}'>Edit</button>
            <button class='btn btn-sm btn-danger deleteBtn' data-id='{$row['supplier_id']}'>Delete</button>
        </td>
    </tr>";
    $sr++;
}
?>
