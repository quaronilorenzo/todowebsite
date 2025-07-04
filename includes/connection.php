<?php
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "mini_dashboard";
    $conn = new mysqli($host,$user,$password,$database);
    if($conn->connect_error){
        die("connessione errata!");
    }
?>
