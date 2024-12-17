<?php 
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "coffeeshop";

    $conn = mysqli_connect($servername, $username, $password, $database);

    // Kiểm tra lỗi kết nối
    if (mysqli_connect_errno()) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>
