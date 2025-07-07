<?php
include 'includes/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $err = "";
    $msg = "";
    $email = $_POST["email"];
    $password = $_POST["password"];

    $check = $conn->prepare('SELECT id FROM users WHERE email = ?');
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $err = "Email già esistente. <a href='login.php'>Accedi</a>";
    } else {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $reg = $conn->prepare('INSERT INTO users(email, password) VALUES (?, ?)');
        $reg->bind_param("ss", $email, $password);
        if ($reg->execute()) {
            $msg = "Registrazione completata! <a href='login.php'>Accedi</a>.";
        } else {
            $err = "Errore durante la registrazione.";
        }
        $reg->close();
    }

    $check->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registrazione - To Do List</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
    <div class="header">
        <h1>To Do List</h1>
        <p>Creato da Quaroni • <a href="https://github.com/quaronilorenzo" target="_blank">GitHub</a></p>
    </div>

    <div class="container">
        <h2 class="title">Registrati</h2>

        <?php if (!empty($msg)) echo "<div class='alert success'>$msg</div>"; ?>
        <?php if (!empty($err)) echo "<div class='alert error'>$err</div>"; ?>

        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Registrati</button>
        </form>
    </div>
</body>
</html>
