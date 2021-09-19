<?php
    include_once "../database-connection.php";

    if (!isset($_POST["Date_Cataloged_Keywords"]) || !isset($_POST["Sort_Log_Type"]) || !isset($_POST["Sort_Logs"]) || !isset($_POST["Limit_Logs"]) || !isset($_SESSION["User_ID"])) {
        ?>

        <article class="bg-danger p-3 rounded text-center text-white user-select-none">
            <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
            <h4><strong>UNABLE TO SEARCH LOGS</strong></h4>

            <p>Refresh the page.</p>
        </article>

        <?php
    } else {
        $Date_Cataloged_Keywords = preg_replace("([^0-9/])", "-", $_POST["Date_Cataloged_Keywords"]);
        $Wildcard_Date_Cataloged_Keywords = "%" . $Date_Cataloged_Keywords . "%";
        $Sort_Log_Type = trim($_POST["Sort_Log_Type"]);
        $Sort_Logs = trim($_POST["Sort_Logs"]);
        $Limit_Logs = trim($_POST["Limit_Logs"]);

        if (!empty($Date_Cataloged_Keywords) && !DateTime::createFromFormat("Y-m-d", $Date_Cataloged_Keywords)) {
            ?>

            <article class="bg-danger p-3 rounded text-center text-white user-select-none">
                <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                <h4><strong>INVALID DATE CATALOGED KEYWORDS FORMAT</strong></h4>

                <p>Refresh the page.</p>
            </article>

            <?php
        } else if (!in_array($Sort_Logs, array("Date_Cataloged ASC", "Date_Cataloged DESC"))) {
            ?>

            <article class="bg-danger p-3 rounded text-center text-white user-select-none">
                <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                <h4><strong>INVALID SORT LOGS VALUE</strong></h4>

                <p>Refresh the page.</p>
            </article>

            <?php
        } else {
            if ($Sort_Log_Type == "All Types") {
                $SQL_Query_Where = "";
            } else {
                $SQL_Query_Where = "AND Log_Type = :Log_Type";
            }

            if (is_numeric($Limit_Logs)) {
                $Limit_Logs = filter_var($Limit_Logs, FILTER_SANITIZE_NUMBER_INT);
                $SQL_Query_Limit = "LIMIT " . $Limit_Logs;
            } else {
                $SQL_Query_Limit = "";
            }

            try {
                $Select_Logs = $Anirepo->prepare("SELECT * FROM logs
                    WHERE User_ID = :User_ID
                    $SQL_Query_Where
                    AND Date_Cataloged LIKE :Date_Cataloged
                    ORDER BY $Sort_Logs
                    $SQL_Query_Limit");
                $Select_Logs->bindParam(":User_ID", $_SESSION["User_ID"]);

                if ($Sort_Log_Type != "All Types") {
                    $Select_Logs->bindParam(":Log_Type", $Sort_Log_Type);
                }
                
                $Select_Logs->bindParam(":Date_Cataloged", $Wildcard_Date_Cataloged_Keywords);
                $Select_Logs->execute();
                $Logs = $Select_Logs->fetchAll();

                if ($Select_Logs->rowCount() < 1) {
                    ?>

                    <article class="bg-info p-3 rounded text-center text-white user-select-none">
                        <h1 class="Large-Font-Size"><i class="fas fa-info-circle"></i></h1>
                        <h4><strong>NO LOGS FOUND ON THE GIVEN DATE</strong></h4>
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
        }
    }

    $Anirepo = null;
?>