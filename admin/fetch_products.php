<?php
    include '../partials/dbconnect.php';
    include '../partials/auth_check.php';

    $query= "select * from products";
    $result = mysqli_query($con, $query);
    $data = [];

    while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode(['data' => $data]);

?>