<?php require_once "../../functions/database-connection.php"; ?>

<div class="modal-header text-white">
    <h1><strong>REVIEW EDITOR</strong></h1>
    <h2 data-bs-dismiss="modal"><i class="fas fa-times-circle"></i></h2>
</div>

<div class="modal-body text-dark">
    <?php
        if (!isset($_POST["Show_Review_Editor"]) || !isset($_POST["Anime_ID"]) || !isset($_SESSION["User_ID"])) {
            echo $Modal_Error_Box;
        } else {
            $Anime_ID = trim($_POST["Anime_ID"]);

            try {
                $Select_Reviews = $Anirepo->prepare("SELECT * FROM reviews
                    JOIN anime ON reviews.Anime_ID = anime.Anime_ID
                    WHERE reviews.Anime_ID = :Anime_ID AND anime.User_ID = :User_ID");
                $Select_Reviews->bindParam(":Anime_ID", $Anime_ID);
                $Select_Reviews->bindParam(":User_ID", $_SESSION["User_ID"]);
                $Select_Reviews->execute();
                $Review = $Select_Reviews->fetch();

                if ($Select_Reviews->rowCount() < 1) {
                    ?>

                    <div class="text-center text-danger user-select-none">
                        <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                        <h4><strong>REVIEWED ANIME NOT FOUND</strong></h4>

                        <p>Refresh the page.</p>
                    </div>

                    <?php
                } else {
                    ?>

                    <div class="mb-5 text-center">
                        <h2><strong><a href="<?= $Review['Anime_MAL_URL']; ?>" target="_BLANK"><i class="fas fa-link me-2"></i>VISIT MAL PAGE</a></strong></h2>

                        <img alt="<?= $Review['Anime_Title']; ?> Thumbnail" class="border mt-2" draggable="false" src="<?= $Review['Anime_Thumbnail']; ?>">
                    </div>

                    <p class="mt-4"><i class="fas fa-tag"></i> <strong>ANIME TITLE:</strong></p>
                    <p class="border-primary border-top text-end text-primary"><?= $Review["Anime_Title"]; ?></p>

                    <p class="mt-4"><i class="fas fa-film"></i> <strong>ANIME TYPE:</strong></p>
                    <p class="border-primary border-top text-end text-primary"><?= $Review["Anime_Type"]; ?></p>

                    <?php
                        if (!empty($Review["Premiered"])) {
                            ?>

                            <p class="mt-4"><i class="fas fa-calendar-alt"></i> <strong>PREMIERED:</strong></p>
                            <p class="border-primary border-top text-end text-primary"><?= $Review["Premiered"]; ?></p>

                            <?php
                        }

                        if (!empty($Review["Studios"])) {
                            ?>

                            <p class="mt-4"><i class="fas fa-video"></i> <strong>STUDIOS:</strong></p>
                            <p class="border-primary border-top text-end text-primary"><?= $Review["Studios"]; ?></p>

                            <?php
                        }
                    ?>

                    <p class="mt-4"><i class="fas fa-book-open"></i> <strong>SOURCE:</strong></p>
                    <p class="border-primary border-top text-end text-primary"><?= $Review["Source"]; ?></p>

                    <p class="mt-4"><i class="fas fa-book"></i> <strong>GENRES:</strong></p>
                    <p class="border-primary border-top text-end text-primary"><?= $Review["Genres"]; ?></p>

                    <p class="mt-4"><i class="fas fa-link"></i> <strong>ANIME MAL URL:</strong></p>
                    <p class="border-primary border-top text-end text-primary"><?= $Review["Anime_MAL_URL"]; ?></p>

                    <p class="mt-4"><i class="fas fa-calendar-plus"></i> <strong>DATE REVIEWED:</strong></p>
                    <p class="border-primary border-top text-end text-primary"><?= date("M d, Y h:i:s A", strtotime($Review['Date_Review_Added'])); ?></p>

                    <?php
                        if (!empty($Review["Date_Review_Updated"])) {
                            ?>

                            <p class="mt-4"><i class="fas fa-calendar-plus"></i> <strong>DATE MODIFIED:</strong></p>
                            <p class="border-primary border-top text-end text-primary"><?= date("M d, Y h:i:s A", strtotime($Review['Date_Review_Updated'])); ?></p>

                            <?php
                        }
                    ?>

                    <form id="Review_Editor_Form" method="POST">
                        <input id="Anime_ID" type="hidden" value="<?= $Anime_ID; ?>">

                        <label class="mt-4" for="Opinion">Opinion:</label>

                        <div class="form-group">
                            <textarea class="form-control form-control-lg ps-3" id="Opinion" placeholder="e.g. Lorem ipsum..." rows="5"><?= $Review["Opinion"]; ?></textarea>
                        </div>

                        <label class="mt-4" for="Score">Score:</label>
                        
                        <div class="form-group position-relative">
                            <input class="form-control form-control-lg w-100" id="Score" placeholder="e.g. 5 out of 10" type="number" value="<?= round($Review['Score']); ?>">

                            <span class="position-absolute"><i class="fas fa-sort-numeric-down"></i></span>
                        </div>

                        <div class="mt-4 row">
                            <div class="col-md">
                                <button class="bg-primary btn btn-lg d-block mt-2 mx-auto px-5 py-3 text-white" id="Update_Review" type="submit"><b>UPDATE REVIEW</b> <i class="fas fa-edit"></i></button>
                            </div>

                            <div class="col-md">
                                <button class="bg-danger btn btn-lg d-block mt-2 mx-auto px-5 py-3 text-white" id="Delete_Review" type="submit"><b>DELETE REVIEW</b> <i class="fas fa-trash-alt"></i></button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-3" id="Review_Editor_Message"></div>

                    <script>
                        $(function() {
                            $("#Update_Review").click(function(No_Refresh) {
                                No_Refresh.preventDefault();

                                var Update_Review = $(this).prop("disabled", true).val(),
                                    Anime_ID = $("#Anime_ID").val(),
                                    Opinion = $("#Opinion").val(),
                                    Score = $("#Score").val();

                                $("#Review_Editor_Message").html("<?= $Waiting_Message; ?>").load("functions/reviews/update-review.php", {
                                    Update_Review: Update_Review,
                                    Anime_ID: Anime_ID,
                                    Opinion: Opinion,
                                    Score: Score
                                });
                            });

                            $("#Delete_Review").click(function(No_Refresh) {
                                var Delete_Review = $(this).prop("disabled", true).val(),
                                    Anime_ID = $("#Anime_ID").val();

                                if (confirm("Are you sure you wanted to delete this review?")) {
                                    $("#Review_Editor_Message").html("<?= $Waiting_Message; ?>").load("functions/reviews/delete-review.php", {
                                        Delete_Review: Delete_Review,
                                        Anime_ID: Anime_ID
                                    });
                                }
                            });
                        });
                    </script>

                    <?php
                }
            } catch(PDOException $Select_Reviews) {
                ?>

                <div class="text-center text-danger user-select-none">
                    <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                    <h4><strong>SELECT_REVIEWS</strong></h4>

                    <p><?= $Select_Reviews->getMessage(); ?>.</p>
                </div>

                <?php
            }
        }
    ?>
</div>