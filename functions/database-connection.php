<?php
    date_default_timezone_set("Asia/Manila");
    session_start();

    $Database_Name = "anirepo";
    $Server_Host = "localhost";
    $Server_Password = "";
    $Server_Username = "root";

    try {
        $Anirepo = new PDO("mysql:host=$Server_Host;dbname=$Database_Name;charset=utf8", $Server_Username, $Server_Password);
        $Anirepo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $Disconnected = false;
        $Error = 0;
        $Error_Box = $Modal_Error_Box = $Error_Message = "";
        $Waiting_Message = "<p class='text-info'><i class='fas fa-hand-paper'></i> Wait for a moment.</p>";

        $Error_Box .= "<div class='bg-danger border border-white p-3 rounded text-center text-white user-select-none'>";
        $Error_Box .= "<h1 class='Large-Font-Size'><i class='fas fa-exclamation-triangle'></i></h1>";

        $Modal_Error_Box .= "<div class='text-center text-danger user-select-none'>";
            $Modal_Error_Box .= "<h1 class='Large-Font-Size'><i class='fas fa-exclamation-triangle'></i></h1>";
            $Modal_Error_Box .= "<h4><strong>UNABLE TO SHOW THE CONTENTS</strong></h4>";

            $Modal_Error_Box .= "<p>Refresh the page.</p>";
        $Modal_Error_Box .= "</div>";
    } catch(PDOException $Disconnected_Message) {
        session_destroy();

        $Disconnected = true;
    }
?>