<?php
    session_start();
    include 'includes/connection.php';
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $msg = ""; // messaggio entrare dentro il vero programma (non ancora realizzato)
        $err_email = "";
        $err_pwd = "";

        $email = $_POST["email"];
        $password = $_POST["password"];
        
        $search = $conn->prepare('SELECT id, password FROM users WHERE email = ?');
        $search->bind_param("s", $email); 
        $search->execute();
        $search->store_result();
        if($search->num_rows == 1){
            $search->bind_result($id, $hash);
            $search->fetch();
            if(password_verify($password,$hash)){
                $msg = "Benvenuto!";
                $_SESSION['user_id'] = $id;
            }else{
                $err_pwd = "<p>Password errata, riprova!</p>";
            }
        }else{
            $err_email = "<p>Email non trovata</p>";
        }
    }
    session_write_close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="./css/login_style.css">
</head>
<body>
    <div class="log-title">
        <h2>Login</h2>
    </div>
    <?php if (isset($msg)) echo "<div class='msg'>" . $msg . "</div>"; ?>
    <?php if (isset($err_email)) echo "<div class='err_email'>" . $err_email . "</div>"; ?>
    <?php if (isset($err_pwd)) echo "<div class='err_pwd'>" . $err_pwd . "</div>"; ?>
    <div class="log">
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Accedi</button>
        </form>
    </div>
</body>
</html>