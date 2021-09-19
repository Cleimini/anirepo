<script>
    $(function() {
        $("#Delete_Favorite").prop("disabled", false);
    });
</script>

<?php
    include_once "../database-connection.php";

    if (!isset($_POST["Delete_Favorite"]) || !isset($_POST["Favorite_ID"]) || !isset($_SESSION["User_ID"])) {
        ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Unable to delete favorite, refresh the page.</p> <?php
    } else {
        $Favorite_ID = trim($_POST["Favorite_ID"]);

        try {
            $Select_Favorites = $Anirepo->prepare("SELECT * FROM favorites WHERE Favorite_ID = :Favorite_ID AND User_ID = :User_ID");
            $Select_Favorites->bindParam(":Favorite_ID", $Favorite_ID);
            $Select_Favorites->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Favorites->execute();
            $Favorite = $Select_Favorites->fetch();

            if ($Select_Favorites->rowCount() < 1) {
                ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Favorite not found, refresh the page.</p> <?php
            } else {
                try {
                    if ($Favorite["Favorite_Type"] == "Anime") {
                        $Log_Type = "Delete Favorite Anime";
                        $Favorite_Type = "Anime";
                    } else if ($Favorite["Favorite_Type"] == "Character") {
                        $Log_Type = "Delete Favorite Character";
                        $Favorite_Type = "Character";
                    } else {
                        $Log_Type = "Delete Favorite People";
                        $Favorite_Type = "People";
                    }

                    $Delete_Favorites = $Anirepo->prepare("DELETE FROM favorites WHERE Favorite_ID = :Favorite_ID AND User_ID = :User_ID");
                    $Delete_Favorites->bindParam(":Favorite_ID", $Favorite_ID);
                    $Delete_Favorites->bindParam(":User_ID", $_SESSION["User_ID"]);
                    $Delete_Favorites->execute();

                    $Reselect_Favorites = $Anirepo->prepare("SELECT * FROM favorites WHERE User_ID = :User_ID");
                    $Reselect_Favorites->bindParam(":User_ID", $_SESSION["User_ID"]);
                    $Reselect_Favorites->execute();

                    $Insert_Logs = $Anirepo->prepare("INSERT INTO logs VALUES ('', :User_ID, '$Log_Type', NOW())");
                    $Insert_Logs->bindParam(":User_ID", $_SESSION["User_ID"]);
                    $Insert_Logs->execute();

                    ?>

                    <script>
                        $(function() {
                            $("#Favorite_Editor_Message").html("");

                            $("#Bootstrap_Modal").modal("hide");

                            $("#Delete_Favorite").prop("disabled", true);

                            <?php
                                if ($Reselect_Favorites->rowCount() > 0) {
                                    if ($Favorite_Type == "Anime") {
                                        ?>

                                        var Refresh_Favorite_Anime = true;

                                        $("#Favorite_Anime_Placeholder").load("functions/favorites/refresh-favorite-anime.php", {
                                            Refresh_Favorite_Anime: Refresh_Favorite_Anime
                                        });

                                        <?php
                                    } else if ($Favorite_Type == "Character") {
                                        ?>

                                        var Refresh_Favorite_Characters = true;
                                    
                                        $("#Favorite_Characters_Placeholder").load("functions/favorites/refresh-favorite-characters.php", {
                                            Refresh_Favorite_Characters: Refresh_Favorite_Characters
                                        });

                                        <?php
                                    } else {
                                        ?>

                                        var Refresh_Favorite_People = true;
                                    
                                        $("#Favorite_People_Placeholder").load("functions/favorites/refresh-favorite-people.php", {
                                            Refresh_Favorite_People: Refresh_Favorite_People
                                        });

                                        <?php
                                    }
                                } else {
                                    ?> $("#Favorites_Placeholder").html('<article class="bg-info p-3 rounded text-center text-white user-select-none"><h1 class="Large-Font-Size"><i class="fas fa-info-circle"></i></h1><h4><strong>NO FAVORITES FOUND ON YOUR REPOSITORY</strong></h4></article>'); <?php
                                }
                            ?>
                        });
                    </script>

                    <?php
                } catch(PDOException $Delete_Favorites_Insert_Logs) {
                    ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Delete_Favorites_Insert_Logs:</b> <?= $Delete_Favorites_Insert_Logs->getMessage(); ?>.</p> <?php
                }
            }
        } catch(PDOException $Select_Favorites) {
            ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Select_Favorites:</b> <?= $Select_Favorites->getMessage(); ?>.</p> <?php
        }
    }

    $Anirepo = null;
?>