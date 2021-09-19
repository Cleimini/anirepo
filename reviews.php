<?php require_once "functions/database-connection.php"; ?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <?php include_once "components/head.html"; ?>

        <link href="stylesheets/navigation.css" rel="stylesheet">
        <link href="stylesheets/main.css" rel="stylesheet">

        <script defer src="javascripts/navigation.js"></script>
        <script defer src="javascripts/reviews.js"></script>

        <title>AniRepo: Reviews</title>
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
                        <section class="p-4 row text-white">
                            <article class="col-md">
                                <h1><strong>REVIEWS</strong></h1>

                                <p>Overview</p>
                            </article>

                            <article class="col-md text-end">
                                <button class="bg-primary btn btn-sm p-3 rounded text-white Adjustable-Button-Width Custom-Button-Border Custom-Button-Border-Blue" data-bs-target="#Bootstrap_Modal" data-bs-toggle="modal" id="Show_Anime_Reviewer" type="button"><b>ANIME REVIEWER</b> <i class="fas fa-file-clipboard"></i></button>
                            </article>
                        </section>

                        <section class="m-3">
                            <article class="bg-white border p-3 rounded">
                                <h4 class="mb-2 text-center text-md-start"><strong>Search Reviewed Anime</strong></h4>

                                <form action="functions/reviews/search-reviews.php" method="POST">
                                    <div class="form-group position-relative">
                                        <input class="form-control form-control-lg" id="Anime_Title_Keywords" placeholder="e.g. Bleach" type="text">

                                        <span class="position-absolute"><i class="fas fa-tag"></i></span>
                                    </div>

                                    <div class="row">
                                        <div class="col-md mt-2">
                                            <div class="form-group position-relative">
                                                <select class="form-control form-control-sm w-100" id="Sort_Reviews">
                                                    <option value="anime.Anime_Title ASC">Anime Title (A-Z)</option>
                                                    <option value="anime.Anime_Title DESC">Anime Title (Z-A)</option>
                                                    <option disabled>--- ---</option>
                                                    <option value="reviews.Score DESC">Score (Highest to Lowest)</option>
                                                    <option value="reviews.Score ASC">Score (Highest to Lowest)</option>
                                                    <option disabled>--- ---</option>
                                                    <option value="reviews.Date_Anime_Reviewed ASC">Date Reviewed (Recently)</option>
                                                    <option value="reviews.Date_Anime_Reviewed DESC">Date Reviewed (Formerly)</option>
                                                </select>

                                                <span class="position-absolute" style="top: 15px;"><i class="fas fa-sort"></i></span>
                                            </div>
                                        </div>

                                        <div class="col-md mt-2">
                                            <div class="form-group position-relative">
                                                <select class="form-control form-control-sm w-100" id="Limit_Reviews">
                                                    <option value="25">Show 25 Reviewed Anime</option>
                                                    <option value="50">Show 50 Reviewed Anime</option>
                                                    <option value="100">Show 100 Reviewed Anime</option>
                                                    <option value="150">Show 150 Reviewed Anime</option>
                                                    <option value="200">Show 200 Reviewed Anime</option>
                                                    <option value="250">Show 250 Reviewed Anime</option>
                                                    <option value="300">Show 300 Reviewed Anime</option>
                                                    <option value="400">Show 400 Reviewed Anime</option>
                                                    <option value="500">Show 500 Reviewed Anime</option>
                                                    <option value="Show All">Show All Reviewed Anime</option>
                                                </select>

                                                <span class="position-absolute" style="top: 15px;"><i class="fas fa-sort-numeric-up"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </article>
                        </section>

                        <section class="m-3" id="Reviews_Placeholder">
                            <?php
                                try {
                                    $Select_Reviews = $Anirepo->prepare("SELECT * FROM reviews
                                        JOIN anime ON reviews.Anime_ID = anime.Anime_ID
                                        WHERE anime.User_ID = :User_ID
                                        ORDER BY Score DESC
                                        LIMIT 25");
                                    $Select_Reviews->bindParam(":User_ID", $_SESSION["User_ID"]);
                                    $Select_Reviews->execute();
                                    $Reviews = $Select_Reviews->fetchAll();

                                    if ($Select_Reviews->rowCount() < 1) {
                                        ?>

                                        <article class="bg-info p-3 rounded text-center text-white user-select-none">
                                            <h1 class="Large-Font-Size"><i class="fas fa-info-circle"></i></h1>
                                            <h4><strong>NO REVIEWED ANIME FOUND ON YOUR REPOSITORY</strong></h4>
                                        </article>

                                        <?php
                                    } else {
                                        ?>

                                        <article class="my-3">
                                            <h4 class="text-center text-md-start"><strong>Reviewed Anime (<?= number_format($Select_Reviews->rowCount()); ?>)</strong></h4>

                                            <p class="text-center text-md-start"><i>Showing <?= number_format($Select_Reviews->rowCount()); ?> Reviewed Anime</i></p>

                                            <div class="mt-5 Grid-Container Grid-5">
                                                <?php
                                                    foreach ($Reviews as $Review) {
                                                        ?>

                                                        <a class="d-block mx-auto text-center text-decoration-none" data-bs-target="#Bootstrap_Modal" data-bs-toggle="modal" href="javascript:void(0);" id="Show_Review_Editor_<?= $Review['Anime_ID']; ?>">
                                                            <img alt="<?= $Review['Anime_Title']; ?> Thumbnail" draggable="false" src="<?= $Review['Anime_Thumbnail']; ?>">

                                                            <p class="bg-success p-1 rounded-bottom text-white">(<b><span id="Score_<?= $Review['Anime_ID']; ?>"><?= round($Review["Score"]) . "/10"; ?></span></b>) <?= $Review["Anime_Title"]; ?></p>
                                                        </a>

                                                        <script>
                                                            $(function() {
                                                                $("#Show_Review_Editor_<?= $Review['Anime_ID']; ?>").click(function() {
                                                                    var Show_Review_Editor = $(this).val(),
                                                                        Anime_ID = <?= $Review["Anime_ID"]; ?>;

                                                                    $(".modal-content").html("").load("components/reviews/review-editor.php", {
                                                                        Show_Review_Editor: Show_Review_Editor,
                                                                        Anime_ID: Anime_ID
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
                                } catch(PDOException $Select_Reviews) {
                                    ?>

                                    <article class="bg-danger p-3 rounded text-center text-white user-select-none">
                                        <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                                        <h4><strong>SELECT_REVIEWS</strong></h4>

                                        <p><?= $Select_Reviews->getMessage(); ?>.</p>
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