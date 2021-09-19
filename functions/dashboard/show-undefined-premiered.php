<?php include_once "../database-connection.php"; ?>

<div class="modal-header text-white">
    <h1><strong>UNDEFINED PREMIERED LIST</strong></h1>
    <h2 data-bs-dismiss="modal"><i class="fas fa-times-circle"></i></h2>
</div>

<div class="modal-body text-dark">
    <?php
        if (!isset($_POST["Show_Undefined_Premiered"]) || !isset($_SESSION["User_ID"])) {
            echo $Modal_Error_Box;
        } else {
            try {
                $Select_Premiered = $Anirepo->prepare("SELECT * FROM anime WHERE User_ID = :User_ID AND Premiered = ''");
                $Select_Premiered->bindParam(":User_ID", $_SESSION["User_ID"]);
                $Select_Premiered->execute();
                $Premiered = $Select_Premiered->fetchAll();

                if ($Select_Premiered->rowCount() < 1) {
                    ?>

                    <div class="text-center text-danger user-select-none">
                        <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                        <h4><strong>NO ANIME FOUND ON YOUR REPOSITORY</strong></h4>

                        <p>Refresh the page.</p>
                    </div>

                    <?php
                } else {
                    foreach ($Premiered as $Premiered) {
                        ?> <p><a class="bg-dark border d-block mb-2 p-2 rounded text-decoration-none text-white" href="<?= $Premiered['Anime_MAL_URL']; ?>" target="_BLANK"><i class="fas fa-link"></i> <?= $Premiered["Anime_Title"]; ?></a></p> <?php
                    }
                }
            } catch(PDOException $Select_Premiered) {
                ?>

                <div class="text-center text-danger user-select-none">
                    <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                    <h4><strong>SELECT_PREMIERED</strong></h4>

                    <p><?= $Select_Premiered->getMessage(); ?>.</p>
                </div>

                <?php
            }
        }

        $Anirepo = null;
    ?>
</div>