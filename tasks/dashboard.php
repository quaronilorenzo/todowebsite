<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
    require '../includes/connection.php';
    $user_id = $_SESSION['user_id'];
    $email = $_SESSION['user_email'];
    $stmt = $conn->prepare("SELECT id, content, created_at FROM tasks WHERE user_id  = ?"); // prendo le task che ha l'utente tramite la FK 
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
    <h2>Dashboard - Home </h2>
    <button class="btn" onclick="location.href='add_task.php'">➕ Aggiungi Task</button>
    <button class="btn" onclick="toggleTasks()">📋 Vedi le Tue Task</button>
    <button class="btn" onclick="location.href='../logout.php'">🚪 Logout</button>

    <div id="taskList">
        <h3>Le tue task</h3>
        <?php if (empty($tasks)){
                echo "<p>Non hai ancora task!</p>";
            }else{
                foreach ($tasks as $task){
                        echo "<div class='task'>" . $task['content'] . "</div>";
                        echo "<div class='task'>creato il: " . $task['created_at'] . "</div>";
                }
            }
        ?>
    </div>

    <script src="../js/dashboard.js"></script>
</body>
</html>
