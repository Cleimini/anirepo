<?php
    include_once "../database-connection.php";

    if (!isset($_POST["Anime_Title_Keywords"]) || !isset($_POST["Sort_Anime_Type"]) || !isset($_POST["Sort_Premiered"]) || !isset($_POST["Sort_Source"]) || !isset($_POST["Sort_Monitoring_Type"]) || !isset($_POST["Sort_Anime"]) || !isset($_POST["Limit_Anime"]) || !isset($_SESSION["User_ID"])) {
        ?>

        <div class="bg-danger border p-3 rounded text-center text-white">
            <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
            <h4><strong>UNABLE TO SEARCH ANIME</strong></h4>

            <p>Refresh the page.</p>
        </div>

        <?php
    } else {
        $Anime_Title_Keywords = trim($_POST["Anime_Title_Keywords"]);
        $Wildcard_Anime_Title_Keywords = "%" . $Anime_Title_Keywords . "%";
        $Sort_Anime_Type = trim($_POST["Sort_Anime_Type"]);
        $Sort_Premiered = trim($_POST["Sort_Premiered"]);
        $Wildcard_Premiered = "%" . $Sort_Premiered . "%";
        $Sort_Source = trim($_POST["Sort_Source"]);
        $Sort_Monitoring_Type = trim($_POST["Sort_Monitoring_Type"]);
        $Sort_Anime = trim($_POST["Sort_Anime"]);
        $Limit_Anime = trim($_POST["Limit_Anime"]);
        $SQL_Query_Anime_Type = $SQL_Query_Premiered = $SQL_Query_Source = $SQL_Query_Monitoring_Type = $SQL_Query_Limit = "";

        if (!in_array($Sort_Anime, array("Anime_Title ASC", "Anime_Title DESC", "Date_Anime_Added ASC", "Date_Anime_Added DESC"))) {
            ?>

            <div class="bg-danger border p-3 rounded text-center text-white">
                <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                <h4><strong>INVALID SORT ANIME VALUE</strong></h4>

                <p>Refresh the page.</p>
            </div>

            <?php
        } else if ($Limit_Anime != "Show All" && !is_numeric($Limit_Anime)) {
            ?>

            <div class="bg-danger border p-3 rounded text-center text-white">
                <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                <h4><strong>INVALID LIMIT ANIME VALUE</strong></h4>

                <p>Refresh the page.</p>
            </div>

            <?php
        } else {
            if ($Sort_Anime_Type != "All Types") {
                $SQL_Query_Anime_Type = "AND Anime_Type = :Anime_Type";
            }

            if ($Sort_Premiered != "All Seasons") {
                $SQL_Query_Premiered = "AND Premiered LIKE :Premiered";
            }

            if ($Sort_Source != "All Sources") {
                $SQL_Query_Source = "AND Source = :Source";
            }

            if ($Sort_Monitoring_Type != "All Monitorings") {
                $SQL_Query_Monitoring_Type = "AND Monitoring_Type = :Monitoring_Type";
            }

            if ($Sort_Anime == "Anime_Title ASC") {
                $Sort_Anime_Label = "Anime Title (A-Z)";
            } else if ($Sort_Anime == "Anime_Title DESC") {
                $Sort_Anime_Label = "Anime Title (Z-A)";
            } else if ($Sort_Anime == "Date_Anime_Added ASC") {
                $Sort_Anime_Label = "Date Added (Recently)";
            } else if ($Sort_Anime == "Date_Anime_Added DESC") {
                $Sort_Anime_Label = "Date Added (Formerly)";
            }

            if ($Limit_Anime != "Show All") {
                $SQL_Query_Limit = "LIMIT "  . filter_var($Limit_Anime, FILTER_SANITIZE_NUMBER_INT);
            }

            try {
                $Select_Anime = $Anirepo->prepare("SELECT * FROM anime
                    JOIN monitorings ON anime.Anime_ID = monitorings.Anime_ID
                    WHERE anime.User_ID = :User_ID
                    AND Anime_Title LIKE :Anime_Title
                    $SQL_Query_Anime_Type $SQL_Query_Premiered $SQL_Query_Source
                    $SQL_Query_Monitoring_Type
                    ORDER BY $Sort_Anime
                    $SQL_Query_Limit");
                $Select_Anime->bindParam(":User_ID", $_SESSION["User_ID"]);
                $Select_Anime->bindParam(":Anime_Title", $Wildcard_Anime_Title_Keywords);
                
                if ($Sort_Anime_Type != "All Types") {
                    $Select_Anime->bindParam(":Anime_Type", $Sort_Anime_Type);
                }

                if ($Sort_Premiered != "All Seasons") {
                    $Select_Anime->bindParam(":Premiered", $Wildcard_Premiered);
                }

                if ($Sort_Source != "All Sources") {
                    $Select_Anime->bindParam(":Source", $Sort_Source);
                }

                if ($Sort_Monitoring_Type != "All Monitorings") {
                    $Select_Anime->bindParam(":Monitoring_Type", $Sort_Monitoring_Type);
                }

                $Select_Anime->execute();
                $Anime = $Select_Anime->fetchAll();

                $Count_Anime = $Anirepo->prepare("SELECT * FROM anime WHERE User_ID = :User_ID");
                $Count_Anime->bindParam(":User_ID", $_SESSION["User_ID"]);
                $Count_Anime->execute();

                if ($Select_Anime->rowCount() < 1) {
                    ?>

                    <div class="bg-info p-3 rounded text-white user-select-none">
                        <div class="text-center">
                            <h1 class="Large-Font-Size"><i class="fab fa-searchengin"></i></h1>
                            <h4><strong>NOTHING FOUND ON YOUR REPOSITORY</strong></h4>

                            <?php if (!empty($Anime_Title_Keywords)) { ?> <p>Cannot find any anime titled <b><q><?= $Anime_Title_Keywords; ?></q></b>.</p> <?php } ?>
                        </div>

                        <p class="mt-5"><strong>Anime Type:</strong> <?= $Sort_Anime_Type; ?></p>
                        <p><strong>Season:</strong> <?= $Sort_Premiered; ?></p>
                        <p><strong>Source:</strong> <?= $Sort_Source; ?></p>
                        <p><strong>Monitoring Type:</strong> <?= $Sort_Monitoring_Type; ?></p>
                        <p><strong>Sorting:</strong> <?= $Sort_Anime_Label; ?></p>
                        <p><strong>Limit:</strong> <?= $Limit_Anime; ?></p>
                    </div>

                    <?php
                } else {
                    ?>

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

                    <?php
                }
            } catch(PDOException $Select_Anime) {
                ?>

                <div class="bg-danger p-3 rounded text-center text-white user-select-none">
                    <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                    <h4><strong>SELECT_ANIME</strong></h4>

                    <p><?= $Select_Anime->getMessage(); ?>.</p>
                </div>

                <?php
            }
        }
    }

    $Anirepo = null;
?>