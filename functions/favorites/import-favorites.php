<?php
    include_once "../database-connection.php";

    if (!isset($_POST["Import_Favorites"]) || !isset($_FILES["CSV_File"]["name"]) || !isset($_SESSION["User_ID"])) {
        ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Unable to import the favorites, refresh the page.</p> <?php
    } else {
        $CSV_File = basename($_FILES["CSV_File"]["name"]);
        $CSV_File_Temp = realpath($_FILES["CSV_File"]["tmp_name"]);
        $CSV_File_Extension = strtolower(pathinfo($CSV_File, PATHINFO_EXTENSION));

        if (empty($CSV_File)) {
            ?> <p class="text-danger"><i class="fas fa-times-circle"></i> No file was selected.</p> <?php
        } else if ($CSV_File_Extension != "csv") {
            ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Only csv files are allowed.</p> <?php
        } else {
            ini_set("max_execution_time", "0");

            $Main_File = fopen($CSV_File_Temp, "r", FILE_SKIP_EMPTY_LINES);

            fgets($Main_File);

            for ($i = 0; $i < 50 && !feof($Main_File); $i++) {
                while (($Excel_Row = fgetcsv($Main_File, 10000, ";")) !== false) {
                    $Row_ID = trim(filter_var($Excel_Row[0], FILTER_SANITIZE_NUMBER_INT));
                    $Selected_Anime_ID = trim(filter_var($Excel_Row[1], FILTER_SANITIZE_NUMBER_INT));
                    $Favorite_MAL_URL = trim(filter_var($Excel_Row[2], FILTER_SANITIZE_URL));
                    $Placement = trim(filter_var($Excel_Row[3], FILTER_SANITIZE_NUMBER_INT));
                    $Favorite_Type = trim($Excel_Row[4]);
                    $MAL_Favorite_ID = NULL;

                    if (empty($Placement)) {
                        $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Row <?= $Row_ID; ?>: Placement is required.</p> <?php
                    }
            
                    if (!in_array($Favorite_Type, array("Anime", "Character", "People"))) {
                        $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Row <?= $Row_ID; ?>: Invalid favorite type value.</p> <?php
                    } else {
                        if ($Favorite_Type == "Anime") {
                            try {
                                $Select_Anime = $Anirepo->prepare("SELECT * FROM anime WHERE Anime_ID = :Anime_ID AND User_ID = :User_ID");
                                $Select_Anime->bindParam(":Anime_ID", $Selected_Anime_ID);
                                $Select_Anime->bindParam(":User_ID", $_SESSION["User_ID"]);
                                $Select_Anime->execute();

                                $Select_Favorites = $Anirepo->prepare("SELECT * FROM favorites WHERE Anime_ID = :Anime_ID AND User_ID = :User_ID");
                                $Select_Favorites->bindParam(":Anime_ID", $Selected_Anime_ID);
                                $Select_Favorites->bindParam(":User_ID", $_SESSION["User_ID"]);
                                $Select_Favorites->execute();
            
                                if (empty($Selected_Anime_ID)) {
                                    $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Row <?= $Row_ID; ?>: Selected anime ID is required.</p> <?php
                                } else if (!is_numeric($Selected_Anime_ID)) {
                                    $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Row <?= $Row_ID; ?>: Selected anime ID must be numeric.</p> <?php
                                } else if ($Select_Anime->rowCount() < 1) {
                                    $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Row <?= $Row_ID; ?>: Anime not found on your repository.</p> <?php
                                } else if ($Select_Favorites->rowCount() > 0) {
                                    $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Row <?= $Row_ID; ?>: Anime is already on your favorites.</p> <?php
                                }
            
                                $Favorite_MAL_URL = "";
                            } catch(PDOException $Select_Anime_Favorites) {
                                $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Select_Anime_Favorites:</b> <?= $Select_Anime_Favorites->getMessage(); ?>.</p> <?php
                            }
                        } else {
                            try {
                                if ($Favorite_Type == "Character") {
                                    $MAL_Favorite_ID = substr($Favorite_MAL_URL, 34);
                                } else {
                                    $MAL_Favorite_ID = substr($Favorite_MAL_URL, 31);
                                }
                
                                $MAL_Favorite_ID = strtok($MAL_Favorite_ID, "/");
            
                                $Select_Favorites = $Anirepo->prepare("SELECT * FROM favorites WHERE MAL_Favorite_ID = :MAL_Favorite_ID AND User_ID = :User_ID");
                                $Select_Favorites->bindParam(":MAL_Favorite_ID", $MAL_Favorite_ID);
                                $Select_Favorites->bindParam(":User_ID", $_SESSION["User_ID"]);
                                $Select_Favorites->execute();
            
                                if (empty($Favorite_MAL_URL)) {
                                    $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Row <?= $Row_ID; ?>: Favorite MAL URL is required.</p> <?php
                                } else if (!filter_var($Favorite_MAL_URL, FILTER_SANITIZE_URL)) {
                                    $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Row <?= $Row_ID; ?>: Invalid favorite MAL URL format.</p> <?php
                                } else if ($Favorite_Type == "Character" && !strstr($Favorite_MAL_URL, "https://myanimelist.net/character/")) {
                                    $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Row <?= $Row_ID; ?>: Anime MAL URL should start at https://myanimelist.net/character/.</p> <?php
                                } else if ($Favorite_Type == "People" && !strstr($Favorite_MAL_URL, "https://myanimelist.net/people/")) {
                                    $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Row <?= $Row_ID; ?>: Anime MAL URL should start at https://myanimelist.net/people/.</p> <?php
                                } else if (! @file_get_contents($Favorite_MAL_URL)) {
                                    $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Row <?= $Row_ID; ?>: Favorite not found on the MAL website.</p> <?php
                                } else if ($Select_Favorites->rowCount() > 0) {
                                    $Error = 1;
            
                                    if ($Favorite_Type == "Character") {
                                        ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Row <?= $Row_ID; ?>: Character is already on your favorites.</p> <?php
                                    } else {
                                        ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Row <?= $Row_ID; ?>: Person is already on your favorites.</p> <?php
                                    }
                                }
            
                                $Selected_Anime_ID = NULL;
                            } catch(PDOException $Select_Favorites) {
                                $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Select_Favorites:</b> <?= $Select_Favorites->getMessage(); ?>.</p> <?php
                            }
                        }
                    }

                    if ($Error < 1) {
                        try {
                            if ($Favorite_Type != "Anime") {
                                $MAL_Website = file_get_html("$Favorite_MAL_URL");
                                $Favorite_Name = $MAL_Website->find("h1[class='title-name h1_bold_none']", 0);
                                $Favorite_Name = strip_tags($Favorite_Name->plaintext);
                                $Favorite_Name = trim(preg_replace("/\s+/", " ", $Favorite_Name));
                                $Favorite_Thumbnail = $MAL_Website->find("td[class='borderClass'] img", 0)->getAttribute("data-src");
                                $Favorite_Thumbnail = trim(filter_var($Favorite_Thumbnail, FILTER_SANITIZE_URL));
                            } else {
                                $Favorite_Name = $Favorite_Thumbnail = "";
                            }
        
                            $Insert_Favorites = $Anirepo->prepare("INSERT INTO favorites
                                VALUES ('', :MAL_Favorite_ID, :Anime_ID, :User_ID, '$Favorite_Name', :Placement, :Favorite_Type, :Favorite_MAL_URL, '$Favorite_Thumbnail', NOW(), NULL)");
                            $Insert_Favorites->bindParam(":MAL_Favorite_ID", $MAL_Favorite_ID);
                            $Insert_Favorites->bindParam(":User_ID", $_SESSION["User_ID"]);
                            $Insert_Favorites->bindParam(":Anime_ID", $Selected_Anime_ID);
                            $Insert_Favorites->bindParam(":Placement", $Placement);
                            $Insert_Favorites->bindParam(":Favorite_Type", $Favorite_Type);
                            $Insert_Favorites->bindParam(":Favorite_MAL_URL", $Favorite_MAL_URL);
                            $Insert_Favorites->execute();

                            ?>
                            
                            <p class="text-success"><i class="fas fa-check-circle"></i> Row <?= $Row_ID; ?>: Anime has been added to your favorites.</p>
                            
                            <?php

                            if ($Favorite_Type == "Anime") {
                                ?>

                                <script>
                                    $(function() {
                                        var Refresh_Favorite_Anime = true;

                                        $("#Favorite_Anime_Placeholder").load("functions/favorites/refresh-favorite-anime.php", {
                                            Refresh_Favorite_Anime: Refresh_Favorite_Anime
                                        });
                                    });
                                </script>
                                
                                <?php
                            } else if ($Favorite_Type == "Character") {
                                ?>

                                <script>
                                    $(function() {
                                        var Refresh_Favorite_Characters = true;
                                        
                                        $("#Favorite_Characters_Placeholder").load("functions/favorites/refresh-favorite-characters.php", {
                                            Refresh_Favorite_Characters: Refresh_Favorite_Characters
                                        });
                                    });
                                </script>
                                
                                <?php
                            } else if ($Favorite_Type == "People") {
                                ?>

                                <script>
                                    $(function() {
                                        var Refresh_Favorite_People = true;
                                        
                                        $("#Favorite_People_Placeholder").load("functions/favorites/refresh-favorite-people.php", {
                                            Refresh_Favorite_People: Refresh_Favorite_People
                                        });
                                    });
                                </script>
                                
                                <?php
                            }
                        } catch(PDOException $Insert_Favorites) {
                            ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Insert_Favorites:</b> <?= $Insert_Favorites->getMessage(); ?>.</p> <?php
                        }
                    }
                }
            }

            if ($Error < 1) {
                try {
                    $Insert_Logs = $Anirepo->prepare("INSERT INTO logs VALUES ('', :User_ID, 'Import Favorites', NOW())");
                    $Insert_Logs->bindParam(":User_ID", $_SESSION["User_ID"]);
                    $Insert_Logs->execute();
                } catch(PDOException $Insert_Logs) {
                    ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Insert_Logs:</b> <?= $Insert_Logs->getMessage(); ?>.</p> <?php
                }
            }
        }
    }

    $Anirepo = null;
?>

<script>
    $(function() {
        $("#Import_Favorites").prop("disabled", false);
    });
</script>