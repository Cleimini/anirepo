<?php require_once "functions/database-connection.php"; ?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <?php
            include_once "components/head.html";

            if (!isset($_SESSION["User_ID"])) {
                ?>

                <link href="stylesheets/homepage.css" rel="stylesheet">

                <script defer src="javascripts/homepage.js"></script>

                <?php
            } else {
                ?>

                <link href="node_modules/fullcalendar/main.css" rel="stylesheet">
                <link href="stylesheets/navigation.css" rel="stylesheet">
                <link href="stylesheets/main.css" rel="stylesheet">

                <script src="node_modules/fullcalendar/main.js"></script>
                <script src="node_modules/chart.js/dist/chart.js"></script>
                <script defer src="javascripts/navigation.js"></script>
                <script defer src="javascripts/dashboard.js"></script>

                <?php
            }
        ?>

        <title>AniRepo</title>
    </head>

    <body>
        <?php
            if ($Disconnected) {
                header("Location: disconnected.php");
            } else {
                if (!isset($_SESSION["User_ID"])) {
                    include_once "components/homepage.php";
                } else {
                    include_once "components/navigation.php";

                    ?>

                    <main class="float-end position-relative">
                        <section class="p-4 text-white">
                            <h1><strong>DASHBOARD</strong></h1>

                            <p>Overview</p>
                        </section>

                        <?php
                            include_once "components/dashboard/monitorings.php";
                            include_once "components/dashboard/trendings.php";
                            include_once "components/dashboard/sources.php";
                            include_once "components/dashboard/types.php";
                            include_once "components/dashboard/studios.php";
                            include_once "components/dashboard/premiered.php";
                            include_once "components/dashboard/genres.php";
                        ?>
                    </main>

                    <?php
                }

                ?>

                <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="Bootstrap_Modal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                        </div>
                    </div>
                </div>

                <?php
            }
        ?>
    </body>
</html>