<?php include_once "../database-connection.php"; ?>

<div class="modal-header text-white">
    <h1><strong>SIGN OUT</strong></h1>
    <h2 data-bs-dismiss="modal"><i class="fas fa-times-circle"></i></h2>
</div>

<div class="modal-body text-center user-select-none">
    <?php
        if (!isset($_POST["Sign_Out"]) || !isset($_SESSION["User_ID"])) {
            ?>

            <div class="text-danger">
                <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                <h4><strong>UNABLE TO SIGN OUT</strong></h4>

                <p>Refresh the page.</p>
            </div>

            <?php
        } else {
            try {
                $Insert_Logs = $Anirepo->prepare("INSERT INTO logs VALUES ('', :User_ID, 'Sign Out', NOW())");
                $Insert_Logs->bindParam(":User_ID", $_SESSION["User_ID"]);
                $Insert_Logs->execute();

                session_destroy();

                ?>

                <div class="text-success">
                    <h1 class="Large-Font-Size"><i class="fas fa-check-circle"></i></h1>
                    <h4><strong>SIGNING OUT</strong></h4>
                    
                    <p>Wait for a moment.</p>
                </div>

                <script>
                    $(function() {
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    });
                </script>

                <?php
            } catch(PDOException $Insert_Logs) {
                ?>

                <div class="text-danger">
                    <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                    <h4><strong>INSERT_LOGS</strong></h4>

                    <p><?= $Insert_Logs->getMessage(); ?>.</p>
                </div>

                <?php
            }
        }

        $Anirepo = null;
    ?>
</div>