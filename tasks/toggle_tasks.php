<?php
session_start();
require '../includes/connection.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['task_id'])) {
    header("Location: ./dashboard.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$task_id = intval($_POST['task_id']);

$stmt = $conn->prepare("SELECT completato FROM tasks WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $task_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: ./dashboard.php");
    exit;
}

$row = $result->fetch_assoc();
$completed = $row['completato'] ? 0 : 1; // toggle vero e proprio

$update = $conn->prepare("UPDATE tasks SET completato = ? WHERE id = ? AND user_id = ?");
$update->bind_param("iii", $completed, $task_id, $user_id);
$update->execute();

header("Location: ./dashboard.php");
exit;
?>
