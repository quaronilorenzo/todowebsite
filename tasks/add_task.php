<?php
    require '../includes/connection.php';
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit();
    }   
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $user_id = $_SESSION['user_id'];
        $content = $_POST['content'];
        $data_scadenza = $_POST['data_scadenza'];
        $nome_task = $_POST['nome_task'];

        $ok = true;
        if (empty($nome_task)){
            $err = "Il nome non può essere vuoto.";
            $ok = false;
        }
        if (empty($content) && $ok){
            $err = "Descrizione obbligatoria.";
            $ok = false;
        }
        if (empty($data_scadenza) && $ok){
            $err = "La data di scadenza è obbligatoria.";
            $ok = false;
        }    
        if($ok){
            $check = $conn->prepare('SELECT nome_task FROM tasks WHERE nome_task = ? AND user_id = ?');
            $check->bind_param("si", $nome_task, $user_id);
            $check->execute();
            $check->store_result();
            if($check->num_rows > 0){
                $err = "Hai già una task con questo nome!";
            } else {
                $stmt = $conn->prepare('INSERT INTO tasks(content, data_scadenza, user_id, nome_task) VALUES (?, ?, ?, ?)');
                $stmt->bind_param("ssis", $content, $data_scadenza, $user_id, $nome_task);
                if ($stmt->execute()) {
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $err = "Errore nell'inserimento. Riprova.";
                }            
                $stmt->close();
            }
            $check->close();
        }
    }   
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Aggiungi Task</title>
    <link rel="stylesheet" href="../css/add_task.css">
</head>
<body>

    <div class="header">
        <h1>To Do List - Creatore: Quaroni</h1>
        <p><a href="https://github.com/quaronilorenzo/todowebsite" target="_blank">GitHub Repository</a></p>
    </div>

    <div class="container">
        <div class="title">
            <h2>Aggiungi una nuova Task</h2>
        </div>

        <?php if (!empty($err)) echo "<div class='alert error'>$err</div>"; ?>
        
        <form method="POST" action="">
            <input type="text" name="nome_task" placeholder="Nome Task" required>

            <textarea name="content" rows="4" placeholder="Descrizione" required></textarea>

            <input type="date" name="data_scadenza"required min="<?= date('Y-m-d') ?>">

            <button type="submit">Aggiungi</button>
        </form>
    </div>
</body>
</html>
