<?php require_once "../../functions/database-connection.php"; ?>

<div class="modal-header text-white">
    <h1><strong>ANIME EDITOR</strong></h1>
    <h2 data-bs-dismiss="modal"><i class="fas fa-times-circle"></i></h2>
</div>

<div class="modal-body text-dark">
    <?php
        if (!isset($_POST["Show_Anime_Editor"]) || !isset($_SESSION["User_ID"])) {
            echo $Modal_Error_Box;
        } else {
            $Anime_ID = trim($_POST["Anime_ID"]);

            try {
                $Select_Anime = $Anirepo->prepare("SELECT * FROM anime
                    JOIN monitorings ON anime.Anime_ID = monitorings.Anime_ID
                    WHERE anime.Anime_ID = :Anime_ID AND anime.User_ID = :User_ID");
                $Select_Anime->bindParam(":Anime_ID", $Anime_ID);
                $Select_Anime->bindParam(":User_ID", $_SESSION["User_ID"]);
                $Select_Anime->execute();
                $Anime = $Select_Anime->fetch();

                if ($Select_Anime->rowCount() < 1) {
                    ?>

                    <div class="text-center text-danger user-select-none">
                        <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                        <h4><strong>ANIME NOT FOUND</strong></h4>

                        <p>Refresh the page.</p>
                    </div>

                    <?php
                } else {
                    ?>

                    <div class="mb-5 text-center">
                        <h2><strong><a href="<?= $Anime['Anime_MAL_URL']; ?>" target="_BLANK"><i class="fas fa-link me-2"></i>VISIT MAL PAGE</a></strong></h2>

                        <img alt="<?= $Anime['Anime_Title']; ?> Thumbnail" class="border mt-2" draggable="false" src="<?= $Anime['Anime_Thumbnail']; ?>">
                    </div>

                    <p><i class="fas fa-id-card"></i> <strong>MAL ANIME ID:</strong></p>
                    <p class="border-primary border-top text-end text-primary"><?= $Anime["MAL_Anime_ID"]; ?></p>

                    <p class="mt-4"><i class="fas fa-tag"></i> <strong>ANIME TITLE:</strong></p>
                    <p class="border-primary border-top text-end text-primary"><?= $Anime["Anime_Title"]; ?></p>

                    <p class="mt-4"><i class="fas fa-film"></i> <strong>ANIME TYPE:</strong></p>
                    <p class="border-primary border-top text-end text-primary"><?= $Anime["Anime_Type"]; ?></p>

                    <?php
                        if (!empty($Anime["Premiered"])) {
                            ?>

                            <p class="mt-4"><i class="fas fa-calendar-alt"></i> <strong>PREMIERED:</strong></p>
                            <p class="border-primary border-top text-end text-primary"><?= $Anime["Premiered"]; ?></p>

                            <?php
                        }

                        if (!empty($Anime["Studios"])) {
                            ?>

                            <p class="mt-4"><i class="fas fa-video"></i> <strong>STUDIOS:</strong></p>
                            <p class="border-primary border-top text-end text-primary"><?= $Anime["Studios"]; ?></p>

                            <?php
                        }
                    ?>

                    <p class="mt-4"><i class="fas fa-book-open"></i> <strong>SOURCE:</strong></p>
                    <p class="border-primary border-top text-end text-primary"><?= $Anime["Source"]; ?></p>

                    <p class="mt-4"><i class="fas fa-book"></i> <strong>GENRES:</strong></p>
                    <p class="border-primary border-top text-end text-primary"><?= $Anime["Genres"]; ?></p>

                    <p class="mt-4"><i class="fas fa-link"></i> <strong>ANIME MAL URL:</strong></p>
                    <p class="border-primary border-top text-end text-primary"><?= $Anime["Anime_MAL_URL"]; ?></p>

                    <p class="mt-4"><i class="fas fa-calendar-plus"></i> <strong>DATE ADDED:</strong></p>
                    <p class="border-primary border-top text-end text-primary"><?= date("M d, Y h:i:s A", strtotime($Anime['Date_Anime_Added'])); ?></p>

                    <?php
                        if (!empty($Anime["Date_Monitoring_Updated"])) {
                            ?>

                            <p class="mt-4"><i class="fas fa-calendar-plus"></i> <strong>DATE MODIFIED:</strong></p>
                            <p class="border-primary border-top text-end text-primary"><?= date("M d, Y h:i:s A", strtotime($Anime['Date_Monitoring_Updated'])); ?></p>

                            <?php
                        }
                    ?>

                    <form id="Anime_Editor_Form" method="POST">
                        <label class="mt-4" for="Monitoring_Type">Monitoring Type:</label>

                        <div class="form-group position-relative">
                            <select class="form-control form-control-lg w-100" id="Monitoring_Type">
                                <option <?php if ($Anime["Monitoring_Type"] == "Finished") { echo "selected"; } ?> value="Finished">Finished Watching</option>
                                <option <?php if ($Anime["Monitoring_Type"] == "Currently") { echo "selected"; } ?> value="Currently">Currently Watching</option>
                                <option <?php if ($Anime["Monitoring_Type"] == "Postponed") { echo "selected"; } ?> value="Postponed">Postponed Watching</option>
                                <option <?php if ($Anime["Monitoring_Type"] == "Scheduled") { echo "selected"; } ?> value="Scheduled">Scheduled Watching</option>
                            </select>

                            <span class="position-absolute"><i class="fas fa-tv"></i></span>
                        </div>

                        <div id="Date_Started_Form">
                            <label class="mt-4" for="Date_Started">Date Started:</label>

                            <div class="form-group position-relative">
                                <input class="form-control form-control-lg w-100" id="Date_Started" type="date" value="<?= $Anime['Date_Started']; ?>">

                                <span class="position-absolute"><i class="fas fa-calendar-alt"></i></span>
                            </div>
                        </div>

                        <div id="Date_Finished_Form">
                            <label class="mt-4" for="Date_Finished">Date Finished:</label>

                            <div class="form-group position-relative">
                                <input class="form-control form-control-lg w-100" id="Date_Finished" type="date" value="<?= $Anime['Date_Finished']; ?>">

                                <span class="position-absolute"><i class="fas fa-calendar-alt"></i></span>
                            </div>
                        </div>

                        <div id="Date_Scheduled_Form">
                            <label class="mt-4" for="Date_Scheduled">Date Scheduled:</label>

                            <div class="form-group position-relative">
                                <input class="form-control form-control-lg w-100" id="Date_Scheduled" type="date" value="<?= $Anime['Date_Scheduled']; ?>">

                                <span class="position-absolute"><i class="fas fa-calendar-alt"></i></span>
                            </div>
                        </div>

                        <div class="mt-4 row">
                            <div class="col-md">
                                <button class="bg-primary btn btn-lg d-block mt-2 mx-auto px-5 py-3 text-white" id="Update_Anime" type="submit"><b>UPDATE ANIME</b> <i class="fas fa-edit"></i></button>
                            </div>

                            <div class="col-md">
                                <button class="bg-danger btn btn-lg d-block mt-2 mx-auto px-5 py-3 text-white" id="Delete_Anime" type="submit"><b>DELETE ANIME</b> <i class="fas fa-trash-alt"></i></button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-3" id="Anime_Editor_Message"></div>

                    <script>
                        $(function() {
                            <?php
                                if ($Anime["Monitoring_Type"] == "Finished") {
                                    ?> $("#Date_Scheduled_Form").hide(); <?php
                                } else if ($Anime["Monitoring_Type"] == "Scheduled") {
                                    ?> $("#Date_Started_Form, #Date_Finished_Form").hide(); <?php
                                } else {
                                    ?> $("#Date_Finished_Form, #Date_Scheduled_Form").hide(); <?php
                                }
                            ?>

                            $("#Monitoring_Type").change(function() {
                                var Monitoring_Type = $(this).val();

                                if (Monitoring_Type == "Finished") {
                                    $("#Date_Started_Form, #Date_Finished_Form").stop().slideDown();
                                    $("#Date_Scheduled_Form").stop().slideUp();

                                    $("#Date_Started").val("<?= $Anime['Date_Started']; ?>");
                                    $("#Date_Finished").val("<?= $Anime['Date_Finished']; ?>");
                                    $("#Date_Scheduled").val("");
                                } else if (Monitoring_Type == "Scheduled") {
                                    $("#Date_Started_Form, #Date_Finished_Form").stop().slideUp();
                                    $("#Date_Scheduled_Form").stop().slideDown();

                                    $("#Date_Started, #Date_Finished").val("");
                                    $("#Date_Scheduled").val("<?= $Anime['Date_Scheduled']; ?>");
                                } else {
                                    $("#Date_Started_Form").stop().slideDown();
                                    $("#Date_Finished_Form, #Date_Scheduled_Form").stop().slideUp();

                                    $("#Date_Started").val("<?= $Anime['Date_Started']; ?>");
                                    $("#Date_Finished, #Date_Scheduled").val("");
                                }
                            });

                            $("#Update_Anime").click(function(No_Refresh) {
                                No_Refresh.preventDefault();

                                var Update_Anime = $(this).prop("disabled", true).val(),
                                    Anime_ID = <?= $Anime_ID; ?>,
                                    Monitoring_Type = $("#Monitoring_Type").val(),
                                    Date_Started = $("#Date_Started").val(),
                                    Date_Finished = $("#Date_Finished").val(),
                                    Date_Scheduled = $("#Date_Scheduled").val();

                                $("#Anime_Editor_Message").html("<?= $Waiting_Message; ?>").load("functions/anime/update-anime.php", {
                                    Update_Anime: Update_Anime,
                                    Anime_ID: Anime_ID,
                                    Monitoring_Type: Monitoring_Type,
                                    Date_Started: Date_Started,
                                    Date_Finished: Date_Finished,
                                    Date_Scheduled: Date_Scheduled
                                });
                            });

                            $("#Delete_Anime").click(function(No_Refresh) {
                                No_Refresh.preventDefault();

                                var Delete_Anime = $(this).prop("disabled", true).val(),
                                    Anime_ID = <?= $Anime_ID; ?>;

                                if (confirm("Are you sure you wanted to delete this anime?")) {
                                    $("#Anime_Editor_Message").html("<?= $Waiting_Message; ?>").load("functions/anime/delete-anime.php", {
                                        Delete_Anime: Delete_Anime,
                                        Anime_ID: Anime_ID
                                    });
                                } else {
                                    $(this).prop("disabled", false);
                                }
                            });
                        });
                    </script>

                    <?php
                }
            } catch(PDOException $Select_Anime) {
                ?>

                <div class="text-center text-danger user-select-none">
                    <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                    <h4><strong>SELECT_ANIME</strong></h4>

                    <p><?= $Select_Anime->getMessage(); ?>. Refresh the page.</p>
                </div>

                <?php
            }
        }
    ?>
</div>