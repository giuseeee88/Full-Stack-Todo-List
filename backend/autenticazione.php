<?php
    if(!isset($_SESSION)) session_start();

    require "connessionedb.php";
    
    if(isset($_GET["azione"])) $azione = $_GET["azione"];
    if(isset($_POST["azione"])) $azione = $_POST["azione"];

    switch($azione){
        case "registrazione":
            $username = $_POST["username"];
            $email = $_POST["emailAddress"];
            $password = $_POST["password"];

            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $result = $conn->query("SELECT * FROM utenti WHERE email = '$email'");

            if($result->num_rows == 0){
                $sql = "INSERT INTO utenti(username, email, passkey) VALUES ('$username', '$email', '$password_hash')";
                $result = $conn->query($sql);

                echo "<div class='w-full mx-auto text-white bg-emerald-500'>";
                    echo "<div class='container flex-row items-center justify-between px-6 py-4 mx-auto'>";
                        echo "<div class='flex'>";
                            echo "<svg viewBox='0 0 40 40' class='w-6 h-6 fill-current'>";
                                echo "<path d='M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM16.6667 28.3333L8.33337 20L10.6834 17.65L16.6667 23.6166L29.3167 10.9666L31.6667 13.3333L16.6667 28.3333Z'>";
                                echo "</path>";
                            echo "</svg>";

                            echo "<p class='mx-3'>Registrazione effettuata con successo</p>";
                        echo "</div>";

                        echo "<button class='p-1 transition-colors duration-300 transform rounded-md hover:bg-opacity-25 hover:bg-gray-600 focus:outline-none' onclick='document.getElementById(\"popupSuccess\").style.display = document.getElementById(\"popupError\").style.display = \"none\"'>";
                            echo "<svg class='w-5 h-5' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>";
                                echo "<path d='M6 18L18 6M6 6L18 18' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' />";
                            echo "</svg>";
                        echo "</button>";
                    echo "</div>";
                echo "</div>";
            }
        break;

        case "login":
            if(isset($_POST["emailAddress"])) $email = $_POST["emailAddress"];
            if(isset($_POST["password"])) $password = $_POST["password"];

            if(!empty($email) && !empty($password)){
                $result = $conn->query("SELECT * FROM utenti WHERE email = '$email'");
                if($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    $password_hash = $row["passkey"];
                    $username = $row["username"];
                    $id_utente = $row["id"];

                    if(password_verify($password, $password_hash)){
                        $_SESSION["username"] = $username;
                        $_SESSION["id"] = $id_utente;
                        header("Location: index.php");
                    }

                    else header("Location: ../frontend/index.html");
                }

                else header("Location: ../frontend/registrazione.html");
            }

            else header("Location: ../frontend/registrazione.html");
        break;

        case "logout":
            $_SESSION["username"] = "";
        break;
    }

    $conn->close();
?>