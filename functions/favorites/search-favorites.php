<?php
    include_once "../database-connection.php";

    if (!isset($_POST["Favorites_Keywords"]) || !isset($_POST["Sort_Favorite_Type"]) || !isset($_POST["Sort_Favorites"]) || !isset($_POST["Limit_Favorites"]) || !isset($_SESSION["User_ID"])) {
        ?>

        <article class="bg-danger my-3 p-3 rounded text-center text-white user-select-none">
            <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
            <h4><strong>UNABLE TO SEARCH FAVORITES</strong></h4>

            <p>Refresh the page.</p>
        </article>

        <?php
    } else {
        $Favorites_Keywords = trim($_POST["Favorites_Keywords"]);
        $Wildcard_Favorites_Keywords = "%" . $Favorites_Keywords . "%";
        $Sort_Favorite_Type = trim($_POST["Sort_Favorite_Type"]);
        $Sort_Favorites = trim($_POST["Sort_Favorites"]);
        $Limit_Favorites = trim($_POST["Limit_Favorites"]);

        if ($Sort_Favorite_Type == "Anime") {
            $SQL_Query_Join = "JOIN anime ON favorites.Anime_ID = anime.Anime_ID WHERE favorites.User_ID = :User_ID";
            $SQL_Query_Where = "AND Anime_Title LIKE :Favorites_Keywords";
        } else {
            $SQL_Query_Join = "WHERE User_ID = :User_ID";
            $SQL_Query_Where = "AND Favorite_Name LIKE :Favorites_Keywords";
        }

        if ($Sort_Favorites == "Name DESC") {
            if ($Sort_Favorite_Type == "Anime") {
                $SQL_Query_Order_By = "ORDER BY anime.Anime_Title DESC";
            } else {
                $SQL_Query_Order_By = "ORDER BY favorites.Favorite_Name DESC";
            }
        } else if ($Sort_Favorites == "Placement ASC") {
            $SQL_Query_Order_By = "ORDER BY favorites.Placement ASC";
        } else if ($Sort_Favorites == "Placement DESC") {
            $SQL_Query_Order_By = "ORDER BY favorites.Placement DESC";
        } else if ($Sort_Favorites == "Date_Favorite_Added ASC") {
            $SQL_Query_Order_By = "ORDER BY favorites.Date_Favorite_Added ASC";
        } else if ($Sort_Favorites == "Date_Favorite_Added DESC") {
            $SQL_Query_Order_By = "ORDER BY favorites.Date_Favorite_Added DESC";
        } else {
            if ($Sort_Favorite_Type == "Anime") {
                $SQL_Query_Order_By = "ORDER BY anime.Anime_Title ASC";
            } else {
                $SQL_Query_Order_By = "ORDER BY favorites.Favorite_Name ASC";
            }
        }

        if (is_numeric($Limit_Favorites)) {
            $Limit_Favorites = filter_var($Limit_Favorites, FILTER_SANITIZE_NUMBER_INT);
            $SQL_Query_Limit = "LIMIT " . $Limit_Favorites;
        } else {
            $SQL_Query_Limit = "";
        }

        try {
            $Select_Favorites = $Anirepo->prepare("SELECT * FROM favorites
                $SQL_Query_Join
                AND Favorite_Type = :Favorite_Type
                $SQL_Query_Where
                $SQL_Query_Order_By
                $SQL_Query_Limit");
            $Select_Favorites->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Favorites->bindParam(":Favorite_Type", $Sort_Favorite_Type);
            $Select_Favorites->bindParam(":Favorites_Keywords", $Wildcard_Favorites_Keywords);
            $Select_Favorites->execute();
            $Favorites = $Select_Favorites->fetchAll();

            if ($Select_Favorites->rowCount() < 1) {
                ?>

                <article class="bg-info my-3 p-3 rounded text-center text-white user-select-none">
                    <h1 class="Large-Font-Size"><i class="fab fa-searchengin"></i></h1>
                    <h4><strong>NOTHING FOUND ON YOUR REPOSITORY</strong></h4>

                    <?php if (!empty($Favorites_Keywords)) { ?> <p>Cannot find any favorites titled/named <b><q><?= $Favorites_Keywords; ?></q></b>.</p> <?php } ?>
                </article>

                <?php
            } else {
                ?>

                <article class="my-3">
                    <h4 class="text-center text-md-start">
                        <strong>Favorite
                            <?php
                                if ($Sort_Favorite_Type == "Anime") {
                                    echo "Anime";
                                } else if ($Sort_Favorite_Type == "Character") {
                                    echo "Characters";
                                } else {
                                    echo "People";
                                }
                            ?>

                            (<?= number_format($Select_Favorites->rowCount()); ?>)
                        </strong>
                    </h4>

                    <p class="text-center text-md-start">
                        <i>
                            Showing <?= number_format($Select_Favorites->rowCount()); ?> Favorite

                            <?php
                                if ($Sort_Favorite_Type == "Anime") {
                                    echo "Anime";
                                } else if ($Sort_Favorite_Type == "Character") {
                                    echo "Characters";
                                } else {
                                    echo "People";
                                }
                            ?>
                        </i>
                    </p>

                    <div class="mt-3 Grid-Container Grid-5">
                        <?php
                            foreach ($Favorites as $Favorite) {
                                if ($Sort_Favorite_Type == "Anime") {
                                    ?>

                                    <a class="d-block mx-auto text-center text-decoration-none" data-bs-target="#Bootstrap_Modal" data-bs-toggle="modal" href="javascript:void(0);" id="Show_Favorite_Editor_<?= $Favorite['Favorite_ID']; ?>">
                                        <img alt="<?= $Favorite['Anime_Title']; ?> Thumbnail" draggable="false" src="<?= $Favorite['Anime_Thumbnail']; ?>">

                                        <p class="bg-success p-1 rounded-bottom text-white">(<b><span id="Placement_<?= $Favorite['Favorite_ID']; ?>"><?= $Favorite['Placement']; ?></span></b>) <?= $Favorite["Anime_Title"]; ?></p>
                                    </a>

                                    <?php
                                } else {
                                    ?>

                                    <a class="d-block mx-auto text-center text-decoration-none" data-bs-target="#Bootstrap_Modal" data-bs-toggle="modal" href="javascript:void(0);" id="Show_Favorite_Editor_<?= $Favorite['Favorite_ID']; ?>">
                                        <img alt="<?= $Favorite['Favorite_Name']; ?> Thumbnail" draggable="false" src="<?= $Favorite['Favorite_Thumbnail']; ?>">

                                        <p class="<?php if ($Sort_Favorite_Type == 'Character') { echo 'bg-primary'; } else { echo 'bg-danger'; } ?> p-1 rounded-bottom text-white">(<b><span id="Placement_<?= $Favorite['Favorite_ID']; ?>"><?= $Favorite['Placement']; ?></span></b>) <?= $Favorite["Favorite_Name"]; ?></p>
                                    </a>

                                    <?php
                                }
                                ?>

                                <script>
                                    $(function() {
                                        $("#Show_Favorite_Editor_<?= $Favorite['Favorite_ID']; ?>").click(function() {
                                            var Show_Favorite_Editor = $(this).val(),
                                                Favorite_ID = <?= $Favorite["Favorite_ID"]; ?>;

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
                </article>

                <?php
            }
        } catch(PDOException $Select_Favorites) {
            ?>

            <article class="bg-danger my-3 p-3 rounded text-center text-white user-select-none">
                <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                <h4><strong>SELECT_FAVORITES</strong></h4>

                <p><?= $Select_Favorites->getMessage(); ?>.</p>
            </article>

            <?php
        }
    }

    $Anirepo = null;
?>