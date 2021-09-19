<?php require_once "../../functions/database-connection.php"; ?>

<div class="modal-header text-white">
    <h1><strong>ANIME ENLISTER</strong></h1>
    <h2 data-bs-dismiss="modal"><i class="fas fa-times-circle"></i></h2>
</div>

<div class="modal-body text-dark">
    <?php
        if (!isset($_POST["Show_Anime_Enlister"]) || !isset($_SESSION["User_ID"])) {
            echo $Modal_Error_Box;
        } else {
            ?>

            <form action="functions/anime/add-anime.php" id="Add_Anime_Form" method="POST">
                <label for="Anime_MAL_URL">Anime MAL URL:</label>

                <div class="form-group position-relative">
                    <input class="form-control form-control-lg w-100" id="Anime_MAL_URL" placeholder="e.g. https://myanimelist.net/anime/269/Bleach" type="url">

                    <span class="position-absolute"><i class="fas fa-link"></i></span>
                </div>

                <label class="mt-4" for="Monitoring_Type">Monitoring Type:</label>

                <div class="form-group position-relative">
                    <select class="form-control form-control-lg w-100" id="Monitoring_Type">
                        <option value="Finished">Finished Watching</option>
                        <option value="Currently">Currently Watching</option>
                        <option value="Postponed">Postponed Watching</option>
                        <option value="Scheduled">Scheduled Watching</option>
                    </select>

                    <span class="position-absolute"><i class="fas fa-tv"></i></span>
                </div>

                <div id="Date_Started_Form">
                    <label class="mt-4" for="Date_Started">Date Started:</label>

                    <div class="form-group position-relative">
                        <input class="form-control form-control-lg w-100" id="Date_Started" type="date">

                        <span class="position-absolute"><i class="fas fa-calendar-alt"></i></span>
                    </div>
                </div>

                <div id="Date_Finished_Form">
                    <label class="mt-4" for="Date_Finished">Date Finished:</label>

                    <div class="form-group position-relative">
                        <input class="form-control form-control-lg w-100" id="Date_Finished" type="date">

                        <span class="position-absolute"><i class="fas fa-calendar-alt"></i></span>
                    </div>
                </div>

                <div id="Date_Scheduled_Form">
                    <label class="mt-4" for="Date_Scheduled">Date Scheduled:</label>

                    <div class="form-group position-relative">
                        <input class="form-control form-control-lg w-100" id="Date_Scheduled" type="date">

                        <span class="position-absolute"><i class="fas fa-calendar-alt"></i></span>
                    </div>
                </div>

                <h4 class="mt-5 text-center"><strong>REVIEW ANIME (OPTIONAL)</strong></h4>

                <label class="mt-3" for="Opinion">Opinion:</label>

                <div class="form-group">
                    <textarea class="form-control form-control-lg ps-3" id="Opinion" placeholder="e.g. Lorem ipsum..." rows="5"></textarea>
                </div>

                <label class="mt-4" for="Score">Score:</label>

                <div class="form-group position-relative">
                    <input class="form-control form-control-lg w-100" id="Score" placeholder="e.g. 5 out of 10" type="number">

                    <span class="position-absolute"><i class="fas fa-sort-numeric-down"></i></span>
                </div>

                <h4 class="mt-5 text-center"><strong>ADD TO FAVORITES (OPTIONAL)</strong></h4>

                <label class="mt-3" for="Placement">Placement:</label>

                <div class="form-group position-relative">
                    <input class="form-control form-control-lg w-100" id="Placement" placeholder="e.g. Top 5" type="number">

                    <span class="position-absolute"><i class="fas fa-sort-numeric-down"></i></span>
                </div>

                <button class="bg-primary btn btn-lg d-block mt-4 mx-auto px-5 py-3 text-white" id="Add_Anime" type="submit"><b> ADD ANIME</b> <i class="fas fa-plus-square"></i></button>
            </form>

            <div class="mt-3" id="Add_Anime_Message"></div>

            <script>
                $(function() {
                    $("#Date_Scheduled_Form").hide();

                    $("#Monitoring_Type").change(function() {
                        var Monitoring_Type = $(this).val();

                        if (Monitoring_Type == "Finished") {
                            $("#Date_Started_Form, #Date_Finished_Form").stop().slideDown();
                            $("#Date_Scheduled_Form").stop().slideUp();

                            $("#Date_Scheduled").val("");
                        } else if (Monitoring_Type == "Scheduled") {
                            $("#Date_Started_Form, #Date_Finished_Form").stop().slideUp();
                            $("#Date_Scheduled_Form").stop().slideDown();

                            $("#Date_Started, #Date_Finished").val("");
                        } else {
                            $("#Date_Started_Form").stop().slideDown();
                            $("#Date_Finished_Form, #Date_Scheduled_Form").stop().slideUp();

                            $("#Date_Finished, #Date_Scheduled").val("");
                        }
                    });

                    $("#Add_Anime").click(function(No_Refresh) {
                        No_Refresh.preventDefault();

                        var Add_Anime = $(this).prop("disabled", true).val(),
                            Anime_MAL_URL = $("#Anime_MAL_URL").val(),
                            Monitoring_Type = $("#Monitoring_Type").val(),
                            Date_Started = $("#Date_Started").val(),
                            Date_Finished = $("#Date_Finished").val(),
                            Date_Scheduled = $("#Date_Scheduled").val(),
                            Opinion = $("#Opinion").val(),
                            Score = $("#Score").val(),
                            Placement = $("#Placement").val();

                        $("#Add_Anime_Message").html("<?= $Waiting_Message; ?>").load("functions/anime/add-anime.php", {
                            Add_Anime: Add_Anime,
                            Anime_MAL_URL: Anime_MAL_URL,
                            Monitoring_Type: Monitoring_Type,
                            Date_Started: Date_Started,
                            Date_Finished: Date_Finished,
                            Date_Scheduled: Date_Scheduled,
                            Opinion: Opinion,
                            Score: Score,
                            Placement: Placement
                        });
                    });
                });
            </script>

            <?php
        }
    ?>
</div>