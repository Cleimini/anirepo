<?php require_once "functions/database-connection.php"; ?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <?php include_once "components/head.html"; ?>

        <link href="stylesheets/navigation.css" rel="stylesheet">
        <link href="stylesheets/main.css" rel="stylesheet">

        <script defer src="javascripts/navigation.js"></script>
        <script defer src="javascripts/logs.js"></script>

        <title>AniRepo: Logs</title>
    </head>

    <body>
        <?php
            if ($Disconnected) {
                header("Location: disconnected.php");
            } else {
                if (!isset($_SESSION["User_ID"])) {
                    header("Location: index.php");
                } else {
                    include_once "components/navigation.php";

                    ?>

                    <main class="float-end position-relative">
                        <section class="p-4 text-white">
                            <article class="row">
                                <div class="col-md">
                                    <h1><strong>MY ACTIVITY LOGS</strong></h1>

                                    <p>Overview</p>
                                </div>

                                <div class="col-md text-end">
                                    <button class="bg-success btn btn-sm p-3 rounded text-white Adjustable-Button-Width Custom-Button-Border Custom-Button-Border-Green" id="Download_Logs" type="button"><b>DOWNLOAD LOGS</b> <i class="fas fa-download"></i></button>
                                </div>
                            </article>

                            <div id="Download_Logs_Message"></div>
                        </section>

                        <section class="m-3">
                            <?php
                                try {
                                    $Select_Distinct_Log_Types = $Anirepo->prepare("SELECT DISTINCT Log_Type FROM logs WHERE User_ID = :User_ID ORDER BY Log_Type ASC");
                                    $Select_Distinct_Log_Types->bindParam(":User_ID", $_SESSION["User_ID"]);
                                    $Select_Distinct_Log_Types->execute();
                                    $Distinct_Log_Types = $Select_Distinct_Log_Types->fetchAll();

                                    ?>

                                    <article class="bg-white border p-3 rounded">
                                        <h4 class="mb-2 text-center text-md-start"><strong>Search Activity Logs</strong></h4>

                                        <form action="functions/logs/search-logs.php" method="POST">
                                            <div class="form-group position-relative">
                                                <input class="form-control form-control-lg" id="Date_Cataloged_Keywords" type="date">

                                                <span class="position-absolute"><i class="fas fa-calendar-alt"></i></span>
                                            </div>

                                            <div class="row">
                                                <div class="col-md mt-2">
                                                    <div class="form-group position-relative">
                                                        <select class="form-control form-control-sm w-100" id="Sort_Log_Type">
                                                            <option value="All Types">All Types</option>

                                                            <?php
                                                                if ($Select_Distinct_Log_Types->rowCount() > 0) {
                                                                    foreach ($Distinct_Log_Types as $Distinct_Log_Type) {
                                                                        ?> <option value="<?= $Distinct_Log_Type['Log_Type']; ?>"><?= $Distinct_Log_Type["Log_Type"]; ?></option> <?php
                                                                    }
                                                                }
                                                            ?>
                                                        </select>

                                                        <span class="position-absolute" style="top: 15px;"><i class="fas fa-scroll"></i></span>
                                                    </div>
                                                </div>

                                                <div class="col-md mt-2">
                                                    <div class="form-group position-relative">
                                                        <select class="form-control form-control-sm w-100" id="Sort_Logs">
                                                            <option value="Date_Cataloged DESC">Date Cataloged (Recently)</option>
                                                            <option value="Date_Cataloged ASC">Date Cataloged (Formerly)</option>
                                                        </select>

                                                        <span class="position-absolute" style="top: 15px;"><i class="fas fa-scroll"></i></span>
                                                    </div>
                                                </div>

                                                <div class="col-md mt-2">
                                                    <div class="form-group position-relative">
                                                        <select class="form-control form-control-sm w-100" id="Limit_Logs">
                                                            <option value="25">Show 25 Activity Logs</option>
                                                            <option value="50">Show 50 Activity Logs</option>
                                                            <option value="100">Show 100 Activity Logs</option>
                                                            <option value="150">Show 150 Activity Logs</option>
                                                            <option value="200">Show 200 Activity Logs</option>
                                                            <option value="250">Show 250 Activity Logs</option>
                                                            <option value="300">Show 300 Activity Logs</option>
                                                            <option value="400">Show 400 Activity Logs</option>
                                                            <option value="500">Show 500 Activity Logs</option>
                                                            <option value="Show All">Show All Activity Logs</option>
                                                        </select>

                                                        <span class="position-absolute" style="top: 15px;"><i class="fas fa-sort-numeric-up"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </article>

                                    <?php
                                } catch(PDOException $Select_Distinct_Log_Types) {
                                    ?>

                                    <article class="bg-danger p-3 rounded text-center text-white user-select-none">
                                        <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                                        <h4><strong>SELECT_DISTINCT_LOG_TYPES</strong></h4>

                                        <p><?= $Select_Distinct_Log_Types->getMessage(); ?>.</p>
                                    </article>

                                    <?php
                                }
                            ?>
                        </section>

                        <section class="m-3" id="Logs_Placeholder">
                            <?php
                                try {
                                    $Select_Logs = $Anirepo->prepare("SELECT * FROM logs WHERE User_ID = :User_ID ORDER BY Date_Cataloged DESC LIMIT 25");
                                    $Select_Logs->bindParam(":User_ID", $_SESSION["User_ID"]);
                                    $Select_Logs->execute();
                                    $Logs = $Select_Logs->fetchAll();

                                    if ($Select_Logs->rowCount() < 1) {
                                        ?>

                                        <article class="bg-danger p-3 rounded text-center text-white user-select-none">
                                            <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                                            <h4><strong>NO ACTIVITY LOGS FOUND ON THE DATABASE</strong></h4>
                                        </article>

                                        <?php
                                    } else {
                                        ?>

                                        <h4 class="text-center text-md-start"><strong>Activity Logs (<?= number_format($Select_Logs->rowCount()); ?>)</strong></h4>

                                        <p class="text-center text-md-start"><i>Showing <?= number_format($Select_Logs->rowCount()); ?> Activity Logs</i></p>

                                        <article class="mt-5 text-center">
                                            <div class="rounded table-responsive">
                                                <table class="border-danger table table-hover">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Type</th>
                                                            <th>Date Cataloged</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <?php
                                                            foreach($Logs as $Log) {
                                                                ?>

                                                                <tr>
                                                                    <td><?= number_format($Log["Log_ID"]); ?></td>
                                                                    <td><?= $Log["Log_Type"]; ?></td>
                                                                    <td><?= date("M d, Y h:i:s A", strtotime($Log["Date_Cataloged"])); ?></td>
                                                                </tr>

                                                                <?php
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </article>

                                        <?php
                                    }
                                } catch(PDOException $Select_Logs) {
                                    ?>

                                    <article class="bg-danger p-3 rounded text-center text-white user-select-none">
                                        <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                                        <h4><strong>SELECT_LOGS</strong></h4>

                                        <p><?= $Select_Logs->getMessage(); ?>.</p>
                                    </article>

                                    <?php
                                }
                            ?>
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