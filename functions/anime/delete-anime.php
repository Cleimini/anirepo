<script>
    $(function() {
        $("#Delete_Anime").prop("disabled", false);
    });
</script>

<?php
    include_once "../database-connection.php";
    
    if (!isset($_POST["Delete_Anime"]) || !isset($_POST["Anime_ID"]) || !isset($_SESSION["User_ID"])) {
        ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Unable to delete anime, refresh the page.</p> <?php
    } else {
        $Anime_ID = trim($_POST["Anime_ID"]);

        try {
            $Select_Anime = $Anirepo->prepare("SELECT * FROM anime WHERE Anime_ID = :Anime_ID AND User_ID = :User_ID");
            $Select_Anime->bindParam(":Anime_ID", $Anime_ID);
            $Select_Anime->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Anime->execute();

            if ($Select_Anime->rowCount() < 1) {
                ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Anime not found, refresh the page.</p> <?php
            } else {
                try {
                    $Delete_Reviews = $Anirepo->prepare("DELETE FROM reviews WHERE Anime_ID = :Anime_ID");
                    $Delete_Reviews->bindParam(":Anime_ID", $Anime_ID);
                    $Delete_Reviews->execute();

                    $Delete_Monitorings = $Anirepo->prepare("DELETE FROM monitorings WHERE Anime_ID = :Anime_ID");
                    $Delete_Monitorings->bindParam(":Anime_ID", $Anime_ID);
                    $Delete_Monitorings->execute();

                    $Delete_Favorites = $Anirepo->prepare("DELETE FROM favorites WHERE Anime_ID = :Anime_ID AND User_ID = :User_ID");
                    $Delete_Favorites->bindParam(":Anime_ID", $Anime_ID);
                    $Delete_Favorites->bindParam(":User_ID", $_SESSION["User_ID"]);
                    $Delete_Favorites->execute();

                    $Delete_Anime = $Anirepo->prepare("DELETE FROM anime WHERE Anime_ID = :Anime_ID AND User_ID = :User_ID");
                    $Delete_Anime->bindParam(":Anime_ID", $Anime_ID);
                    $Delete_Anime->bindParam(":User_ID", $_SESSION["User_ID"]);
                    $Delete_Anime->execute();

                    ?>

                    <script>
                        $(function() {
                            var Refresh_Anime = true;

                            $("#Anime_Editor_Message").html("");

                            $("#Bootstrap_Modal").modal("hide");

                            $("#Delete_Anime").prop("disabled", true);

                            $("#Anime_Section_Placeholder").load("functions/anime/refresh-anime.php", {
                                Refresh_Anime: Refresh_Anime
                            });
                        });
                    </script>

                    <?php
                } catch(PDOException $Delete_Anime) {
                    ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Delete_Anime:</b> <?= $Delete_Anime->getMessage(); ?>.</p> <?php
                }
            }
        } catch(PDOException $Select_Anime) {
            ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Select_Anime:</b> <?= $Select_Anime->getMessage(); ?>.</p> <?php
        }
    }

    $Anirepo = null;
?>