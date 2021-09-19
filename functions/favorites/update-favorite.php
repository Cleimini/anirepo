<?php
    include_once "../database-connection.php";
    
    if (!isset($_POST["Update_Favorite"]) || !isset($_POST["Favorite_ID"]) || !isset($_POST["Placement"]) || !isset($_SESSION["User_ID"])) {
        ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Unable to update favorite, refresh the page.</p> <?php
    } else {
        $Favorite_ID = trim($_POST["Favorite_ID"]);
        $Placement = trim(filter_var($_POST["Placement"], FILTER_SANITIZE_NUMBER_INT));

        try {
            $Select_Favorites = $Anirepo->prepare("SELECT * FROM favorites WHERE Favorite_ID = :Favorite_ID AND User_ID = :User_ID");
            $Select_Favorites->bindParam(":Favorite_ID", $Favorite_ID);
            $Select_Favorites->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Favorites->execute();
            $Favorite = $Select_Favorites->fetch();

            if ($Select_Favorites->rowCount() < 1) {
                ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Favorite not found, refresh the page.</p> <?php
            } else if (!is_numeric($Placement)) {
                ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Placement should be numeric.</p> <?php
            } else {
                try {
                    if ($Favorite["Favorite_Type"] == "Anime") {
                        $Log_Type = "Update Favorite Anime";
                    } else if ($Favorite["Favorite_Type"] == "Character") {
                        $Log_Type = "Update Favorite Character";
                    } else {
                        $Log_Type = "Update Favorite People";
                    }

                    $Update_Favorites = $Anirepo->prepare("UPDATE favorites SET Placement = :Placement, Date_Favorite_Updated = NOW() WHERE Favorite_ID = :Favorite_ID AND User_ID = :User_ID");
                    $Update_Favorites->bindParam(":Favorite_ID", $Favorite_ID);
                    $Update_Favorites->bindParam(":User_ID", $_SESSION["User_ID"]);
                    $Update_Favorites->bindParam(":Placement", $Placement);
                    $Update_Favorites->execute();

                    $Insert_Logs = $Anirepo->prepare("INSERT INTO logs VALUES ('', :User_ID, '$Log_Type', NOW())");
                    $Insert_Logs->bindParam(":User_ID", $_SESSION["User_ID"]);
                    $Insert_Logs->execute();

                    ?>

                    <p class="text-success"><i class="fas fa-check-circle"></i> Favorite has been updated.</p>

                    <script>
                        $(function() {
                            $("#Placement_<?= $Favorite_ID; ?>").html("<?= $Placement; ?>");
                        });
                    </script>

                    <?php
                } catch(PDOException $Update_Favorites_Insert_Logs) {
                    ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Update_Favorites_Insert_Logs:</b> <?= $Update_Favorites_Insert_Logs->getMessage(); ?>.</p> <?php
                }
            }
        } catch(PDOException $Select_Favorites) {
            ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Select_Favorites:</b> <?= $Select_Favorites->getMessage(); ?>.</p> <?php
        }
    }

    $Anirepo = null;
?>

<script>
    $(function() {
        $("#Update_Favorite").prop("disabled", false);
    });
</script>