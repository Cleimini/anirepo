<?php require_once "functions/database-connection.php"; ?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <?php include_once "components/head.html"; ?>

        <link href="stylesheets/navigation.css" rel="stylesheet">
        <link href="stylesheets/main.css" rel="stylesheet">

        <script defer src="javascripts/navigation.js"></script>
        <script defer src="javascripts/favorites.js"></script>

        <title>AniRepo: Favorites</title>
    </head>

    <body>
        <?php
            if ($Disconnected) {
                header("Location: disconnected.php");
            } else {
                if (!isset($_SESSION["User_ID"])) {
                    header("Location: index.php");
                } else {
                    include_once "components/navigation.php";

                    ?>

                    <main class="float-end position-relative">
                        <section class="p-4 text-white">
                            <h1><strong>FAVORITES</strong></h1>

                            <p>Overview</p>
                        </section>

                        <section class="bg-white border m-3 p-3 rounded">
                            <article class="Grid-Container Grid-2">
                                <div>
                                    <h4 class="mb-2 text-center text-md-start"><strong>Add Favorites</strong></h4>

                                    <button class="bg-success btn btn-lg d-block p-4 rounded text-white w-100 Custom-Button-Border Custom-Button-Border-Green" data-bs-target="#Bootstrap_Modal" data-bs-toggle="modal" id="Show_Favorites_Enlister" type="button"><b>FAV. ENLISTER</b> <i class="fas fa-plus-square"></i></button>
                                </div>

                                <div>
                                    <h4 class="mb-2 text-center text-md-start"><strong>Import Favorites</strong></h4>

                                    <button class="bg-primary btn btn-lg d-block p-4 rounded text-white w-100 Custom-Button-Border Custom-Button-Border-Blue" data-bs-target="#Bootstrap_Modal" data-bs-toggle="modal" id="Show_Favorites_Importer" type="button"><b>FAV. IMPORTER</b> <i class="fas fa-file-import"></i></button>
                                </div>
                            </article>
                        </section>

                        <section class="m-3">
                            <article class="bg-white border p-3 rounded">
                                <h4 class="mb-2 text-center text-md-start"><strong>Search Favorites</strong></h4>

                                <form action="functions/favorites/search-favorites.php" method="POST">
                                    <div class="form-group position-relative">
                                        <input class="form-control form-control-lg" id="Favorites_Keywords" placeholder="e.g. Bleach" type="text">

                                        <span class="position-absolute"><i class="fas fa-tag"></i></span>
                                    </div>

                                    <div class="row">
                                        <div class="col-md mt-2">
                                            <div class="form-group position-relative">
                                                <select class="form-control form-control-sm w-100" id="Sort_Favorite_Type">
                                                    <option value="Anime">Anime</option>
                                                    <option value="Character">Character</option>
                                                    <option value="People">People</option>
                                                </select>

                                                <span class="position-absolute" style="top: 15px;"><i class="fas fa-star"></i></span>
                                            </div>
                                        </div>

                                        <div class="col-md mt-2">
                                            <div class="form-group position-relative">
                                                <select class="form-control form-control-sm w-100" id="Sort_Favorites">
                                                    <option value="Name ASC">Name (A-Z)</option>
                                                    <option value="Name DESC">Name (Z-A)</option>
                                                    <option disabled>--- ---</option>
                                                    <option value="Placement ASC">Placement (1-10)</option>
                                                    <option value="Placement DESC">Placement (10-1)</option>
                                                    <option disabled>--- ---</option>
                                                    <option value="Date_Favorite_Added ASC">Date Added (Recently)</option>
                                                    <option value="Date_Favorite_Updated DESC">Date Added (Formerly)</option>
                                                </select>

                                                <span class="position-absolute" style="top: 15px;"><i class="fas fa-sort"></i></span>
                                            </div>
                                        </div>

                                        <div class="col-md mt-2">
                                            <div class="form-group position-relative">
                                                <select class="form-control form-control-sm w-100" id="Limit_Favorites">
                                                    <option value="25">Show 25 Favorites</option>
                                                    <option value="50">Show 50 Favorites</option>
                                                    <option value="100">Show 100 Favorites</option>
                                                    <option value="150">Show 150 Favorites</option>
                                                    <option value="200">Show 200 Favorites</option>
                                                    <option value="250">Show 250 Favorites</option>
                                                    <option value="300">Show 300 Favorites</option>
                                                    <option value="400">Show 400 Favorites</option>
                                                    <option value="500">Show 500 Favorites</option>
                                                    <option value="Show All">Show All Favorites</option>
                                                </select>

                                                <span class="position-absolute" style="top: 15px;"><i class="fas fa-sort-numeric-up"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <div class="mt-2 text-center">
                                    <button class="bg-dark btn btn-sm px-5 py-3 text-white" id="Refresh_Favorites" type="button"><b> REFRESH FAVORITES</b> <i class="fas fa-refresh"></i></button>
                                </div>
                            </article>
                        </section>

                        <section class="m-3" id="Favorites_Placeholder">
                            <?php
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
                            ?>
                        </section>
                    </main>

                    <?php
                }

                ?>

                <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="Bootstrap_Modal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                        </div>
                    </div>
                </div>

                <?php
            }
        ?>
    </body>
</html>