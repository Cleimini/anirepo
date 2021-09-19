<?php
    include_once "../database-connection.php";

    if (!isset($_POST["Anime_Title_Keywords"]) || !isset($_POST["Sort_Reviews"]) || !isset($_POST["Limit_Reviews"])) {
        ?>

        <article class="bg-danger p-3 rounded text-center text-white user-select-none">
            <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
            <h4><strong>UNABLE TO SEARCH REVIEWS</strong></h4>

            <p>Refresh the page.</p>
        </article>

        <?php
    } else {
        $Anime_Title_Keywords = trim($_POST["Anime_Title_Keywords"]);
        $Wildcard_Anime_Title_Keywords = "%" . $Anime_Title_Keywords . "%";
        $Sort_Reviews = trim($_POST["Sort_Reviews"]);
        $Limit_Reviews = trim($_POST["Limit_Reviews"]);

        if (!in_array($Sort_Reviews, array("anime.Anime_Title ASC", "anime.Anime_Title DESC", "reviews.Score ASC", "reviews.Score DESC", "reviews.Date_Anime_Reviewed ASC", "reviews.Date_Anime_Reviewed DESC"))) {
            ?>

            <article class="bg-danger p-3 rounded text-center text-white user-select-none">
                <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                <h4><strong>INVALID SORT REVIEWS VALUE</strong></h4>

                <p>Refresh the page.</p>
            </article>

            <?php
        } else {
            if ($Sort_Reviews == "anime.Anime_Title ASC") {
                $Sort_Reviews_Label = "Anime Title (A-Z)";
            } else if ($Sort_Reviews == "anime.Anime_Title DESC") {
                $Sort_Reviews_Label = "Anime Title (Z-A)";
            } else if ($Sort_Reviews == "reviews.Score DESC") {
                $Sort_Reviews_Label = "Score (Highest to Lowest)";
            } else if ($Sort_Reviews == "reviews.Score ASC") {
                $Sort_Reviews_Label = "Score (Lowest to Highest)";
            } else if ($Sort_Reviews == "reviews.Date_Anime_Reviewed ASC") {
                $Sort_Reviews_Label = "Date Reviewed (Recently)";
            } else if ($Sort_Reviews == "reviews.Date_Anime_Reviewed DESC") {
                $Sort_Reviews_Label = "Date Reviewed (Formerly)";
            }

            if (is_numeric($Limit_Reviews)) {
                $Limit_Reviews = filter_var($_POST["Limit_Reviews"], FILTER_SANITIZE_NUMBER_INT);
                $SQL_Query_Limit = "LIMIT " . $Limit_Reviews;
            } else {
                $SQL_Query_Limit = "";
            }

            try {
                $Select_Reviews = $Anirepo->prepare("SELECT * FROM reviews
                    JOIN anime ON reviews.Anime_ID = anime.Anime_ID
                    WHERE anime.User_ID = :User_ID
                    AND anime.Anime_Title LIKE :Anime_Title
                    ORDER BY Score DESC
                    $SQL_Query_Limit");
                $Select_Reviews->bindParam(":User_ID", $_SESSION["User_ID"]);
                $Select_Reviews->bindparam(":Anime_Title", $Wildcard_Anime_Title_Keywords);
                $Select_Reviews->execute();
                $Reviews = $Select_Reviews->fetchAll();

                if ($Select_Reviews->rowCount() < 1) {
                    if (!empty($Anime_Title_Keywords)) {
                        ?>

                        <article class="bg-info p-3 rounded text-white user-select-none">
                            <div class="text-center">
                                <h1 class="Large-Font-Size"><i class="fab fa-searchengin"></i></h1>
                                <h4><strong>NOTHING FOUND ON YOUR REPOSITORY</strong></h4>

                                <?php if (!empty($Anime_Title_Keywords)) { ?> <p>Cannot find any anime titled <b><q><?= $Anime_Title_Keywords; ?></q></b>.</p> <?php } ?>
                            </div>

                            <p class="mt-5"><strong>Sorting:</strong> <?= $Sort_Reviews_Label; ?></p>
                            <p><strong>Limit:</strong> <?= $Limit_Reviews; ?></p>
                        </article>

                        <?php
                    } else {
                        ?>

                        <article class="bg-info p-3 rounded text-center text-white user-select-none">
                            <h1 class="Large-Font-Size"><i class="fas fa-info-circle"></i></h1>
                            <h4><strong>NO REVIEWED ANIME FOUND ON YOUR REPOSITORY</strong></h4>
                        </article>

                        <?php
                    }
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
        }
    }

    $Anirepo = null;
?>