<?php
    include_once "../database-connection.php";

    if (!isset($_POST["Update_Review"]) || !isset($_POST["Anime_ID"]) || !isset($_POST["Opinion"]) || !isset($_POST["Score"])) {
        ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Unable to udpate the reviewed anime, refresh the page.</p> <?php
    } else {
        $Anime_ID = trim(filter_var($_POST["Anime_ID"], FILTER_SANITIZE_NUMBER_INT));
        $Opinion = trim(filter_var($_POST["Opinion"], FILTER_SANITIZE_SPECIAL_CHARS));
        $Score = trim(filter_var($_POST["Score"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
        
        try {
            $Select_Reviews = $Anirepo->prepare("SELECT * FROM reviews
                JOIN anime ON reviews.Anime_ID = anime.Anime_ID
                WHERE reviews.Anime_ID = :Anime_ID AND anime.User_ID = :User_ID");
            $Select_Reviews->bindParam(":Anime_ID", $Anime_ID);
            $Select_Reviews->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Reviews->execute();

            if ($Select_Reviews->rowCount() < 1) {
                ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Reviewed anime not found, refresh the page.</p> <?php
            } else if (empty($Score)) {
                ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Score is required.</p> <?php
            } else if (!is_numeric($Score)) {
                ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Score must be numeric.</p> <?php
            } else {
                try {
                    $Update_Reviews = $Anirepo->prepare("UPDATE reviews
                        SET Opinion = :Opinion, Score = :Score, Date_Review_Updated = NOW()
                        WHERE Anime_ID = :Anime_ID");
                    $Update_Reviews->bindParam(":Anime_ID", $Anime_ID);
                    $Update_Reviews->bindParam(":Opinion", $Opinion);
                    $Update_Reviews->bindParam(":Score", $Score);
                    $Update_Reviews->execute();

                    $Insert_Logs = $Anirepo->prepare("INSERT INTO logs VALUES ('', :User_ID, 'Update Review', NOW())");
                    $Insert_Logs->bindParam(":User_ID", $_SESSION["User_ID"]);
                    $Insert_Logs->execute();

                    ?>

                    <p class="text-success"><i class="fas fa-check-circle"></i> Reviewed anime has been updated.</p>

                    <script>
                        $(function() {
                            $("#Score_<?= $Anime_ID; ?>").html("<?= round($Score); ?>");
                        });
                    </script>

                    <?php
                } catch(PDOException $Update_Reviews) {
                    ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Update_Reviews:</b> <?= $Update_Reviews->getMessage(); ?>.</p> <?php
                }
            }
        } catch(PDOException $Select_Reviews) {
            ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Select_Reviews:</b> <?= $Select_Reviews->getMessage(); ?>.</p> <?php
        }
    }

    $Anirepo = null;
?>

<script>
    $(function() {
        $("#Update_Review").prop("disabled", false);
    });
</script>