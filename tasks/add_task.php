<?php
    /*
        Mostra un form per inserire una nuova task. ok
        Inserisce la task nel database legandola all'utente loggato. ok
        Reindirizza l’utente alla dashboard.
    */
    require '../includes/connection.php';
    session_start();
    if (!isset($_SESSION['user_id'])) {
            header("Location: ../login.php");
            exit();
    }   
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $user_id = $_SESSION['user_id']; // metto l'id del user della sessione 
        $content = $_POST['content'];
        $data_scadenza = $_POST['data_scadenza'];
        $nome_task = $_POST['nome_task'];
 
        $ok = true; // se c'è anche un solo campo vuoto non viene eseguita la query e ok diventerà un false, e farà uscire come errore il primo campo non compilato
        if (empty($nome_task)){
            $err = "Il nome non può essere vuoto.";
            $ok = false;
        }
        if (empty($content) && $ok == true){
            $err = "Descrizione obbligatoria.";
            $ok = false;
        }
        if (empty($data_scadenza) && $ok == true){
            $err = "La data di scadenza è obbligatoria.";
            $ok = false;
        }    
        if($ok == true){            
            $check = $conn->prepare('SELECT nome_task FROM tasks WHERE nome_task = ? AND user_id = ?');
            $check->bind_param("si", $nome_task, $user_id);
            $check->execute();
            $check->store_result();
            if($check->num_rows > 0){
                echo "Hai giù una task con questo nome!";
            }else{
                $stmt = $conn->prepare('INSERT INTO tasks(content, data_scadenza, user_id,nome_task) VALUES (?,?,?,?)');
                $stmt->bind_param("ssis",$content,$data_scadenza,$user_id, $nome_task);
                if ($stmt->execute()) {
                    header("Location: dashboard.php?success=1");
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

<h2>Aggiungi nuova Task</h2>

<?php if (!empty($err)) echo "<p class='error'>$msg</p>"; ?>

<form method="POST" action="">
    <label for="nome_task">Nome Task:</label><br>
    <input type="text" name="nome_task" required><br>

    <label for="content">Descrizione:</label><br>
    <textarea name="content" rows="4" required></textarea><br>

    <label for="scadenza">Data Scadenza:</label><br>
    <input type="date" name="data_scadenza" required><br>

    <button type="submit">Aggiungi</button>
</form>



