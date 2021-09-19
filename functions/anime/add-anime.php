<?php
    include_once "../database-connection.php";
    include_once "../simple_html_dom.php";

    if (!isset($_POST["Add_Anime"]) || !isset($_POST["Anime_MAL_URL"]) || !isset($_POST["Monitoring_Type"]) || !isset($_POST["Date_Started"]) || !isset($_POST["Date_Finished"]) || !isset($_POST["Date_Scheduled"]) || !isset($_POST["Opinion"]) || !isset($_POST["Score"]) || !isset($_POST["Placement"]) || !isset($_SESSION["User_ID"])) {
        ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Unable to add anime, refresh the page.</p> <?php
    } else {
        $Anime_MAL_URL = trim(filter_var($_POST["Anime_MAL_URL"], FILTER_SANITIZE_URL));
        $MAL_Anime_ID = substr($Anime_MAL_URL, 30);
        $MAL_Anime_ID = strtok($MAL_Anime_ID, "/");
        $Monitoring_Type = trim($_POST["Monitoring_Type"]);
        $Date_Started = preg_replace("([^0-9/])", "", $_POST["Date_Started"]);
        $Date_Finished = preg_replace("([^0-9/])", "", $_POST["Date_Finished"]);
        $Date_Scheduled = preg_replace("([^0-9/])", "", $_POST["Date_Scheduled"]);
        $Opinion = trim(filter_var($_POST["Opinion"], FILTER_SANITIZE_SPECIAL_CHARS));
        $Score = trim(filter_var($_POST["Score"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
        $Placement = trim(filter_var($_POST["Placement"], FILTER_SANITIZE_NUMBER_INT));

        try {
            $Select_Anime = $Anirepo->prepare("SELECT * FROM anime WHERE MAL_Anime_ID = :MAL_Anime_ID AND User_ID = :User_ID");
            $Select_Anime->bindParam(":MAL_Anime_ID", $MAL_Anime_ID);
            $Select_Anime->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Anime->execute();

            if (empty($Anime_MAL_URL)) {
                $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Anime MAL URL is required.</p> <?php
            } else if (!filter_var($Anime_MAL_URL, FILTER_VALIDATE_URL)) {
                $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Invalid anime MAL URL format.</p> <?php
            } else if (!strstr($Anime_MAL_URL, "https://myanimelist.net/anime/")) {
                $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Anime MAL URL should start at https://myanimelist.net/anime/.</p> <?php
            } else if (! @file_get_contents($Anime_MAL_URL)) {
                $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Anime not found on the MAL website.</p> <?php
            } else if ($Select_Anime->rowCount() > 0) {
                $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Anime is already on your repository.</p> <?php
            }

            if (!in_array($Monitoring_Type, array("Finished", "Currently", "Postponed", "Scheduled"))) {
                $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Invalid monitoring type value.</p> <?php
            } else {
                if ($Monitoring_Type == "Finished") {
                    if (!empty($Date_Started)) {
                        if (!DateTime::createFromFormat("Y-m-d", $Date_Started)) {
                            $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Invalid date started format.</p> <?php
                        } else if (!empty($Date_Finished) && $Date_Started > $Date_Finished) {
                            $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Date started should not be later than the date finished.</p> <?php
                        }
                    } else {
                        $Date_Started = NULL;
                    }

                    if (!empty($Date_Finished)) {
                        if (!DateTime::createFromFormat("Y-m-d", $Date_Finished)) {
                            $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Invalid date finished format.</p> <?php
                        } else if (!empty($Date_Started) && $Date_Finished < $Date_Started) {
                            $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Date finished should not be earlier than the date started.</p> <?php
                        }
                    } else {
                        $Date_Finished = NULL;
                    }

                    $Date_Scheduled = NULL;
                } else if ($Monitoring_Type == "Scheduled") {
                    if (empty($Date_Scheduled)) {
                        $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Date scheduled is required.</p> <?php
                    } else if (!DateTime::createFromFormat("Y-m-d", $Date_Scheduled)) {
                        $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Invalid date scheduled format.</p> <?php
                    }

                    $Date_Started = $Date_Finished = NULL;
                } else {
                    if (!empty($Date_Started) && !DateTime::createFromFormat("Y-m-d", $Date_Started)) {
                        $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Invalid date started format.</p> <?php
                    } else {
                        $Date_Started = NULL;
                    }

                    $Date_Finished = $Date_Scheduled = NULL;
                }
            }

            if (!empty($Score) && (!is_numeric($Score))) {
                $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Score should be numeric.</p> <?php
            } else if (!empty($Score) && $Score > 10) {
                $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Scoring an anime has a minimum of 1 to a maximum of 10.</p> <?php
            }

            if (!empty($Placement) && (!is_numeric($Placement))) {
                $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Placement should be numeric.</p> <?php
            }

            if ($Error < 1) {
                try {
                    $Correct_Anime_Genres = array();
                    $Anime_Genres = array(1 => "ActionAction", 2 => "AdventureAdventure",  3=> "CarsCars",  4 => "ComedyComedy", 5 => "DementiaDementia",
                        6 => "DemonsDemons", 7 => "DramaDrama", 8 => "EcchiEcchi", 9 => "FantasyFantasy", 10 => "GameGame",
                        11 => "HaremHarem", 12 => "HentaiHentai", 13 => "HistoricalHistorical", 14 => "HorrorHorror", 15 => "JoseiJosei",
                        16 => "KidsKids", 17 => "MagicMagic", 18 => "MartialArtsMartialArts", 19 => "MechaMecha", 20 => "MilitaryMilitary",
                        21 => "MusicMusic", 22 => "MysteryMystery", 23 => "ParodyParody", 24 => "PolicePolice", 25 => "PyschologicalPsychological",
                        26 => "RomanceRomance", 27 => "SamuraiSamurai", 28 => "SchoolSchool", 29 => "Sci-FiSci-Fi", 30 => "SeinenSeinen",
                        31 => "ShoujoShoujo", 32 => "ShoujoAiShoujoAi", 33 => "ShounenShounen", 34 => "ShounenAiShounenAi", 35 => "SliceofLifeSliceofLife",
                        36 => "SpaceSpace", 37 => "SportsSports", 38 => "SuperPowerSuperPower", 39 => "SupernaturalSupernatural", 40 => "ThrillerThriller",
                        41 => "VampireVampire", 42 => "YaoiYaoi", 43 => "YuriYuri");
                    $Fixed_Anime_Genres = array(1 => "Action", 2 => "Adventure", 3 => "Cars", 4 => "Comedy", 5 => "Dementia",
                        6 => "Demons", 7 => "Drama", 8 => "Ecchi", 9 => "Fantasy", 10 => "Game",
                        11 => "Harem", 12 => "Hentai", 13 => "Historical", 14 => "Horror", 15 => "Josei",
                        16 => "Kids", 17 => "Magic", 18 => "Martial Arts", 19 => "Mecha", 20 => "Military",
                        21 => "Music", 22 => "Mystery", 23 => "Parody", 24 => "Police", 25 => "Pyschological",
                        26 => "Romance", 27 => "Samurai", 28 => "School", 29 => "Sci-Fi", 30 => "Seinen",
                        31 => "Shoujo", 32 => "Shoujo Ai", 33 => "Shounen", 34 => "Shounen Ai", 35 => "Slice of Life",
                        36 => "Space", 37 => "Sports", 38 => "Super Power", 39 => "Supernatural", 40 => "Thriller",
                        41 => "Vampire", 42 => "Yaoi", 43 => "Yuri");
                    $MAL_Website = file_get_html("$Anime_MAL_URL");
                    $Anime_Title = $MAL_Website->find("h1[class='title-name h1_bold_none']", 0);
                    $Anime_Title = strip_tags($Anime_Title->plaintext);
                    $Anime_Title = trim(preg_replace("/\s+/", " ", $Anime_Title));
                    $Anime_Thumbnail = $MAL_Website->find("td[class='borderClass'] div div img", 0)->getAttribute("data-src");
                    $Anime_Thumbnail = trim(filter_var($Anime_Thumbnail, FILTER_SANITIZE_URL));
                    $Parent_Container = $MAL_Website->find("td[class='borderClass']", 0);
                    $Child_Container = $Parent_Container->find("div[class='spaceit']");
                    $Sibling_Container = $Parent_Container->find("div[class='spaceit'] span[class='dark_text']");
        
                    for ($i = 0; $i < sizeof($Child_Container); $i++) {
                        $Child_Data = $Child_Container[$i]->prev_sibling();
                        $Child_Data = strip_tags($Child_Data->plaintext);
                        $Child_Data = trim(preg_replace("/\s+/", " ", $Child_Data));
        
                        if (sizeof($Child_Container) == 6) {
                            $Premiered = "";
        
                            if ($i == 0) { $Anime_Type = substr($Child_Data, 6); }
                            if ($i == 3) { $Studios = substr($Child_Data, 9); }
                            if ($i == 4) {
                                $Genres = substr($Child_Data, 8);
                                $Genres = str_replace(" ", "", $Genres);
                                $Genres = explode(",", $Genres);
        
                                for ($k = 0; $k < sizeof($Genres); $k++) {
                                    if ($Compare_Genres = array_search($Genres[$k], $Anime_Genres)) {
                                        $Correct_Anime_Genres[] = $Genres[$k] = $Fixed_Anime_Genres[$Compare_Genres];
                                    }
                                }
        
                                $Correct_Anime_Genres = implode(", ", $Correct_Anime_Genres);
                            }
                        }
        
                        if (sizeof($Child_Container) == 7) {
                            if ($i == 0) { $Anime_Type = substr($Child_Data, 6); }
                            if ($i == 2) { $Premiered = substr($Child_Data, 11); }
                            if ($i == 4) { $Studios = substr($Child_Data, 9); }
                            if ($i == 5) {
                                $Genres = substr($Child_Data, 8);
                                $Genres = str_replace(" ", "", $Genres);
                                $Genres = explode(",", $Genres);
        
                                for ($k = 0; $k < sizeof($Genres); $k++) {
                                    if ($Compare_Genres = array_search($Genres[$k], $Anime_Genres)) {
                                        $Correct_Anime_Genres[] = $Genres[$k] = $Fixed_Anime_Genres[$Compare_Genres];
                                    }
                                }
        
                                $Correct_Anime_Genres = implode(", ", $Correct_Anime_Genres);
                            }
                        }
                    }
        
                    for($j = 0; $j < sizeof($Sibling_Container); $j++) {
                        $Sibling_Data = $Sibling_Container[$j]->parent();
                        $Sibling_Data = strip_tags($Sibling_Data->plaintext);
                        $Sibling_Data = trim(preg_replace("/\s+/", " ", $Sibling_Data));
        
                        if (sizeof($Sibling_Container) == 6) {
                            if ($j == 3) { $Source = substr($Sibling_Data, 8); }
                        }
        
                        if (sizeof($Sibling_Container) == 7) {
                            if ($j == 4) { $Source = substr($Sibling_Data, 8); }
                        }
                    }
        
                    if ($Studios == "None found, add some") {
                        $Studios = "";
                    }

                    $Insert_Anime = $Anirepo->prepare("INSERT INTO anime
                        VALUES ('', $MAL_Anime_ID, :User_ID, '$Anime_Title', '$Anime_Type', '$Premiered', '$Studios', '$Source', '$Correct_Anime_Genres',
                                :Anime_MAL_URL, '$Anime_Thumbnail', NOW())");
                    $Insert_Anime->bindParam(":User_ID", $_SESSION["User_ID"]);
                    $Insert_Anime->bindParam(":Anime_MAL_URL", $Anime_MAL_URL);
                    $Insert_Anime->execute();

                    $Anime_ID = $Anirepo->lastInsertId();

                    $Insert_Monitorings = $Anirepo->prepare("INSERT INTO monitorings VALUES ($Anime_ID, :Monitoring_Type, :Date_Started, :Date_Finished, :Date_Scheduled, NOW(), NULL)");
                    $Insert_Monitorings->bindParam(":Monitoring_Type", $Monitoring_Type);
                    $Insert_Monitorings->bindParam(":Date_Started", $Date_Started);
                    $Insert_Monitorings->bindParam(":Date_Finished", $Date_Finished);
                    $Insert_Monitorings->bindParam(":Date_Scheduled", $Date_Scheduled);
                    $Insert_Monitorings->execute();

                    if (!empty($Opinion) || !empty($Score)) {
                        $Insert_Reviews = $Anirepo->prepare("INSERT INTO reviews VALUES ($Anime_ID, :Opinion, :Score, NOW(), NULL)");
                        $Insert_Reviews->bindParam(":Opinion", $Opinion);
                        $Insert_Reviews->bindParam(":Score", $Score);
                        $Insert_Reviews->execute();
                    }

                    if (!empty($Placement)) {
                        $Insert_Favorites = $Connect->prepare("INSERT INTO favorites VALUES ('', $Anime_ID, '', :Placement, 'Anime', :Favorite_MAL_URL, NOW(), NULL)");
                        $Insert_Favorites->bindParam(":Placement", $Placement);
                        $Insert_Favorites->bindParam(":Favorite_MAL_URL", $Anime_MAL_URL);
                        $Insert_Favorites->execute();
                    }

                    $Insert_Logs = $Anirepo->prepare("INSERT INTO logs VALUES ('', :User_ID, 'Add Anime', NOW())");
                    $Insert_Logs->bindParam(":User_ID", $_SESSION["User_ID"]);
                    $Insert_Logs->execute();

                    ?>

                    <p class="text-success"><i class="fas fa-check-circle"></i> Anime has been added.</p>

                    <script>
                        $(function() {
                            $("#Add_Anime_Form select").prop("selectedIndex", 0);

                            $("#Date_Started_Form, #Date_Finished_Form").stop().slideDown();
                            $("#Date_Scheduled_Form").stop().slideUp();

                            $("#Add_Anime_Form input, #Date_Anime_Form textarea").val("");
                        });
                    </script>

                    <?php
                } catch(PDOException $Insert_Anime) {
                    ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Insert_Anime:</b> <?= $Insert_Anime->getMessage(); ?>.</p> <?php
                }
            }
        } catch(PDOException $Select_Anime) {
            ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Select_Anime:</b> <?= $Select_Anime->getMessage(); ?>.</p> <?php
        }
    }

    $Anirepo = null;
?>

<script>
    $(function() {
        $("#Add_Anime").prop("disabled", false);
    });
</script>