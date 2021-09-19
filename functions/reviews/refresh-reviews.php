<?php
    include_once "../database-connection.php";

    if (!isset($_POST["Refresh_Reviews"]) || $_POST["Refresh_Reviews"] == false || !isset($_SESSION["User_ID"])) {
        ?>

        <article class="bg-danger p-3 rounded text-center text-white user-select-none">
            <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
            <h4><strong>UNABLE TO REFRESH THE REVIEW LIST</strong></h4>

            <p>Refresh the page instead.</p>
        </article>

        <?php
    } else {
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
    }

    $Anirepo = null;
?>