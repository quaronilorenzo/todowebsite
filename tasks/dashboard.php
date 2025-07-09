<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
    require '../includes/connection.php';
    $user_id = $_SESSION['user_id'];
    $email = $_SESSION['user_email'];
    $stmt = $conn->prepare("SELECT id, content, created_at, nome_task, data_scadenza, completato FROM tasks WHERE user_id  = ?"); // prendo le task che ha l'utente tramite la FK 
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $tasks = $result->fetch_all(MYSQLI_ASSOC); // tasks contiene tutte le task del utente in un array tasks[0]['content'] = modifica 
    $stmt->close(); 
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h2>Dashboard - Home</h2>
        </header>

        <div class="btn-group">
            <button class="btn add-task" onclick="location.href='add_task.php'">➕ Aggiungi Task</button>
            <button class="btn view-tasks" onclick="toggleTasks()">📋 Vedi le Tue Task</button>
            <button class="btn logout" onclick="location.href='../logout.php'">🚪 Logout</button>
        </div>

        <div id="taskList">
            <h3>Le tue task</h3>
            <?php if (empty($tasks)) {
                echo "<p>Non hai ancora task!</p>";
            } ?>

            <?php if(!empty($tasks)){ foreach ($tasks as $task): ?>
                <div class="task">
                    <form method="post" action="./toggle_tasks.php" style="display: inline;">
                        <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                        <input type="checkbox" name="completed" onchange="this.form.submit()" <?= !empty($task['completato']) ? 'checked' : '' ?>>
                    </form>

                    <strong>Nome:</strong> <?= htmlspecialchars($task['nome_task']) ?><br>
                    <strong>Contenuto:</strong> <?= htmlspecialchars($task['content']) ?><br>
                    <strong>Data di scadenza:</strong> <?= htmlspecialchars($task['data_scadenza']) ?><br>
                    <small>Creato il: <?= htmlspecialchars($task['created_at']) ?></small><br>

                    <button onclick="location.href='delete_task.php?id=<?= $task['id'] ?>'">🗑️ Elimina</button>
                    <button onclick="location.href='modify_task.php?id=<?= $task['id'] ?>'">✏️ Modifica</button>
                </div>
                <hr class="separator">
            <?php endforeach; }?>

            
            
        </div>
    </div>

    <script src="../js/dashboard.js"></script>
</body>
</html>
