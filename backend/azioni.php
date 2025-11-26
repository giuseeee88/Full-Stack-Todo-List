<?php
    if(!isset($_SESSION)) session_start();

    require "connessionedb.php";

    if(isset($_GET["azione"])) $azione = $_GET["azione"];

    switch($azione){
        case "aggiorna":
            if (isset($_GET["elementi"])) {
                $elementi = $_GET["elementi"];

                $id_utente = $_SESSION["id"];

                if($elementi === "last"){
                    $result = $conn->query("SELECT MAX(id) AS last_id FROM attività WHERE id_utente = '$id_utente'");
                    $row = $result->fetch_assoc();
                    $last_id = $row["last_id"];
                    if(is_numeric($last_id)) $result = $conn->query("DELETE FROM attività WHERE id = $last_id and id_utente = '$id_utente'");
                }

                else{
                    $elementi_array = explode(',', $elementi);

                    foreach ($elementi_array as $elemento) {
                        $elemento = trim($elemento);
                        if (is_numeric($elemento)) {
                            $result = $conn->query("DELETE FROM attività WHERE id = $elemento and id_utente = '$id_utente'");
                        }
                    }
                }
            }

            break;

        case "aggiungi":
            if(isset($_GET["descrizione"]) && isset($_GET["stato"])){
                $descrizione = $_GET["descrizione"];
                $stato = $_GET["stato"];
                $data_inserimento = date("Y/m/d") . " " . date("h:i:sa");
        
                $id_utente = $_SESSION["id"];
                
                $stmt = $conn->prepare("SELECT * FROM attività WHERE descrizione = ? and id_utente = ?");
                $stmt->bind_param("si", $descrizione, $id_utente);
                $stmt->execute();
                $result = $stmt->get_result();
        
                if($result->num_rows === 0){
                    $stmt->close();
        
                    $stmt = $conn->prepare("INSERT INTO attività (descrizione, stato, data_inserimento, id_utente) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("sssi", $descrizione, $stato, $data_inserimento, $id_utente);
                    $stmt->execute();
                }
        
                $stmt->close();
            }
            
            break;

        case "modifica":
            if(isset($_GET["numero_elemento"])) $numero_elemento = $_GET["numero_elemento"];
            if(isset($_GET["nuova_descrizione"])) $nuova_descrizione = $_GET["nuova_descrizione"];
            if(isset($_GET["nuovo_stato"])) $nuovo_stato = $_GET["nuovo_stato"];

            $id_utente = $_SESSION["id"];

            if(isset($numero_elemento)){
                $stmt = $conn->prepare("UPDATE attività SET descrizione = ?, stato = ? WHERE id = ? and id_utente = ?");
                $stmt->bind_param("ssii", $nuova_descrizione, $nuovo_stato, $numero_elemento, $id_utente);
                $stmt->execute();
            }

            break;
    }

    include "tasks.php";

    $conn->close();

?>