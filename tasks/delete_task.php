<?php
    require '../includes/connection.php';
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit();
    }   
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        
    }
?>