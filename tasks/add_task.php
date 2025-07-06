<?php
    /*
        Mostra un form per inserire una nuova task.

        Inserisce la task nel database legandola all'utente loggato.

        Reindirizza l’utente alla dashboard.

    */
    require '../includes/connection.php';
    session_start();
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";  
    
    if (!isset($_SESSION['user_id'])) {
            header("Location: ../login.php");
            exit();
    }   
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $id = $_SESSION['user_id']; // definisco il valore
        // echo $_SESSION['user_id'];
        $content = $_POST['content'];
        $data_scadenza = $_POST['data_scadenza'];
        $stmt = $conn->prepare('INSERT INTO tasks(content, data_scadenza) VALUES (?,?)');
    }
?>