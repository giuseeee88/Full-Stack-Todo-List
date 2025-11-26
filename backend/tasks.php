<?php
    if(!isset($_SESSION)){
        session_start();
        if(!isset($_SESSION["username"]) || empty($_SESSION["username"])) header("Location: ../frontend/login.html");
    }
?>
<div id="contenuto" class="container">
    <div class="row">
        <h1 id="titolo-principale">Benvenuto <?php echo $_SESSION["username"]; ?></h1>
        <div class="tasti">
            <button class="btn btn-light info comando" onclick="logout()">
                <span class="material-symbols-outlined">
                    logout
                </span>
            </button>
            <button class="btn btn-light info comando" data-bs-toggle="modal" data-bs-target="#exampleModal1">
                <span class="material-symbols-outlined">
                    info
                </span>
            </button>
            <button id="statistiche" class="btn btn-light info comando" data-bs-toggle="modal" data-bs-target="#exampleModal2">
                <span class="material-symbols-outlined">
                    monitoring
                </span>
            </button>
            <button id="aggiungi-task" class="btn btn-light comando" onclick="addTask()">
                <span class="material-symbols-outlined">
                    add_task
                </span>
            </button>
            <button id="elimina-task" class="btn btn-light comando" onclick="deleteTask(document.getElementsByClassName('campo'))">
                <span class="material-symbols-outlined">
                    delete
                </span>
            </button>
        </div>
    </div>
        <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Informazioni sulle shortcut</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
                </div>
                <div class="modal-body">
                [CTRL + A] : Crea una nuova task
                <br>
                [CTRL + E] : Elimina l'ultima task
                <br>
                [CTRL + Q] : Visualizza le statistiche
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Chiudi</button>
                </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Statistiche tasks</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
                </div>
                <div class="modal-body">
                    <?php
                        $stati_task = ["Incomplete", "Completate"];
                        $id_utente = $_SESSION["id"];
                        $result = $conn->query("SELECT COUNT(*) AS numero_task FROM attività WHERE id_utente = '$id_utente'");
                        $row = $result->fetch_assoc();
                        $numero_task = $row["numero_task"];

                        echo "Hai creato in tutto $numero_task task<br>";
                        
                        $result = $conn->query("SELECT COUNT(*) AS task_completate FROM attività WHERE stato = 1 and id_utente = '$id_utente'");
                        $row = $result->fetch_assoc();
                        $task_completate = $row["task_completate"];
                        $task_incomplete = $numero_task - $task_completate;
                        
                        echo "Task completate: $task_completate<br>";
                        echo "Task incomplete: $task_incomplete";
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>

    <?php
        $result = $conn->query("SELECT COUNT(*) AS numero_task FROM attività WHERE id_utente = '$id_utente'");
        $row = $result->fetch_assoc();
        $numero_task = $row["numero_task"];
        if($numero_task > 0){
            echo "<br><table id='task'>";
            echo "<th></th><th>Descrizione</th><th>Data inserimento</th>";

            $result = $conn->query("SELECT * FROM attività WHERE id_utente = '$id_utente'");

            while($row = $result->fetch_assoc()){
                echo "<tr class='elemento'>";
                    $id = $row["id"];
                    $descrizione = $row["descrizione"];
                    $stato = $row["stato"];
                    $data_inserimento = $row["data_inserimento"];
                    $style = $stato == 0 ? "text-decoration: none;" : "text-decoration: line-through;";
                    echo "<td><input type='checkbox' class='campo' value='$id'></td>";
                    echo "<td><div contenteditable='false' style='$style' class='descrizione' id='descrizione-$id'>$descrizione</div></td>";
                    echo "<td><div class='descrizione'>$data_inserimento</div></td>";
                    echo "
                        <td id='task-$id'>
                            <button class='btn btn-light comando' value='$id' onclick='editTask(this)'>
                                <span class='material-symbols-outlined' id='edit-task-$id'>
                                    edit
                                </span>
                            </button>
                        </td>
                    ";
                echo "</tr>";
            }
            echo "</table>";
        }
        else echo "<br><h4>Aggiungi una task</h4>";
    ?>
</div>
<script src="../frontend/autenticazione.js"></script>