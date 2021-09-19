<?php
    include_once "../database-connection.php";

    if (!isset($_POST["Trending_Type"]) || !isset($_SESSION["User_ID"])) {
        ?>

        <div class="text-center text-danger user-select-none">
            <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
            <h4><strong>UNABLE TO SWITCH TRENDINGS</strong></h4>

            <p>Refresh the page.</p>
        </div>

        <?php
    } else {
        $Trending_Type = trim($_POST["Trending_Type"]);

        if (!in_array($Trending_Type, array("Seasonal", "Airing", "Updated", "Upcoming", "Popular"))) {
            ?>

            <div class="text-center text-danger user-select-none">
                <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                <h4><strong>INVALID TRENDING TYPE VALUE</strong></h4>

                <p>Refresh the page.</p>
            </div>

            <?php
        } else {
            try {
                $Select_Trendings = $Anirepo->prepare("SELECT * FROM trendings WHERE Trending_Type = :Trending_Type");
                $Select_Trendings->bindParam(":Trending_Type", $Trending_Type);
                $Select_Trendings->execute();
                $Trendings = $Select_Trendings->fetchAll();
    
                if ($Select_Trendings->rowCount() < 1) {
                    ?>

                    <div class="text-center text-danger user-select-none">
                        <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                        <h4><strong>NO TRENDING ANIME FOUND</strong></h4>

                        <p>Refresh the page.</p>
                    </div>

                    <?php
                } else {
                    ?>

                    <div class="owl-carousel owl-theme">
                        <?php
                            foreach ($Trendings as $Trending) {
                                ?>

                                <a class="d-block item text-decoration-none text-white" href="<?= $Trending['Trending_MAL_URL']; ?>" target="_BLANK">
                                    <img alt="<?= $Trending['Trending_Title']; ?> Thumbnail" draggable="false" src="<?= $Trending['Trending_Thumbnail']; ?>">

                                    <p class="p-1 text-center"><?= $Trending["Trending_Title"]; ?></p>
                                </a>

                                <?php
                            }
                        ?>
                    </div>

                    <script src="javascripts/owl-carousel.js"></script>

                    <?php
                }
            } catch(PDOException $Select_Trendings) {
                ?>

                <div class="text-center text-danger user-select-none">
                    <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                    <h4><strong>SELECT_TRENDINGS</strong></h4>

                    <p><?= $Select_Trendings->getMessage(); ?>.</p>
                </div>

                <?php
            }
        }
    }

    $Anirepo = null;
?>