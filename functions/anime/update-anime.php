<?php
    include_once "../database-connection.php";

    if (!isset($_POST["Update_Anime"]) || !isset($_POST["Anime_ID"]) || !isset($_POST["Monitoring_Type"]) || !isset($_POST["Date_Started"]) || !isset($_POST["Date_Finished"]) || !isset($_POST["Date_Scheduled"]) || !isset($_SESSION["User_ID"])) {
        ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Unable to update anime, refresh the page.</p> <?php
    } else {
        $Anime_ID = trim(filter_var($_POST["Anime_ID"], FILTER_SANITIZE_NUMBER_INT));
        $Monitoring_Type = trim($_POST["Monitoring_Type"]);
        $Date_Started = preg_replace("([^0-9/])", "", $_POST["Date_Started"]);
        $Date_Finished = preg_replace("([^0-9/])", "", $_POST["Date_Finished"]);
        $Date_Scheduled = preg_replace("([^0-9/])", "", $_POST["Date_Scheduled"]);

        try {
            $Select_Anime = $Anirepo->prepare("SELECT * FROM anime WHERE Anime_ID = :Anime_ID AND User_ID = :User_ID");
            $Select_Anime->bindParam(":Anime_ID", $Anime_ID);
            $Select_Anime->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Anime->execute();
            $Anime = $Select_Anime->fetch();

            if ($Select_Anime->rowCount() < 1) {
                ?> <p><i class="fas fa-times-circle"></i> Anime not found, refresh the page.</p> <?php
            } else {
                if (!in_array($Monitoring_Type, array("Finished", "Currently", "Postponed", "Scheduled"))) {
                    $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Invalid monitoring type value.</p> <?php
                } else {
                    if ($Monitoring_Type == "Finished") {
                        if (!empty($Date_Started)) {
                            if (!DateTime::createFromFormat("Y-m-d", $Date_Started)) {
                                $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Invalid date started format.</p> <?php
                            } else if (!empty($Date_Finished) && $Date_Started > $Date_Finished) {
                                $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Date started should not be later than the date finished.</p> <?php
                            }
                        } else {
                            $Date_Started = NULL;
                        }
    
                        if (!empty($Date_Finished)) {
                            if (!DateTime::createFromFormat("Y-m-d", $Date_Finished)) {
                                $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Invalid date finished format.</p> <?php
                            } else if (!empty($Date_Started) && $Date_Finished < $Date_Started) {
                                $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Date finished should not be earlier than the date started.</p> <?php
                            }
                        } else {
                            $Date_Finished = NULL;
                        }
    
                        $Date_Scheduled = NULL;
                    } else if ($Monitoring_Type == "Scheduled") {
                        if (empty($Date_Scheduled)) {
                            $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Date scheduled is required.</p> <?php
                        } else if (!DateTime::createFromFormat("Y-m-d", $Date_Scheduled)) {
                            $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Invalid date scheduled format.</p> <?php
                        }
    
                        $Date_Started = $Date_Finished = NULL;
                    } else {
                        if (!empty($Date_Started) && !DateTime::createFromFormat("Y-m-d", $Date_Started)) {
                            $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Invalid date started format.</p> <?php
                        } else {
                            $Date_Started = NULL;
                        }
    
                        $Date_Finished = $Date_Scheduled = NULL;
                    }
                }

                if ($Error < 1) {
                    try {
                        $Update_Monitorings = $Anirepo->prepare("UPDATE monitorings
                            SET Monitoring_Type = :Monitoring_Type,
                                Date_Started = :Date_Started,
                                Date_Finished = :Date_Finished,
                                Date_Scheduled = :Date_Scheduled,
                                Date_Monitoring_Updated = NOW()
                            WHERE Anime_ID = :Anime_ID");
                        $Update_Monitorings->bindParam(":Anime_ID", $Anime_ID);
                        $Update_Monitorings->bindParam(":Monitoring_Type", $Monitoring_Type);
                        $Update_Monitorings->bindParam(":Date_Started", $Date_Started);
                        $Update_Monitorings->bindParam(":Date_Finished", $Date_Finished);
                        $Update_Monitorings->bindParam(":Date_Scheduled", $Date_Scheduled);
                        $Update_Monitorings->execute();

                        $Insert_Logs = $Anirepo->prepare("INSERT INTO logs VALUES ('', :User_ID, 'Update Anime', NOW())");
                        $Insert_Logs->bindParam(":User_ID", $_SESSION["User_ID"]);
                        $Insert_Logs->execute();

                        ?>

                        <p class="text-success"><i class="fas fa-check-circle"></i> Anime has been updated.</p>

                        <script>
                            $(function() {
                                <?php
                                    if ($Monitoring_Type == "Finished") {
                                        $Color_Class_Name = "bg-success text-white";
                                    } else if ($Monitoring_Type == "Currently") {
                                        $Color_Class_Name = "bg-primary text-white";
                                    } else if ($Monitoring_Type == "Postponed") {
                                        $Color_Class_Name = "bg-danger text-white";
                                    } else if ($Monitoring_Type == "Scheduled") {
                                        $Color_Class_Name = "bg-warning text-dark";
                                    }
                                ?>

                                $("#Anime_Title_Placeholder_<?= $Anime_ID; ?>").removeClass("bg-success bg-primary bg-danger bg-warning text-white text-dark").addClass("<?= $Color_Class_Name; ?>");
                            });
                        </script>

                        <?php
                    } catch(PDOException $Update_Monitorings_Insert_Logs) {
                        $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Update_Monitorings_Insert_Logs:</b> <?= $Update_Monitorings_Insert_Logs->getMessage(); ?>.</p> <?php
                    }
                }
            }
        } catch(PDOException $Select_Anime) {
            ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Select_Anime:</b> <?= $Select_Anime->getMessage(); ?>.</p> <?php
        }
    }

    $Anirepo = null;
?>

<script>
    $(function() {
        $("#Update_Anime").prop("disabled", false);
    });
</script>