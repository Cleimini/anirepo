<script>
    $(function() {
        $("#Delete_Account").prop("disabled", false);
    });
</script>

<?php
    include_once "../database-connection.php";

    if (!isset($_POST["Delete_Account"]) || !isset($_SESSION["User_ID"])) {
        ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Unable to delete account, refresh the page.</p> <?php
    } else {
        try {
            $Select_Users = $Anirepo->prepare("SELECT * FROM users WHERE User_ID = :User_ID");
            $Select_Users->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Users->execute();

            $Select_Anime = $Anirepo->prepare("SELECT * FROM anime WHERE User_ID = :User_ID");
            $Select_Anime->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Anime->execute();
            $Anime = $Select_Anime->fetchAll();

            if ($Select_Users->rowCount() < 1) {
                ?> <p class="text-danger"><i class="fas fa-times-circle"></i> User not found, refresh the page.</p> <?php
            } else {
                try {
                    foreach ($Anime as $Anime) {
                        $Delete_Reviews = $Anirepo->prepare("DELETE FROM reviews WHERE Anime_ID = :Anime_ID");
                        $Delete_Reviews->bindParam(":Anime_ID", $Anime["Anime_ID"]);
                        $Delete_Reviews->execute();

                        $Delete_Monitorings = $Anirepo->prepare("DELETE FROM monitorings WHERE Anime_ID = :Anime_ID");
                        $Delete_Monitorings->bindParam(":Anime_ID", $Anime["Anime_ID"]);
                        $Delete_Monitorings->execute();
                    }

                    $Delete_Favorites = $Anirepo->prepare("DELETE FROM favorites WHERE User_ID = :User_ID");
                    $Delete_Favorites->bindParam(":User_ID", $_SESSION["User_ID"]);
                    $Delete_Favorites->execute();

                    $Delete_Anime = $Anirepo->prepare("DELETE FROM anime WHERE User_ID = :User_ID");
                    $Delete_Anime->bindParam(":User_ID", $_SESSION["User_ID"]);
                    $Delete_Anime->execute();

                    $Delete_Logs = $Anirepo->prepare("DELETE FROM logs WHERE User_ID = :User_ID");
                    $Delete_Logs->bindParam(":User_ID", $_SESSION["User_ID"]);
                    $Delete_Logs->execute();

                    $Delete_Resets = $Anirepo->prepare("DELETE FROM resets WHERE User_ID = :User_ID");
                    $Delete_Resets->bindParam(":User_ID", $_SESSION["User_ID"]);
                    $Delete_Resets->execute();

                    $Delete_Users = $Anirepo->prepare("DELETE FROM users WHERE User_ID = :User_ID");
                    $Delete_Users->bindParam(":User_ID", $_SESSION["User_ID"]);
                    $Delete_Users->execute();

                    ?>

                    <p class="text-success"><i class="fas fa-check-circle"></i> Account has been deleted.</p>

                    <script>
                        $(function() {
                            $("#Delete_Account").prop("disabled", true);

                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        });
                    </script>

                    <?php
                } catch(PDOException $Delete_Account) {
                    ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Delete_Account:</b> <?= $Delete_Account->getMessage(); ?>.</p> <?php
                }
            }
        } catch(PDOException $Select_Users) {
            ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Select_Users:</b> <?= $Select_Users->getMessage(); ?>.</p> <?php
        }
    }

    $Anirepo = null;
?>