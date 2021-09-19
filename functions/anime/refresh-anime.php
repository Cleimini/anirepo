<?php
    include_once "../database-connection.php";

    if (!isset($_POST["Refresh_Anime"]) || !isset($_SESSION["User_ID"])) {
        ?>

        <article class="bg-danger p-3 rounded text-center text-white user-select-none">
            <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
            <h4><strong>UNABLE TO REFRESH THE ANIME LIST</strong></h4>

            <p>Refresh the page instead.</p>
        </article>

        <?php
    } else {
        try {
            $Select_Anime = $Anirepo->prepare("SELECT * FROM anime
                JOIN monitorings ON anime.Anime_ID = monitorings.Anime_ID
                WHERE anime.User_ID = :User_ID ORDER BY anime.Anime_Title ASC LIMIT 25");
            $Select_Anime->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Anime->execute();
            $Anime = $Select_Anime->fetchAll();

            $Count_Anime = $Anirepo->prepare("SELECT * FROM anime WHERE User_ID = :User_ID");
            $Count_Anime->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Count_Anime->execute();

            if ($Select_Anime->rowCount() < 1) {
                ?>

                <article class="bg-info p-3 rounded text-center text-white user-select-none">
                    <h1 class="Large-Font-Size"><i class="fas fa-info-circle"></i></h1>
                    <h4><strong>NO ANIME FOUND ON YOUR REPOSITORY</strong></h4>
                </article>

                <?php
            } else {
                ?>
                
                <article class="bg-white border p-3 rounded">
                    <h4 class="mb-2 text-center text-md-start"><strong>Search Anime</strong></h4>

                    <form action="functions/anime/search-anime.php" method="POST">
                        <div class="form-group position-relative">
                            <input class="form-control form-control-lg" id="Anime_Title_Keywords" placeholder="e.g. Bleach" type="text">

                            <span class="position-absolute"><i class="fas fa-tag"></i></span>
                        </div>

                        <div class="row">
                            <div class="col-md mt-2">
                                <div class="form-group position-relative">
                                    <select class="form-control form-control-sm w-100" id="Sort_Anime_Type">
                                        <option value="All Types">All Types</option>
                                        <option value="TV">TV</option>
                                        <option value="Movie">Movie</option>
                                        <option value="OVA">OVA</option>
                                        <option value="ONA">ONA</option>
                                        <option value="Special">Special</option>
                                    </select>

                                    <span class="position-absolute" style="top: 15px;"><i class="fas fa-film"></i></span>
                                </div>
                            </div>

                            <div class="col-md mt-2">
                                <div class="form-group position-relative">
                                    <select class="form-control form-control-sm w-100" id="Sort_Premiered">
                                        <option value="All Seasons">All Seasons</option>
                                        <option value="Winter">Winter</option>
                                        <option value="Fall">Fall</option>
                                        <option value="Summer">Summer</option>
                                        <option value="Spring">Spring</option>
                                    </select>

                                    <span class="position-absolute" style="top: 15px;"><i class="fas fa-calendar-alt"></i></span>
                                </div>
                            </div>

                            <div class="col-md mt-2">
                                <div class="form-group position-relative">
                                    <select class="form-control form-control-sm w-100" id="Sort_Source">
                                        <option value="All Sources">All Sources</option>
                                        <option value="Manga">Manga</option>
                                        <option value="Original">Original</option>
                                        <option value="Light novel">Light novel</option>
                                        <option value="Visual novel">Visual novel</option>
                                        <option value="4-koma manga">4-koma manga</option>
                                        <option value="Web manga">Web manga</option>
                                        <option value="Game">Game</option>
                                        <option value="Novel">Novel</option>
                                        <option value="Other">Other</option>
                                    </select>

                                    <span class="position-absolute" style="top: 15px;"><i class="fas fa-book-open"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md mt-2">
                                <div class="form-group position-relative">
                                    <select class="form-control form-control-sm w-100" id="Sort_Monitoring_Type">
                                        <option value="All Monitorings">All Monitorings</option>
                                        <option value="Finished">Finished Anime</option>
                                        <option value="Currently">Currently Watching</option>
                                        <option value="Postponed">Postponed Watching</option>
                                        <option value="Scheduled">Scheduled Anime</option>
                                    </select>

                                    <span class="position-absolute" style="top: 15px;"><i class="fas fa-tv"></i></span>
                                </div>
                            </div>

                            <div class="col-md mt-2">
                                <div class="form-group position-relative">
                                    <select class="form-control form-control-sm w-100" id="Sort_Anime">
                                        <option value="Anime_Title ASC">Anime Title (A-Z)</option>
                                        <option value="Anime_Title DESC">Anime Title (Z-A)</option>
                                        <option disabled>--- ---</option>
                                        <option value="Date_Anime_Added ASC">Date Added (Recently)</option>
                                        <option value="Date_Anime_Added DESC">Date Added (Formerly)</option>
                                    </select>

                                    <span class="position-absolute" style="top: 15px;"><i class="fas fa-sort"></i></span>
                                </div>
                            </div>

                            <div class="col-md mt-2">
                                <div class="form-group position-relative">
                                    <select class="form-control form-control-sm w-100" id="Limit_Anime">
                                        <option value="25">Show 25 Anime</option>
                                        <option value="50">Show 50 Anime</option>
                                        <option value="100">Show 100 Anime</option>
                                        <option value="150">Show 150 Anime</option>
                                        <option value="200">Show 200 Anime</option>
                                        <option value="250">Show 250 Anime</option>
                                        <option value="300">Show 300 Anime</option>
                                        <option value="400">Show 400 Anime</option>
                                        <option value="500">Show 500 Anime</option>
                                        <option value="Show All">Show All</option>
                                    </select>

                                    <span class="position-absolute" style="top: 15px;"><i class="fas fa-sort-numeric-up"></i></span>
                                </div>
                            </div>
                        </div>
                    </form>

                    <h4 class="mt-5"><strong>Legend</strong></h4>

                    <div class="mt-3 row">
                        <div class="col-md">
                            <div class="bg-success d-inline-block p-2 rounded"></div>

                            <span>Finished</span>
                        </div>

                        <div class="col-md">
                            <div class="bg-primary d-inline-block p-2 rounded"></div>

                            <span>Currently</span>
                        </div>

                        <div class="col-md">
                            <div class="bg-danger d-inline-block p-2 rounded"></div>

                            <span>Postponed</span>
                        </div>

                        <div class="col-md">
                            <div class="bg-warning d-inline-block p-2 rounded"></div>

                            <span>Scheduled</span>
                        </div>
                    </div>
                </article>

                <article class="my-3" id="Anime_List_Placeholder">
                    <h4 class="text-center text-md-start"><strong>Anime (<?= number_format($Count_Anime->rowCount()); ?>)</strong></h4>

                    <p class="text-center text-md-start"><i>Showing <?= number_format($Select_Anime->rowCount()); ?> Anime</i></p>

                    <div class="mt-5 Grid-Container Grid-5">
                        <?php
                            foreach ($Anime as $Anime) {
                                if ($Anime["Monitoring_Type"] == "Finished") {
                                    $Color_Class_Name = "bg-success text-white";
                                } else if ($Anime["Monitoring_Type"] == "Currently") {
                                    $Color_Class_Name = "bg-primary text-white";
                                } else if ($Anime["Monitoring_Type"] == "Postponed") {
                                    $Color_Class_Name = "bg-danger text-white";
                                } else if ($Anime["Monitoring_Type"] == "Scheduled") {
                                    $Color_Class_Name = "bg-warning text-dark";
                                }

                                ?>

                                <a class="d-block mx-auto text-center text-decoration-none" data-bs-target="#Bootstrap_Modal" data-bs-toggle="modal" href="javascript:void(0);" id="Show_Anime_Editor_<?= $Anime['Anime_ID']; ?>">
                                    <img alt="<?= $Anime['Anime_Title']; ?> Thumbnail" draggable="false" src="<?= $Anime['Anime_Thumbnail']; ?>">

                                    <p class="p-1 rounded-bottom <?= $Color_Class_Name; ?>" id="Anime_Title_Placeholder_<?= $Anime['Anime_ID']; ?>"><?= $Anime["Anime_Title"]; ?></p>
                                </a>

                                <script>
                                    $(function() {
                                        $("#Show_Anime_Editor_<?= $Anime['Anime_ID']; ?>").click(function() {
                                            var Show_Anime_Editor = $(this).val(),
                                                Anime_ID = <?= $Anime["Anime_ID"]; ?>;

                                            $(".modal-content").html("").load("components/anime/anime-editor.php", {
                                                Show_Anime_Editor: Show_Anime_Editor,
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
        } catch(PDOException $Select_Anime) {
            ?>

            <article class="bg-danger p-3 rounded text-center text-white user-select-none">
                <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                <h4><strong>SELECT_ANIME</strong></h4>

                <p><?= $Select_Anime->getMessage(); ?>.</p>
            </article>

            <?php
        }
    }

    $Anirepo = null;
?>