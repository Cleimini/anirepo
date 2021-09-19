<?php require_once "../../functions/database-connection.php"; ?>

<div class="modal-header text-white">
    <h1><strong>ANIME REVIEWER</strong></h1>
    <h2 data-bs-dismiss="modal"><i class="fas fa-times-circle"></i></h2>
</div>

<div class="modal-body text-dark">
    <?php
        if (!isset($_POST["Show_Anime_Reviewer"]) || !isset($_SESSION["User_ID"])) {
            echo $Modal_Error_Box;
        } else {
            ?>

            <form action="functions/reviews/review-anime.php" id="Review_Anime_Form" method="POST">
                <label>Selected Anime ID:</label>

                <div class="form-group position-relative">
                    <input class="form-control form-control-lg w-100" id="Selected_Anime_ID" placeholder="Type an anime title that you added below" readonly type="text">

                    <span class="position-absolute"><i class="fas fa-id-card"></i></span>
                </div>

                <label class="mt-4" for="Anime_Title">Anime Title:</label>

                <div class="form-group position-relative">
                    <input class="form-control form-control-lg w-100" id="Anime_Title" placeholder="e.g. Bleach" type="text">

                    <span class="position-absolute"><i class="fas fa-tag"></i></span>
                </div>

                <div id="Anime_Title_Searches"></div>

                <label class="mt-4" for="Opinion">Opinion:</label>

                <div class="form-group">
                    <textarea class="form-control form-control-lg ps-3" id="Opinion" placeholder="e.g. Lorem ipsum..." rows="5"></textarea>
                </div>

                <label class="mt-4" for="Score">Score:</label>
                
                <div class="form-group position-relative">
                    <input class="form-control form-control-lg w-100" id="Score" placeholder="e.g. 5 out of 10" type="number">

                    <span class="position-absolute"><i class="fas fa-sort-numeric-down"></i></span>
                </div>

                <button class="bg-primary btn btn-lg d-block mt-4 mx-auto px-5 py-3 text-white" id="Review_Anime" type="submit"><b> REVIEW ANIME</b> <i class="fas fa-plus-square"></i></button>
            </form>

            <div class="mt-3" id="Review_Anime_Message"></div>

            <script>
                $(function() {
                    $("#Anime_Title").keyup(function() {
                        var Anime_Title = $(this).val();

                        if (Anime_Title != "") {
                            $("#Anime_Title_Searches").addClass("mt-3").load("functions/search-anime-titles.php", {
                                Anime_Title: Anime_Title
                            });
                        } else {
                            $("#Anime_Title_Searches").html("").removeClass("mt-3");
                        }
                    });

                    $("#Review_Anime").click(function(No_Refresh) {
                        No_Refresh.preventDefault();

                        var Review_Anime = $(this).prop("disabled", true).val(),
                            Selected_Anime_ID = $("#Selected_Anime_ID").val(),
                            Opinion = $("#Opinion").val(),
                            Score = $("#Score").val();

                        $("#Review_Anime_Message").html("<?= $Waiting_Message; ?>").load("functions/reviews/review-anime.php", {
                            Review_Anime: Review_Anime,
                            Selected_Anime_ID: Selected_Anime_ID,
                            Opinion: Opinion,
                            Score: Score
                        });
                    });
                });
            </script>

            <?php
        }
    ?>
</div>