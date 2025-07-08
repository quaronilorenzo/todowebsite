<?php
    session_start();
    require '../includes/connection.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $task_id = intval($_GET['id'] ?? 0);

    // Verifica che la task appartenga all'utente
    $query = $conn->prepare("SELECT id, nome_task, content, data_scadenza FROM tasks WHERE id = ? AND user_id = ?");
    $query->bind_param("ii", $task_id, $user_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows !== 1) {
        header("Location: dashboard.php");
        exit();
    }

    $task = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $change_nome = $_POST["nome_task"];
        $change_data_scadenza = $_POST["data_scadenza"];
        $change_content = $_POST["content"];
        
        $stmt = $conn->prepare('UPDATE tasks SET nome_task = ?, content = ?, data_scadenza = ? WHERE id = ? AND user_id = ?');
        $stmt->bind_param("sssii", $change_nome, $change_content, $change_data_scadenza, $task_id, $user_id);
        if($stmt->execute()){
            header("Location: ./dashboard.php");
            exit();
        }else{
            echo "<div class='alert error'>Errore nell'aggiornamento.</div>";
        }
       

    }   
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Modifica Task</title>
    <link rel="stylesheet" href="../css/modify_task.css">
</head>
<body>
    <div class="header">
        <h1>To Do List - Creatore Quaroni</h1>
        <p><a href="https://github.com/quaronilorenzo" target="_blank">github.com/quaronilorenzo</a></p>
    </div>

    <div class="container">
        <div class="">
            <h2>Modifica Task</h2>
        </div>
        <form method="POST">
            <input type="text" name="nome_task" value="<?= htmlspecialchars($task['nome_task']) ?>" required>
            <input type="text" name="content" value="<?= htmlspecialchars($task['content']) ?>" required>
            <input type="date" name="data_scadenza" value="<?= $task['data_scadenza'] ?>" min="<?= date('Y-m-d') ?>" required>
            <button type="submit">Salva modifiche</button>  
        </form>
    </div>
</body>
</html>