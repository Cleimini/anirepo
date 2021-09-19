<?php
    include_once "../database-connection.php";

    if (!isset($_POST["Refresh_Favorites"]) || !isset($_SESSION["User_ID"])) {
        ?>

        <div class="bg-danger my-3 p-3 rounded text-center text-white user-select-none">
            <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
            <h4><strong>UNABLE TO REFRESH THE FAVORITES LISTS</strong></h4>

            <p>Refresh the page instead.</p>
        </div>

        <?php
    } else {
        try {
            $Select_Favorite_Anime = $Anirepo->prepare("SELECT * FROM favorites
                JOIN anime ON favorites.Anime_ID = anime.Anime_ID
                WHERE favorites.User_ID = :User_ID AND favorites.Favorite_Type = 'Anime'
                ORDER BY Placement ASC
                LIMIT 25");
            $Select_Favorite_Anime->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Favorite_Anime->execute();
            $Favorite_Anime = $Select_Favorite_Anime->fetchAll();

            $Select_Favorite_Characters = $Anirepo->prepare("SELECT * FROM favorites
                WHERE User_ID = :User_ID AND Favorite_Type = 'Character'
                ORDER BY Placement ASC
                LIMIT 25");
            $Select_Favorite_Characters->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Favorite_Characters->execute();
            $Favorite_Characters = $Select_Favorite_Characters->fetchAll();

            $Select_Favorite_People = $Anirepo->prepare("SELECT * FROM favorites
                WHERE User_ID = :User_ID AND Favorite_Type = 'People'
                ORDER BY Placement ASC
                LIMIT 25");
            $Select_Favorite_People->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Favorite_People->execute();
            $Favorite_People = $Select_Favorite_People->fetchAll();

            if ($Select_Favorite_Anime->rowCount() < 1 && $Select_Favorite_Characters->rowCount() < 1 && $Select_Favorite_People->rowCount() < 1) {
                ?>

                <article class="bg-info p-3 rounded text-center text-white user-select-none">
                    <h1 class="Large-Font-Size"><i class="fas fa-info-circle"></i></h1>
                    <h4><strong>NO FAVORITES FOUND ON YOUR REPOSITORY</strong></h4>
                </article>

                <?php
            } else {
                ?>

                <article id="Favorite_Anime_Placeholder">
                    <?php
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
                    ?>
                </article>

                <article class="mt-5" id="Favorite_Characters_Placeholder">
                    <?php
                        if ($Select_Favorite_Characters->rowCount() > 0) {
                            ?>

                            <h4 class="text-center text-md-start"><strong>Favorite Characters (<?= number_format($Select_Favorite_Characters->rowCount()); ?>)</strong></h4>

                            <p class="text-center text-md-start"><i>Showing <?= number_format($Select_Favorite_Characters->rowCount()); ?> Favorite Characters</i></p>

                            <div class="mt-3 Grid-Container Grid-5">
                                <?php
                                    foreach ($Favorite_Characters as $Favorite_Character) {
                                        ?>

                                        <a class="d-block mx-auto text-center text-decoration-none" data-bs-target="#Bootstrap_Modal" data-bs-toggle="modal" href="javascript:void(0);" id="Show_Favorite_Editor_<?= $Favorite_Character['Favorite_ID']; ?>">
                                            <img alt="<?= $Favorite_Character['Favorite_Name']; ?> Thumbnail" draggable="false" src="<?= $Favorite_Character['Favorite_Thumbnail']; ?>">

                                            <p class="bg-primary p-1 rounded-bottom text-white">(<b><span id="Placement_<?= $Favorite_Character['Favorite_ID']; ?>"><?= $Favorite_Character['Placement']; ?></span></b>) <?= $Favorite_Character["Favorite_Name"]; ?></p>
                                        </a>

                                        <script>
                                            $(function() {
                                                $("#Show_Favorite_Editor_<?= $Favorite_Character['Favorite_ID']; ?>").click(function() {
                                                    var Show_Favorite_Editor = $(this).val(),
                                                        Favorite_ID = <?= $Favorite_Character["Favorite_ID"]; ?>;

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
                    ?>
                </article>

                <article class="mt-5" id="Favorite_People_Placeholder">
                    <?php
                        if ($Select_Favorite_People->rowCount() > 0) {
                            ?>

                            <h4 class="text-center text-md-start"><strong>Favorite People (<?= number_format($Select_Favorite_People->rowCount()); ?>)</strong></h4>

                            <p class="text-center text-md-start"><i>Showing <?= number_format($Select_Favorite_People->rowCount()); ?> Favorite People</i></p>

                            <div class="mt-3 Grid-Container Grid-5">
                                <?php
                                    foreach ($Favorite_People as $Favorite_People) {
                                        ?>

                                        <a class="d-block mx-auto text-center text-decoration-none" data-bs-target="#Bootstrap_Modal" data-bs-toggle="modal" href="javascript:void(0);" id="Show_Favorite_Editor_<?= $Favorite_People['Favorite_ID']; ?>">
                                            <img alt="<?= $Favorite_People['Favorite_Name']; ?> Thumbnail" draggable="false" src="<?= $Favorite_People['Favorite_Thumbnail']; ?>">

                                            <p class="bg-danger p-1 rounded-bottom text-white">(<b><span id="Placement_<?= $Favorite_People['Favorite_ID']; ?>"><?= $Favorite_People['Placement']; ?></span></b>) <?= $Favorite_People["Favorite_Name"]; ?></p>
                                        </a>

                                        <script>
                                            $(function() {
                                                $("#Show_Favorite_Editor_<?= $Favorite_People['Favorite_ID']; ?>").click(function() {
                                                    var Show_Favorite_Editor = $(this).val(),
                                                        Favorite_ID = <?= $Favorite_People["Favorite_ID"]; ?>;

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
                    ?>
                </article>

                <?php
            }
        } catch(PDOException $Select_Favorites) {
            ?>

            <article class="bg-danger p-3 rounded text-center text-white user-select-none">
                <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                <h4><strong>SELECT_FAVORITES</strong></h4>

                <p><?= $Select_Favorites->getMessage(); ?>.</p>
            </article>

            <?php
        }
    }

    $Anirepo = null;
?>