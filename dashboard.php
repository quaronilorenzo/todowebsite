<?php
    session_start();
    include 'includes/connection.php';   
    if(!isset($_SESSION['user_id'])){
        header("Location: login.php");
        exit();
    }
    $user_email = $_SESSION['user_email'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="">
</head>
<body>
    <h2>Benvenuto, <?php echo htmlspecialchars($user_email); ?>!</h2>

    <p><a href="logout.php">Esci</a></p>
</body>
</html>
