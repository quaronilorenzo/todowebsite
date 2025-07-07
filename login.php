<?php
session_start();
include 'includes/connection.php';

$msg = "";
$err_email = "";
$err_pwd = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $user_id;
            $_SESSION["user_email"] = $email;
            header("Location: tasks/dashboard.php");
            exit();
        } else {
            $err_pwd = "❌ Password errata. Riprova.";
        }
    } else {
        $err_email = "❌ Email non registrata.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Login - To Do List</title>
    <link rel="stylesheet" href="./css/login.css"> <!-- stesso stile del register -->
</head>
<body>
    <div class="header">
        <h1>📝 To Do List</h1>
        <p>Creato da Quaroni • <a href="https://github.com/quaronilorenzo" target="_blank">GitHub</a></p>
    </div>

    <div class="container">
        <h2 class="title">Login</h2>

        <?php if (!empty($err_email)) echo "<div class='alert error'>$err_email</div>"; ?>
        <?php if (!empty($err_pwd)) echo "<div class='alert error'>$err_pwd</div>"; ?>

        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Accedi</button>
        </form>

        <p class="bottom-link">Non hai un account? <a href="register.php">Registrati</a></p>
    </div>
</body>
</html>
