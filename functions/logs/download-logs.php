<?php
    include_once "../database-connection.php";

    if (!isset($_POST["Download_Logs"]) || !isset($_SESSION["User_ID"])) {
        ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Unable to download the logs, refresh the page.</p> <?php
    } else {
        try {
            $Select_Logs = $Anirepo->prepare("SELECT * FROM logs WHERE User_ID = :User_ID");
            $Select_Logs->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Logs->execute();
            $Logs = $Select_Logs->fetchAll();

            if ($Select_Logs->rowCount() < 1) {
                ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Logs not found, refresh the page.</p> <?php
            } else {
                $File = "../../generated/" . $_SESSION["User_ID"] . ".txt";
                $Open_File = fopen($File, "w") or die("<p class='text-danger'><i class='fas fa-times-circle'></i> Unable to open the file, refresh the page.</p>");

                foreach ($Logs as $Log) {
                    $Log_ID = $Log["Log_ID"];
                    $Log_Type = $Log["Log_Type"];
                    $Date_Cataloged = date("M d, Y h:i:s A", strtotime($Log["Date_Cataloged"]));
                    $Data = $Log_ID . "; " . $Log_Type . "; " . $Date_Cataloged . PHP_EOL;

                    fwrite($Open_File, "$Data");
                }

                fclose($Open_File);

                header("Content-Description: File Transfer");
                header("Content-Disposition: attachment; filename=" . basename($File));
                header("Expires: 0");
                header("Cache-Control: must-revalidate");
                header("Pragma: public");
                header("Content-Length: " . filesize($File));
                header("Content-Type: text/plain");

                ?> <p class="text-success"><i class="fas fa-check-circle"></i> File ready to be downloaded. <a download="<?= $_SESSION['User_ID']; ?>.txt" href='anime/generated/<?= $File; ?>' target="_BLANK">Click to Download!</a></p> <?php
            }
        } catch(PDOException $Select_Logs) {
            ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Select_Logs:</b> <?= $Select_Logs->getMessage(); ?>.</p> <?php
        }
    }

    $Anirepo = null;
?>

<script>
    $(function() {
        $("#Download_Logs").prop("disabled", false);
    });
</script>