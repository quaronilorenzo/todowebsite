    <?php
        include 'includes/connection.php';
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $err = ""; // variabile per il messaggio d'errore
            $msg = ""; // variabile per il messaggio corretto
            $email = $_POST["email"];
            $password = $_POST["password"];
            $check = $conn->prepare('SELECT id FROM users WHERE email = ?'); // guardo se esiste già l'email
            $check->bind_param("s", $email);  
            $check->execute();
            $check->store_result();
            if($check->num_rows > 0){
                $err = "Email già esistente. Accedi";
            }else{
                $password = password_hash($password, PASSWORD_DEFAULT);
                $reg = $conn->prepare('INSERT INTO users(email, password) VALUES (?,?)');
                $reg->bind_param("ss",$email, $password);
                if($reg->execute()){
                    $msg = "Benvenuto, registrazione completata! Ora accedi.";
                }else{
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
        <title>Registrazione</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <div class="reg-title"><h2>Registrati</h2></div>
        <?php if (isset($msg)) echo "<div class='msg'>$msg</div>"; ?>
        <?php if (isset($err)) echo "<div class='err'>$err</div>"; ?>
        <div class="reg">
            <form method="POST" action="">
                <input type="email" name="email" placeholder="Email" required><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <button type="submit">Registrati</button>
            </form>
        </div>
    </body>
    </html>