<?php
    include_once "../database-connection.php";

    if (!isset($_POST["Review_Anime"]) || !isset($_POST["Selected_Anime_ID"]) || !isset($_POST["Opinion"]) || !isset($_POST["Score"]) || !isset($_SESSION["User_ID"])) {
        ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Unable to review anime, refresh the page.</p> <?php
    } else {
        $Selected_Anime_ID = trim(filter_var($_POST["Selected_Anime_ID"], FILTER_SANITIZE_NUMBER_INT));
        $Opinion = trim(filter_var($_POST["Opinion"], FILTER_SANITIZE_SPECIAL_CHARS));
        $Score = trim(filter_var($_POST["Score"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));

        try {
            $Select_Anime = $Anirepo->prepare("SELECT * FROM anime WHERE Anime_ID = :Anime_ID AND User_ID = :User_ID");
            $Select_Anime->bindParam(":Anime_ID", $Selected_Anime_ID);
            $Select_Anime->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Anime->execute();

            $Select_Reviews = $Anirepo->prepare("SELECT * FROM reviews
                JOIN anime ON reviews.Anime_ID = anime.Anime_ID
                WHERE reviews.Anime_ID = :Anime_ID AND anime.User_ID = :User_ID");
            $Select_Reviews->bindParam(":Anime_ID", $Selected_Anime_ID);
            $Select_Reviews->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Reviews->execute();

            if ($Select_Anime->rowCount() < 1) {
                ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Anime not found.</p> <?php
            } else if (empty($Score)) {
                ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Score is required.</p> <?php
            } else if (!is_numeric($Score)) {
                ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Score must be numeric.</p> <?php
            } else if ($Select_Reviews->rowCount() > 0) {
                ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Anime has already been reviewed.</p> <?php
            } else {
                try {
                    $Insert_Reviews = $Anirepo->prepare("INSERT INTO reviews VALUES (:Anime_ID, :Opinion, :Score, NOW(), NULL)");
                    $Insert_Reviews->bindParam(":Anime_ID", $Selected_Anime_ID);
                    $Insert_Reviews->bindParam(":Opinion", $Opinion);
                    $Insert_Reviews->bindParam(":Score", $Score);
                    $Insert_Reviews->execute();

                    $Insert_Logs = $Anirepo->prepare("INSERT INTO logs VALUES ('', :User_ID, 'Review Anime', NOW())");
                    $Insert_Logs->bindParam(":User_ID", $_SESSION["User_ID"]);
                    $Insert_Logs->execute();

                    ?>

                    <p class="text-success"><i class="fas fa-check-circle"></i> Anime has been reviewed.</p>

                    <script>
                        $(function() {
                            var Refresh_Reviews = true;

                            $("#Anime_Title_Searches").html("").removeClass("mt-3");

                            $("#Review_Anime_Form input, #Review_Anime_Form textarea").val("");

                            $("#Reviews_Placeholder").load("functions/reviews/refresh-reviews.php", {
                                Refresh_Reviews: Refresh_Reviews
                            });
                        });
                    </script>

                    <?php
                } catch(PDOException $Insert_Reviews_Logs) {
                    ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Insert_Reviews_Logs:</b> <?= $Insert_Reviews_Logs->getMessage(); ?>.</p> <?php
                }
            }
        } catch(PDOException $Select_Anime_Reviews) {
            ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Select_Anime_Reviews:</b> <?= $Select_Anime_Reviews->getMessage(); ?>.</p> <?php
        }
    }

    $Anirepo = null;
?>

<script>
    $(function() {
        $("#Review_Anime").prop("disabled", false);
    });
</script>