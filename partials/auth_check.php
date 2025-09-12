<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: http://localhost/IMWBI/index.php");
    exit();
}

$currentPath = $_SERVER['PHP_SELF'];

// If user is admin but trying to access emp pages
if (strpos($currentPath, '/emp/') !== false && $_SESSION['role'] !== 'employee') {
    echo "<h3 style='color:red; text-align:center;'>Access Denied: Only Employees can access this page.</h3>";
    exit();
}

// If user is employee but trying to access admin pages
if (strpos($currentPath, '/admin/') !== false && $_SESSION['role'] !== 'admin') {
    echo "<h3 style='color:red; text-align:center;'>Access Denied: Only Admins can access this page.</h3>";
    exit();
}
?>
