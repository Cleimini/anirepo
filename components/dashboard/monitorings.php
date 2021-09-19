<section class="m-3 text-center text-white">
    <?php
        try {
            $Select_Monitorings = $Anirepo->prepare("SELECT *,
                COUNT(CASE WHEN monitorings.Monitoring_Type = 'Finished' THEN 0 ELSE NULL END) AS Total_Finished_Monitorings,
                COUNT(CASE WHEN monitorings.Monitoring_Type = 'Currently' THEN 0 ELSE NULL END) AS Total_Currently_Monitorings,
                COUNT(CASE WHEN monitorings.Monitoring_Type = 'Postponed' THEN 0 ELSE NULL END) AS Total_Postponed_Monitorings,
                COUNT(CASE WHEN monitorings.Monitoring_Type = 'Scheduled' THEN 0 ELSE NULL END) AS Total_Scheduled_Monitorings
                FROM monitorings
                JOIN anime ON monitorings.Anime_ID = anime.Anime_ID
                WHERE anime.User_ID = :User_ID AND monitorings.Monitoring_Type = 'Finished'");
            $Select_Monitorings->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Monitorings->execute();
            $Monitoring = $Select_Monitorings->fetch();

            ?>

            <article class="Grid-Container Grid-4">
                <div class="bg-danger border p-2 rounded">
                    <h6><strong>FINISHED</strong></h6>

                    <div class="clearfix mt-3">
                        <h1 class="float-start"><i class="fas fa-tv"></i></h1>
                        <h1 class="float-end"><?= number_format($Monitoring["Total_Finished_Monitorings"]); ?></h1>
                    </div>
                </div>

                <div class="bg-info border p-2 rounded">
                    <h6><strong>CURRENTLY</strong></h6>

                    <div class="clearfix mt-3">
                        <h1 class="float-start"><i class="fas fa-play"></i></h1>
                        <h1 class="float-end"><?= number_format($Monitoring["Total_Currently_Monitorings"]); ?></h1>
                    </div>
                </div>

                <div class="bg-success border p-2 rounded">
                    <h6><strong>POSTPONED</strong></h6>

                    <div class="clearfix mt-3">
                        <h1 class="float-start"><i class="fas fa-stop"></i></h1>
                        <h1 class="float-end"><?= number_format($Monitoring["Total_Postponed_Monitorings"]); ?></h1>
                    </div>
                </div>

                <div class="bg-warning border p-2 rounded">
                    <h6><strong>SCHEDULED</strong></h6>

                    <div class="clearfix mt-3">
                        <h1 class="float-start"><i class="fas fa-calendar-alt"></i></h1>
                        <h1 class="float-end"><?= number_format($Monitoring["Total_Scheduled_Monitorings"]); ?></h1>
                    </div>
                </div>
            </article>

            <?php
        } catch(PDOException $Select_Monitorings) {
            ?>

            <article class="bg-danger p-3 rounded user-select-none">
                <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                <h4><strong>SELECT_MONITORINGS</strong></h4>

                <p><?= $Select_Monitorings->getMessage(); ?>.</p>
            </article>

            <?php
        }
    ?>
</section>

<section class="m-3">
    <?php
        try {
            $Select_Currently_Monitorings = $Anirepo->prepare("SELECT * FROM monitorings
                JOIN anime ON monitorings.Anime_ID = anime.Anime_ID
                WHERE anime.User_ID = :User_ID AND monitorings.Monitoring_Type = 'Currently'");
            $Select_Currently_Monitorings->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Currently_Monitorings->execute();
            $Currently_Monitorings = $Select_Currently_Monitorings->fetchAll();

            $Select_Postponed_Monitorings = $Anirepo->prepare("SELECT * FROM monitorings
                JOIN anime ON monitorings.Anime_ID = anime.Anime_ID
                WHERE anime.User_ID = :User_ID AND monitorings.Monitoring_Type = 'Postponed'");
            $Select_Postponed_Monitorings->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Postponed_Monitorings->execute();
            $Postponed_Monitorings = $Select_Postponed_Monitorings->fetchAll();

            $Select_Scheduled_Monitorings = $Anirepo->prepare("SELECT * FROM monitorings
                JOIN anime ON monitorings.Anime_ID = anime.Anime_ID
                WHERE anime.User_ID = :User_ID AND monitorings.Monitoring_Type = 'Scheduled'");
            $Select_Scheduled_Monitorings->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Scheduled_Monitorings->execute();
            $Scheduled_Monitorings = $Select_Scheduled_Monitorings->fetchAll();

            if ($Select_Currently_Monitorings->rowCount() < 1 && $Select_Postponed_Monitorings->rowCount() < 1 && $Select_Scheduled_Monitorings->rowCount() < 1) {
                ?>

                <article class="bg-info p-3 rounded text-center text-white user-select-none">
                    <h1 class="Large-Font-Size"><i class="fas fa-info-circle"></i></h1>
                    <h4><strong>NO ACTIVE MONITORINGS ON YOUR REPOSITORY</strong></h4>
                </article>

                <?php
            } else {
                ?>

                <article class="bg-white border p-3 rounded">
                    <h4 class="text-center text-md-start"><strong>Active Monitorings</strong></h4>

                    <div class="mt-3" id="Calendar"></div>

                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            var Calendar = document.getElementById("Calendar"),
                                Calendar = new FullCalendar.Calendar(Calendar, {
                                height: 400,
                                initialDate: "<?= date('Y-m-d'); ?>",
                                initialView: "dayGridWeek",
                                timeZone: "UTC",
                                headerToolbar: {
                                    left: "prev,next",
                                    center: "title",
                                    right: "dayGridDay,dayGridWeek"
                                },

                                events: [
                                    <?php
                                        foreach ($Currently_Monitorings as $Currently_Monitoring) {
                                            echo "{";
                                                echo "title:" . "'" . $Currently_Monitoring["Anime_Title"] . "',";
                                                echo "url:" . "'" . $Currently_Monitoring["Anime_MAL_URL"] . "',";
                                                echo "start:" . "'" . $Currently_Monitoring["Date_Started"] . "',";
                                                echo "end:" . "'" . $Currently_Monitoring["Date_Finished"] . "',";
                                                echo "backgroundColor: '#0d6efd',";
                                                echo "borderColor: '#0d6efd',";
                                                echo "textColor: '#ffffff'";
                                            echo "},";
                                        }

                                        foreach ($Postponed_Monitorings as $Postponed_Monitoring) {
                                            echo "{";
                                                echo "title:" . "'" . $Postponed_Monitoring["Anime_Title"] . "',";
                                                echo "url:" . "'" . $Postponed_Monitoring["Anime_MAL_URL"] . "',";
                                                echo "start:" . "'" . $Postponed_Monitoring["Date_Started"] . "',";
                                                echo "end:" . "'" . $Postponed_Monitoring["Date_Finished"] . "',";
                                                echo "backgroundColor: '#dc3545',";
                                                echo "borderColor: '#dc3545',";
                                                echo "textColor: '#ffffff'";
                                            echo "},";
                                        }
                                        
                                        foreach ($Scheduled_Monitorings as $Scheduled_Monitoring) {
                                            echo "{";
                                                echo "title:" . "'" . $Scheduled_Monitoring["Anime_Title"] . "',";
                                                echo "url:" . "'" . $Scheduled_Monitoring["Anime_MAL_URL"] . "',";
                                                echo "start:" . "'" . $Scheduled_Monitoring["Date_Scheduled"] . "',";
                                                echo "backgroundColor: '#0dcaf0',";
                                                echo "borderColor: '#0dcaf0',";
                                                echo "textColor: '#ffffff'";
                                            echo "},";
                                        }
                                    ?>
                                ]
                            });

                            Calendar.setOption("locale", "ph");
                            Calendar.render();
                        });
                    </script>
                </article>

                <?php
            }
        } catch(PDOException $Select_Active_Monitorings) {
            ?>

            <article class="bg-danger p-3 rounded text-center text-white user-select-none">
                <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                <h4><strong>SELECT_ACTIVE_MONITORINGS</strong></h4>

                <p><?= $Select_Active_Monitorings->getMessage(); ?>.</p>
            </article>

            <?php
        }
    ?>
</section>