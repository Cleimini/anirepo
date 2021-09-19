<?php require_once "../../functions/database-connection.php"; ?>

<div class="modal-header text-white">
    <h1><strong>FAVORITES ENLISTER</strong></h1>
    <h2 data-bs-dismiss="modal"><i class="fas fa-times-circle"></i></h2>
</div>

<div class="modal-body text-dark">
    <?php
        if (!isset($_POST["Show_Favorites_Enlister"]) || !isset($_SESSION["User_ID"])) {
            echo $Modal_Error_Box;
        } else {
            ?>

            <form action="functions/favorites/add-favorite.php" id="Add_Favorite_Form" method="POST">
                <div id="Anime_Title_Form">
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
                </div>

                <div id="Favorite_MAL_URL_Form">
                    <label for="Favorite_MAL_URL">Favorite MAL URL:</label>

                    <div class="form-group position-relative">
                        <input class="form-control form-control-lg w-100" id="Favorite_MAL_URL" placeholder="e.g. https://myanimelist.net/character/564/Uryuu_Ishida" type="url">

                        <span class="position-absolute"><i class="fas fa-link"></i></span>
                    </div>
                </div>

                <label class="mt-4" for="Placement">Placement:</label>
                
                <div class="form-group position-relative">
                    <input class="form-control form-control-lg w-100" id="Placement" placeholder="e.g. Top 5" type="number">

                    <span class="position-absolute"><i class="fas fa-sort-numeric-down"></i></span>
                </div>

                <label class="mt-4" for="Favorite_Type">Favorite Type:</label>

                <div class="form-group position-relative">
                    <select class="form-control form-control-lg w-100" id="Favorite_Type">
                        <option value="Anime">Anime</option>
                        <option value="Character">Character</option>
                        <option value="People">People</option>
                    </select>

                    <span class="position-absolute"><i class="fas fa-star"></i></span>
                </div>

                <button class="bg-primary btn btn-lg d-block mt-4 mx-auto px-5 py-3 text-white" id="Add_Favorite" type="submit"><b> ADD FAVORITE</b> <i class="fas fa-plus-square"></i></button>
            </form>

            <div class="mt-3" id="Add_Favorite_Message"></div>

            <script>
                $(function() {
                    $("#Favorite_MAL_URL_Form").hide();

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

                    $("#Favorite_Type").change(function() {
                        var Favorite_Type = $(this).val();

                        if (Favorite_Type == "Anime") {
                            $("#Anime_Title_Form").stop().slideDown();
                            $("#Favorite_MAL_URL_Form").stop().slideUp();

                            $("#Favorite_MAL_URL").val("");
                        } else {
                            $("#Anime_Title_Form").stop().slideUp();
                            $("#Favorite_MAL_URL_Form").stop().slideDown();

                            $("#Selected_Anime_ID, #Anime_Title").val("");
                        }

                        $("#Anime_Title_Searches").html("");
                    });

                    $("#Add_Favorite").click(function(No_Refresh) {
                        No_Refresh.preventDefault();

                        var Add_Favorite = $(this).prop("disabled", true).val(),
                            Selected_Anime_ID = $("#Selected_Anime_ID").val(),
                            Anime_Title = $("#Anime_Title").val(),
                            Favorite_MAL_URL = $("#Favorite_MAL_URL").val(),
                            Placement = $("#Placement").val(),
                            Favorite_Type = $("#Favorite_Type").val();

                        $("#Add_Favorite_Message").html("<?= $Waiting_Message; ?>").load("functions/favorites/add-favorite.php", {
                            Add_Favorite: Add_Favorite,
                            Selected_Anime_ID: Selected_Anime_ID,
                            Anime_Title: Anime_Title,
                            Favorite_MAL_URL: Favorite_MAL_URL,
                            Placement: Placement,
                            Favorite_Type: Favorite_Type
                        });
                    });
                });
            </script>

            <?php
        }
    ?>
</div>