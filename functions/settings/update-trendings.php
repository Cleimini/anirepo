<?php
    include_once "../database-connection.php";
    include_once "../simple_html_dom.php";

    if (!isset($_POST["Update_Seasonals"]) && !isset($_POST["Update_Airings"]) && !isset($_POST["Update_Episodes"]) && !isset($_POST["Update_Upcomings"]) && !isset($_POST["Update_Populars"]) || !isset($_SESSION["User_ID"]) || $_SESSION["User_ID"] != 1) {
        ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Unable to update trending anime, refresh the page.</p> <?php
    } else {
        try {
            $MAL_URL = file_get_html("https://myanimelist.net/");

            if (isset($_POST["Update_Seasonals"])) {
                $Trending_Type = "Seasonal";

                $Parent = $MAL_URL->find("div[class='widget seasonal left']", 0);
                $Link = $Parent->find("div[class='widget-content'] div[class='widget-slide-block'] a");
                $Title = $Parent->find("div[class='widget-content'] h3[class='h3_character_name']");
            } else if (isset($_POST["Update_Episodes"])) {
                $Trending_Type = "Updated";

                $Parent = $MAL_URL->find("div[class='widget latest_episode_video left']", 0);
                $Link = $Parent->find("li[class='btn-anime episode'] h3[class='latest_updated_h3'] a");
                $Title = $Parent->find("li[class='btn-anime episode'] h3[class='latest_updated_h3']");
            } else {
                if (isset($_POST["Update_Airings"])) {
                    $Trending_Type = "Airing";
                    $Parent = $MAL_URL->find("div[class='widget airing_ranking right']", 0);
                } else if (isset($_POST["Update_Upcomings"])) {
                    $Trending_Type = "Upcoming";
                    $Parent = $MAL_URL->find("div[class='widget upcoming_ranking right']", 0);
                } else if (isset($_POST["Update_Populars"])) {
                    $Trending_Type = "Popular";
                    $Parent = $MAL_URL->find("div[class='widget popular_ranking right']", 0);
                }

                $Link = $Parent->find("li[class='ranking-unit'] h3[class='h3_side'] a");
                $Title = $Parent->find("li[class='ranking-unit'] h3[class='h3_side']");
            }

            $Delete_Trendings = $Anirepo->prepare("DELETE FROM trendings WHERE Trending_Type = '$Trending_Type'");
            $Delete_Trendings->execute();

            for ($i = 0; $i < sizeof($Title); $i++) {
                $Trending_Title = $Title[$i]->plaintext;
                $Trending_Title = trim(preg_replace("/\s+/", " ", $Trending_Title));
                $Trending_MAL_URL = $Link[$i]->getAttribute("href");
                $Trending_Thumbnail_URL = $Trending_MAL_URL . "/pics";
                $Trending_Thumbnail_HTML = file_get_html("$Trending_Thumbnail_URL");
                $Trending_Thumbnail = $Trending_Thumbnail_HTML->find("td[class='borderClass'] div div img", 0)->getAttribute("data-src");

                $Insert_Trendings = $Anirepo->prepare("INSERT INTO trendings VALUES ('', :Trending_Title, '$Trending_Type', :Trending_MAL_URL, :Trending_Thumbnail, NOW())");
                $Insert_Trendings->bindParam(":Trending_Title", $Trending_Title);
                $Insert_Trendings->bindParam(":Trending_MAL_URL", $Trending_MAL_URL);
                $Insert_Trendings->bindParam(":Trending_Thumbnail", $Trending_Thumbnail);
                $Insert_Trendings->execute();
            }

            if (isset($_POST["Update_Seasonals"])) {
                ?> <p class="text-success"><i class="fas fa-check-circle"></i> Trending seasonal anime has been updated from the MAL website.</p> <?php
            } else if (isset($_POST["Update_Airings"])) {
                ?> <p class="text-success"><i class="fas fa-check-circle"></i> Trending airing anime has been updated from the MAL website.</p> <?php
            } else if (isset($_POST["Update_Episodes"])) {
                ?> <p class="text-success"><i class="fas fa-check-circle"></i> Anime with new episodes has been updated from the MAL website.</p> <?php
            } else if (isset($_POST["Update_Upcomings"])) {
                ?> <p class="text-success"><i class="fas fa-check-circle"></i> Trending upcoming anime has been updated from the MAL website.</p> <?php
            } else if (isset($_POST["Update_Populars"])) {
                ?> <p class="text-success"><i class="fas fa-check-circle"></i> All-time popular anime has been updated from the MAL website.</p> <?php
            }

            ?>

            <script>
                $(function() {
                    <?php
                        if (isset($_POST["Update_Seasonals"])) {
                            ?> $("#Seasonal_Anime_Placeholder").html("<?= date('M d, Y h:i:s A'); ?>"); <?php
                        } else if (isset($_POST["Update_Airings"])) {
                            ?> $("#Airing_Anime_Placeholder").html("<?= date('M d, Y h:i:s A'); ?>"); <?php
                        } else if (isset($_POST["Update_Episodes"])) {
                            ?> $("#Episode_Anime_Placeholder").html("<?= date('M d, Y h:i:s A'); ?>"); <?php
                        } else if (isset($_POST["Update_Upcomings"])) {
                            ?> $("#Upcoming_Anime_Placeholder").html("<?= date('M d, Y h:i:s A'); ?>"); <?php
                        } else if (isset($_POST["Update_Populars"])) {
                            ?> $("#Popular_Anime_Placeholder").html("<?= date('M d, Y h:i:s A'); ?>"); <?php
                        }
                    ?>
                });
            </script>

            <?php
        } catch(PDOException $Delete_Insert_Trendings) {
            ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Delete_Insert_Trendings:</b> <?= $Delete_Insert_Trendings->getMessage(); ?>.</p> <?php
        }
    }

    $Anirepo = null;
?>

<script>
    $(function() {
        $("#Update_Seasonals, #Update_Airings, #Update_Episodes, #Update_Upcomings, #Update_Populars").prop("disabled", false);
    });
</script>