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
    $query = $conn->prepare("SELECT id, nome_task FROM tasks WHERE id = ? AND user_id = ?");
    $query->bind_param("ii", $task_id, $user_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows !== 1) {
        header("Location: dashboard.php");
        exit();
    }

    $task = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
            $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ii", $task_id, $user_id);
            $stmt->execute();
            $stmt->close();
        }
        header("Location: dashboard.php");
        exit();
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Conferma Eliminazione</title>
    <link rel="stylesheet" href="../css/delete_task.css">
</head>
<body>
    <div class="container">
        <h2>Sei sicuro di voler eliminare questa task?</h2>
        <form method="POST">
            <button type="submit" name="confirm" value="yes" class="confirm">Sì, elimina</button>
            <button type="submit" name="confirm" value="no" class="cancel">No, torna indietro</button>
        </form>
    </div>
</body>
</html>