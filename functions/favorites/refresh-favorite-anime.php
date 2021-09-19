<?php
    include_once "../database-connection.php";

    if (!isset($_POST["Refresh_Favorite_Anime"]) || $_POST["Refresh_Favorite_Anime"] == false || !isset($_SESSION["User_ID"])) {
        ?>

        <div class="bg-danger p-3 text-center text-white user-select-none">
            <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
            <h4><strong>UNABLE TO REFRESH THE FAVORITE ANIME LIST</strong></h4>

            <p>Refresh the page instead.</p>
        </div>

        <?php
    } else {
        try {
            $Select_Favorite_Anime = $Anirepo->prepare("SELECT * FROM favorites
                JOIN anime ON favorites.Anime_ID = anime.Anime_ID
                WHERE favorites.User_ID = :User_ID AND favorites.Favorite_Type = 'Anime'");
            $Select_Favorite_Anime->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Favorite_Anime->execute();
            $Favorite_Anime = $Select_Favorite_Anime->fetchAll();

            if ($Select_Favorite_Anime->rowCount() > 0) {
                ?>

                <h4 class="text-center text-md-start"><strong>Favorite Anime (<?= number_format($Select_Favorite_Anime->rowCount()); ?>)</strong></h4>

                <p class="text-center text-md-start"><i>Showing <?= number_format($Select_Favorite_Anime->rowCount()); ?> Favorite Anime</i></p>

                <div class="mt-3 Grid-Container Grid-5">
                    <?php
                        foreach ($Favorite_Anime as $Favorite_Anime) {
                            ?>

                            <a class="d-block mx-auto text-center text-decoration-none" data-bs-target="#Bootstrap_Modal" data-bs-toggle="modal" href="javascript:void(0);" id="Show_Favorite_Editor_<?= $Favorite_Anime['Favorite_ID']; ?>">
                                <img alt="<?= $Favorite_Anime['Anime_Title']; ?> Thumbnail" draggable="false" src="<?= $Favorite_Anime['Anime_Thumbnail']; ?>">

                                <p class="bg-success p-1 rounded-bottom text-white">(<b><span id="Placement_<?= $Favorite_Anime['Favorite_ID']; ?>"><?= $Favorite_Anime['Placement']; ?></span></b>) <?= $Favorite_Anime["Anime_Title"]; ?></p>
                            </a>

                            <script>
                                $(function() {
                                    $("#Show_Favorite_Editor_<?= $Favorite_Anime['Favorite_ID']; ?>").click(function() {
                                        var Show_Favorite_Editor = $(this).val(),
                                            Favorite_ID = <?= $Favorite_Anime["Favorite_ID"]; ?>;

                                        $(".modal-content").html("").load("components/favorites/favorite-editor.php", {
                                            Show_Favorite_Editor: Show_Favorite_Editor,
                                            Favorite_ID: Favorite_ID
                                        });
                                    });
                                });
                            </script>

                            <?php
                        }
                    ?>
                </div>

                <?php
            }
        } catch(PDOException $Select_Favorite_Anime) {
            ?>

            <div class="bg-danger p-3 text-center text-white user-select-none">
                <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                <h4><strong>SELECT_FAVORITE_ANIME</strong></h4>

                <p><?= $Select_Favorite_Anime->getMessage(); ?>.</p>
            </div>

            <?php
        }
    }

    $Anirepo = null;
?>