<script>
    $(function() {
        $("#Delete_Review").prop("disabled", false);
    });
</script>

<?php
    include_once "../database-connection.php";

    if (!isset($_POST["Delete_Review"]) || !isset($_POST["Anime_ID"]) || !isset($_SESSION["User_ID"])) {
        ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Unable to delete the reviewed anime, refresh the page.</p> <?php
    } else {
        $Anime_ID = trim($_POST["Anime_ID"]);

        try {
            $Select_Reviews = $Anirepo->prepare("SELECT * FROM reviews
                JOIN anime ON reviews.Anime_ID = anime.Anime_ID
                WHERE reviews.Anime_ID = :Anime_ID AND anime.User_ID = :User_ID");
            $Select_Reviews->bindParam(":Anime_ID", $Anime_ID);
            $Select_Reviews->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Reviews->execute();

            if ($Select_Reviews->rowCount() < 1) {
                ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Reviewed anime not found, refresh the page.</p> <?php
            } else {
                try {
                    $Delete_Reviews = $Anirepo->prepare("DELETE FROM reviews WHERE Anime_ID = :Anime_ID");
                    $Delete_Reviews->bindParam(":Anime_ID", $Anime_ID);
                    $Delete_Reviews->execute();
                    
                    $Insert_Logs = $Anirepo->prepare("INSERT INTO logs VALUES ('', :User_ID, 'Delete Review', NOW())");
                    $Insert_Logs->bindParam(":User_ID", $_SESSION["User_ID"]);
                    $Insert_Logs->execute();

                    ?>

                    <script>
                        $(function() {
                            var Refresh_Reviews = true;

                            $("#Review_Editor_Message").html("");

                            $("#Bootstrap_Modal").modal("hide");

                            $("#Delete_Review").prop("disabled", true);

                            $("#Reviews_Placeholder").load("functions/reviews/refresh-reviews.php", {
                                Refresh_Reviews: Refresh_Reviews
                            });
                        });
                    </script>

                    <?php
                } catch(PDOException $Delete_Reviews) {
                    ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Delete_Reviews:</b> <?= $Delete_Reviews->getMessage(); ?>.</p> <?php
                }
            }
        } catch(PDOException $Select_Reviews) {
            ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Select_Reviews:</b> <?= $Select_Reviews->getMessage(); ?>.</p> <?php
        }
    }

    $Anirepo = null;
?>