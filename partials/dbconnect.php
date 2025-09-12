<?php

    $con=new mysqli("localhost","root","Priyaj@123","imwbi");

    if(!$con){
        die("Sorry we failed to conect: ".mysqli_connect_error());
    }

?>