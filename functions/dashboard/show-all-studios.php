<?php include_once "../database-connection.php"; ?>

<div class="modal-header text-white">
    <h1><strong>STUDIO LIST</strong></h1>
    <h2 data-bs-dismiss="modal"><i class="fas fa-times-circle"></i></h2>
</div>

<div class="modal-body text-dark">
    <?php
        if (!isset($_POST["Show_All_Studios"]) || !isset($_SESSION["User_ID"])) {
            echo $Modal_Error_Box;
        } else {
            try {
                $Select_Studios = $Anirepo->prepare("SELECT DISTINCT Studios,
                    COUNT(*) AS Total_Per_Studios
                    FROM anime
                    WHERE User_ID = :User_ID
                    GROUP BY Studios
                    ORDER BY Total_Per_Studios DESC");
                $Select_Studios->bindParam(":User_ID", $_SESSION["User_ID"]);
                $Select_Studios->execute();
                $Studios = $Select_Studios->fetchAll();

                if ($Select_Studios->rowCount() < 1) {
                    ?>

                    <div class="text-center text-danger user-select-none">
                        <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                        <h4><strong>NO ANIME FOUND ON YOUR REPOSITORY</strong></h4>

                        <p>Refresh the page.</p>
                    </div>

                    <?php
                } else {
                    foreach ($Studios as $Studio) {
                        ?>

                        <div class="<?php if (!empty($Studio["Studios"])) { echo "bg-dark text-white"; } else { echo "bg-warning text-dark"; } ?> border mb-2 mx-auto p-2 rounded row">
                            <div class="col text-start">
                                <p><i class="fas fa-film"></i> <?php if (!empty($Studio["Studios"])) { echo $Studio["Studios"]; } else { echo "<strong>UNNAMED STUDIO</strong>"; } ?>:</p>
                            </div>

                            <div class="col text-end">
                                <p><?= number_format($Studio["Total_Per_Studios"]); ?></p>
                            </div>
                        </div>

                        <?php
                    }
                }
            } catch(PDOException $Select_Studios) {
                ?>

                <div class="text-center text-danger user-select-none">
                    <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                    <h4><strong>SELECT_STUDIOS</strong></h4>

                    <p><?= $Select_Studios->getMessage(); ?>.</p>
                </div>

                <?php
            }
        }

        $Anirepo = null;
    ?>
</div>