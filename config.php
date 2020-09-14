<?php 

    $dbcon = mysqli_connect('localhost', 'root', '', 'limit_login');

    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL : " . mysqli_connect_error();
    }

?>