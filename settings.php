<?php require_once "functions/database-connection.php"; ?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <?php include_once "components/head.html"; ?>

        <link href="stylesheets/navigation.css" rel="stylesheet">
        <link href="stylesheets/main.css" rel="stylesheet">

        <script defer src="javascripts/navigation.js"></script>
        <script defer src="javascripts/settings.js"></script>

        <title>AniRepo: Settings</title>
    </head>

    <body>
        <?php
            if ($Disconnected) {
                header("Location: disconnected.php");
            } else {
                if (!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"] > 1) {
                    header("Location: index.php");
                } else {
                    include_once "components/navigation.php";

                    ?>

                    <main class="float-end position-relative">
                        <section class="p-4 text-white">
                                <h1><strong>SETTINGS</strong></h1>

                                <p>Overview</p>
                            </div>
                        </section>

                        <section class="m-3">
                            <?php
                                try {
                                    $Select_Trendings = $Anirepo->prepare("SELECT * FROM trendings");
                                    $Select_Trendings->execute();

                                    $Select_Seasonal_Trendings = $Anirepo->prepare("SELECT * FROM trendings WHERE Trending_Type = 'Seasonal' ORDER BY Date_Trending_Added DESC LIMIT 1");
                                    $Select_Seasonal_Trendings->execute();
                                    $Seasonal_Trending = $Select_Seasonal_Trendings->fetch();

                                    $Select_Airing_Trendings = $Anirepo->prepare("SELECT * FROM trendings WHERE Trending_Type = 'Airing' ORDER BY Date_Trending_Added DESC LIMIT 1");
                                    $Select_Airing_Trendings->execute();
                                    $Airing_Trending = $Select_Airing_Trendings->fetch();

                                    $Select_Updated_Trendings = $Anirepo->prepare("SELECT * FROM trendings WHERE Trending_Type = 'Updated' ORDER BY Date_Trending_Added DESC LIMIT 1");
                                    $Select_Updated_Trendings->execute();
                                    $Updated_Trending = $Select_Updated_Trendings->fetch();

                                    $Select_Upcoming_Trendings = $Anirepo->prepare("SELECT * FROM trendings WHERE Trending_Type = 'Upcoming' ORDER BY Date_Trending_Added DESC LIMIT 1");
                                    $Select_Upcoming_Trendings->execute();
                                    $Upcoming_Trending = $Select_Upcoming_Trendings->fetch();

                                    $Select_Popular_Trendings = $Anirepo->prepare("SELECT * FROM trendings WHERE Trending_Type = 'Popular' ORDER BY Date_Trending_Added DESC LIMIT 1");
                                    $Select_Popular_Trendings->execute();
                                    $Popular_Trending = $Select_Popular_Trendings->fetch();

                                    ?>

                                    <article class="bg-white border p-3 rounded">
                                        <h4 class="text-center text-md-start"><strong>Update Trending Anime on MAL</strong></h4>

                                        <form action="functions/settings/update-trendings.php" method="POST">
                                            <div class="row">
                                                <div class="col-md mt-2">
                                                    <button class="bg-primary btn btn-sm py-3 text-white w-100" id="Update_Seasonals" type="submit"><b>SEASONALS</b> <i class="fas fa-refresh"></i></button>
                                                </div>

                                                <div class="col-md mt-2">
                                                    <button class="bg-info btn btn-sm py-3 text-white w-100" id="Update_Airings" type="submit"><b>AIRINGS</b> <i class="fas fa-refresh"></i></button>
                                                </div>

                                                <div class="col-md mt-2">
                                                    <button class="bg-danger btn btn-sm py-3 text-white w-100" id="Update_Episodes" type="submit"><b>EPISODES</b> <i class="fas fa-refresh"></i></button>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md mt-2">
                                                    <button class="bg-success btn btn-sm py-3 text-white w-100" id="Update_Upcomings" type="submit"><b>UPCOMINGS</b> <i class="fas fa-refresh"></i></button>
                                                </div>

                                                <div class="col-md mt-2">
                                                    <button class="bg-warning btn btn-sm py-3 text-dark w-100" id="Update_Populars" type="submit"><b>POPULARS</b> <i class="fas fa-refresh"></i></button>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="mt-3" id="Update_Trendings_Message">
                                            <?php
                                                if ($Select_Trendings->rowCount() < 1) {
                                                    ?> <p class="text-center text-danger"><i class="fas fa-times-circle"></i> No trending anime found on the database.</p> <?php
                                                }
                                            ?>
                                        </div>

                                        <hr>

                                        <p><b>Last Seasonals Updated:</b>
                                            <span id="Seasonal_Anime_Placeholder">
                                                <?php
                                                    if (!empty($Seasonal_Trending["Date_Trending_Added"])) {
                                                        echo date("M d, Y h:i:s A", strtotime($Seasonal_Trending["Date_Trending_Added"]));
                                                    } else {
                                                        echo "No updates";
                                                    }
                                                ?>
                                            </span>
                                        </p>

                                        <p><b>Last Airings Updated:</b>
                                            <span id="Airing_Anime_Placeholder">
                                                <?php
                                                    if (!empty($Airing_Trending["Date_Trending_Added"])) {
                                                        echo date("M d, Y h:i:s A", strtotime($Airing_Trending["Date_Trending_Added"]));
                                                    } else {
                                                        echo "No updates";
                                                    }
                                                ?>
                                            </span>
                                        </p>

                                        <p><b>Last Episodes Updated:</b>
                                            <span id="Episode_Anime_Placeholder">
                                                <?php
                                                    if (!empty($Updated_Trending["Date_Trending_Added"])) {
                                                        echo date("M d, Y h:i:s A", strtotime($Updated_Trending["Date_Trending_Added"]));
                                                    } else {
                                                        echo "No updates";
                                                    }
                                                ?>
                                            </span>
                                        </p>

                                        <p><b>Last Upcomings Updated:</b>
                                            <span id="Upcoming_Anime_Placeholder">
                                                <?php
                                                    if (!empty($Upcoming_Trending["Date_Trending_Added"])) {
                                                        echo date("M d, Y h:i:s A", strtotime($Upcoming_Trending["Date_Trending_Added"]));
                                                    } else {
                                                        echo "No updates";
                                                    }
                                                ?>
                                            </span>
                                        </p>
                                        
                                        <p><b>Last Populars Updated:</b>
                                            <span id="Popular_Anime_Placeholder">
                                                <?php
                                                    if (!empty($Popular_Trending["Date_Trending_Added"])) {
                                                        echo date("M d, Y h:i:s A", strtotime($Popular_Trending["Date_Trending_Added"]));
                                                    } else {
                                                        echo "No updates";
                                                    }
                                                ?>
                                            </span>
                                        </p>
                                    </article>

                                    <?php
                                } catch(PDOException $Select_Trendings) {
                                    ?>
                                    
                                    <article class="bg-danger p-3 rounded text-center text-white user-select-none">
                                        <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                                        <h4><strong>SELECT_TRENDINGS</strong></h4>

                                        <p><?= $Select_Trendings->getMessage(); ?>.</p>
                                    </article>

                                    <?php
                                }
                            ?>
                        </section>

                        <section class="m-3">
                            <article class="bg-white border p-3 rounded">
                                <h4 class="mb-3 text-center text-md-start"><strong>Expired Token Codes</strong></h4>

                                <div class="text-center" id="Expired_Token_Codes_Placeholder">
                                    <?php
                                        try {
                                            $Select_Resets = $Anirepo->prepare("SELECT * FROM resets WHERE Date_Token_Code_Expires <= DATE_ADD(NOW(), INTERVAL 5 MINUTE)");
                                            $Select_Resets->execute();
                                            $Resets = $Select_Resets->fetchAll();

                                            if ($Select_Resets->rowCount() < 1) {
                                                ?>

                                                <div class="text-info user-select-none">
                                                    <h1 class="Large-Font-Size"><i class="fas fa-thumbs-up"></i></h1>
                                                    <h4><strong>NO EXPIRED TOKEN CODES FOUND</strong></h4>
                                                </div>

                                                <?php
                                            } else {
                                                ?>

                                                <div class="rounded table-responsive">
                                                    <table class="border-danger table table-hover">
                                                        <thead class="table-dark">
                                                            <tr>
                                                                <th>User ID</th>
                                                                <th>Expiration Date</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            <?php
                                                                foreach($Resets as $Reset) {
                                                                    ?>

                                                                    <tr>
                                                                        <td><?= $Reset["User_ID"]; ?></td>
                                                                        <td><?= $Reset["Date_Token_Code_Expires"]; ?></td>
                                                                    </tr>

                                                                    <?php
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <form action="functions/settings/flush-token-codes.php" method="POST">
                                                    <button class="bg-danger btn btn-sm px-5 py-3 text-white" id="Flush_Token_Codes" type="submit"><b> FLUSH TOKEN CODES</b> <i class="fas fa-eraser"></i></button>
                                                </form>

                                                <div class="mt-3 text-start" id="Flush_Token_Codes_Message"></div>

                                                <?php
                                            }
                                        } catch(PDOException $Select_Resets) {
                                            ?>

                                            <div class="text-danger user-select-none">
                                                <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                                                <h4><strong>SELECT_RESETS</strong></h4>

                                                <p><?= $Select_Resets->getMessage(); ?>.</p>
                                            </div>

                                            <?php
                                        }
                                    ?>
                                </div>
                            </article>
                        </section>
                    </main>

                    <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="Bootstrap_Modal">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                            </div>
                        </div>
                    </div>

                    <?php
                }
            }
        ?>
    </body>
</html>