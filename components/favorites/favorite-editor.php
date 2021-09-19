<?php require_once "../../functions/database-connection.php"; ?>

<div class="modal-header text-white">
    <h1><strong>FAVORITE EDITOR</strong></h1>
    <h2 data-bs-dismiss="modal"><i class="fas fa-times-circle"></i></h2>
</div>

<div class="modal-body text-dark">
    <?php
        if (!isset($_POST["Show_Favorite_Editor"]) || !isset($_SESSION["User_ID"])) {
            echo $Modal_Error_Box;
        } else {
            $Favorite_ID = trim($_POST["Favorite_ID"]);

            try {
                $Select_Favorites = $Anirepo->prepare("SELECT * FROM favorites WHERE Favorite_ID = :Favorite_ID AND User_ID = :User_ID");
                $Select_Favorites->bindParam(":Favorite_ID", $Favorite_ID);
                $Select_Favorites->bindParam(":User_ID", $_SESSION["User_ID"]);
                $Select_Favorites->execute();
                $Favorite = $Select_Favorites->fetch();

                if ($Select_Favorites->rowCount() < 1) {
                    ?>

                    <div class="text-center text-danger user-select-none">
                        <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                        <h4><strong>FAVORITE NOT FOUND</strong></h4>

                        <p>Refresh the page.</p>
                    </div>

                    <?php
                } else {
                    if ($Favorite["Favorite_Type"] == "Anime") {
                        $Select_Anime = $Anirepo->prepare("SELECT * FROM anime WHERE Anime_ID = :Anime_ID AND User_ID = :User_ID");
                        $Select_Anime->bindParam(":Anime_ID", $Favorite["Anime_ID"]);
                        $Select_Anime->bindParam(":User_ID", $_SESSION["User_ID"]);
                        $Select_Anime->execute();
                        $Anime = $Select_Anime->fetch();
                    }

                    ?>

                    <div class="mb-5 text-center">
                        <?php
                            if ($Favorite["Favorite_Type"] == "Anime") {
                                ?>

                                <h2><strong><a href="<?= $Anime['Anime_MAL_URL']; ?>" target="_BLANK"><i class="fas fa-link me-2"></i>VISIT MAL PAGE</a></strong></h2>

                                <img alt="<?= $Anime['Anime_Title']; ?> Thumbnail" class="border mt-2" draggable="false" src="<?= $Anime['Anime_Thumbnail']; ?>">
                                

                                <?php
                            } else {
                                ?>

                                <h2><strong><a href="<?= $Favorite['Favorite_MAL_URL']; ?>" target="_BLANK"><i class="fas fa-link me-2"></i>VISIT MAL PAGE</a></strong></h2>

                                <img alt="<?= $Favorite['Favorite_Name']; ?> Thumbnail" class="border mt-2" draggable="false" src="<?= $Favorite['Favorite_Thumbnail']; ?>">

                                <?php
                            }
                        ?>
                    </div>

                    <?php
                        if ($Favorite["Favorite_Type"] == "Anime") {
                            ?>

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

                            <?php
                        } else {
                            ?>

                            <p><i class="fas fa-id-card"></i> <strong>MAL FAVORITE ID:</strong></p>
                            <p class="border-primary border-top text-end text-primary"><?= $Favorite["MAL_Favorite_ID"]; ?></p>

                            <p class="mt-4"><i class="fas fa-tag"></i> <strong>FAVORITE NAME:</strong></p>
                            <p class="border-primary border-top text-end text-primary"><?= $Favorite["Favorite_Name"]; ?></p>

                            <p class="mt-4"><i class="fas fa-link"></i> <strong>FAVORITE MAL URL:</strong></p>
                            <p class="border-primary border-top text-end text-primary"><?= $Favorite["Favorite_MAL_URL"]; ?></p>

                            <?php
                        }
                    ?>

                    <p class="mt-4"><i class="fas fa-calendar-plus"></i> <strong>DATE ADDED:</strong></p>
                    <p class="border-primary border-top text-end text-primary"><?= date("M d, Y h:i:s A", strtotime($Favorite['Date_Favorite_Added'])); ?></p>

                    <?php
                        if (!empty($Favorite["Date_Favorite_Updated"])) {
                            ?>

                            <p class="mt-4"><i class="fas fa-calendar-plus"></i> <strong>DATE MODIFIED:</strong></p>
                            <p class="border-primary border-top text-end text-primary"><?= date("M d, Y h:i:s A", strtotime($Favorite['Date_Favorite_Updated'])); ?></p>

                            <?php
                        }
                    ?>

                    <form id="Favorite_Editor_Form" method="POST">
                        <label class="mt-4" for="Placement">Placement:</label>
                        
                        <div class="form-group position-relative">
                            <input class="form-control form-control-lg w-100" id="Placement" placeholder="e.g. Top 5" type="number" value="<?= $Favorite['Placement']; ?>">

                            <span class="position-absolute"><i class="fas fa-sort-numeric-down"></i></span>
                        </div>

                        <div class="mt-4 row">
                            <div class="col-md">
                                <button class="bg-primary btn btn-lg d-block mt-2 mx-auto px-5 py-3 text-white" id="Update_Favorite" type="submit"><b>UPDATE FAVORITE</b> <i class="fas fa-edit"></i></button>
                            </div>

                            <div class="col-md">
                                <button class="bg-danger btn btn-lg d-block mt-2 mx-auto px-5 py-3 text-white" id="Delete_Favorite" type="submit"><b>DELETE FAVORITE</b> <i class="fas fa-trash-alt"></i></button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-3" id="Favorite_Editor_Message"></div>

                    <script>
                        $(function() {
                            $("#Update_Favorite").click(function(No_Refresh) {
                                No_Refresh.preventDefault();

                                var Update_Favorite = $(this).prop("disabled", true).val(),
                                    Favorite_ID = <?= $Favorite_ID; ?>,
                                    Placement = $("#Placement").val();

                                $("#Favorite_Editor_Message").html("<?= $Waiting_Message; ?>").load("functions/favorites/update-favorite.php", {
                                    Update_Favorite: Update_Favorite,
                                    Favorite_ID: Favorite_ID,
                                    Placement: Placement
                                });
                            });

                            $("#Delete_Favorite").click(function(No_Refresh) {
                                No_Refresh.preventDefault();

                                var Delete_Favorite = $(this).prop("disabled", true).val(),
                                    Favorite_ID = <?= $Favorite_ID; ?>;

                                if (confirm("Are you sure you wanted to delete this favorite?")) {
                                    $("#Favorite_Editor_Message").html("<?= $Waiting_Message; ?>").load("functions/favorites/delete-favorite.php", {
                                        Delete_Favorite: Delete_Favorite,
                                        Favorite_ID: Favorite_ID
                                    });
                                } else {
                                    $(this).prop("disabled", false);
                                }
                            });
                        });
                    </script>

                    <?php
                }
            } catch(PDOException $Select_Favorites_Anime) {
                ?>

                <div class="text-center text-danger user-select-none">
                    <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                    <h4><strong>SELECT_FAVORITES_ANIME</strong></h4>

                    <p><?= $Select_Favorites_Anime->getMessage(); ?>.</p>
                </div>

                <?php
            }
        }
    ?>
</div>