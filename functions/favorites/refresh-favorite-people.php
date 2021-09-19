<?php
    include_once "../database-connection.php";

    if (!isset($_POST["Refresh_Favorite_People"]) || $_POST["Refresh_Favorite_People"] == false || !isset($_SESSION["User_ID"])) {
        ?>

        <div class="bg-danger p-3 text-center text-white user-select-none">
            <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
            <h4><strong>UNABLE TO REFRESH THE FAVORITE PEOPLE LIST</strong></h4>

            <p>Refresh the page instead.</p>
        </div>

        <?php
    } else {
        try {
            $Select_Favorite_People = $Anirepo->prepare("SELECT * FROM favorites
                WHERE User_ID = :User_ID AND Favorite_Type = 'People'
                ORDER BY Placement ASC
                LIMIT 25");
            $Select_Favorite_People->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Favorite_People->execute();
            $Favorite_People = $Select_Favorite_People->fetchAll();

            if ($Select_Favorite_People->rowCount() > 0) {
                ?>

                <h4 class="text-center text-md-start"><strong>Favorite People (<?= number_format($Select_Favorite_People->rowCount()); ?>)</strong></h4>

                <p class="text-center text-md-start"><i>Showing <?= number_format($Select_Favorite_People->rowCount()); ?> Favorite People</i></p>

                <div class="mt-3 Grid-Container Grid-5">
                    <?php
                        foreach ($Favorite_People as $Favorite_People) {
                            ?>

                            <a class="d-block mx-auto text-center text-decoration-none" data-bs-target="#Bootstrap_Modal" data-bs-toggle="modal" href="javascript:void(0);" id="Show_Favorite_Editor_<?= $Favorite_People['Favorite_ID']; ?>">
                                <img alt="<?= $Favorite_People['Favorite_Name']; ?> Thumbnail" draggable="false" src="<?= $Favorite_People['Favorite_Thumbnail']; ?>">

                                <p class="bg-danger p-1 rounded-bottom text-white">(<b><span id="Placement_<?= $Favorite_People['Favorite_ID']; ?>"><?= $Favorite_People['Placement']; ?></span></b>) <?= $Favorite_People["Favorite_Name"]; ?></p>
                            </a>

                            <script>
                                $(function() {
                                    $("#Show_Favorite_Editor_<?= $Favorite_People['Favorite_ID']; ?>").click(function() {
                                        var Show_Favorite_Editor = $(this).val(),
                                            Favorite_ID = <?= $Favorite_People["Favorite_ID"]; ?>;

                                        $(".modal-content").html("").load("components/favorites/favorite-editor.php", {
                                            Show_Favorite_Editor: Show_Favorite_Editor,
                                            Favorite_ID: Favorite_ID
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
        } catch(PDOException $Select_Favorite_People) {
            ?>

            <div class="bg-danger p-3 text-center text-white user-select-none">
                <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                <h4><strong>SELECT_FAVORITE_PEOPLE</strong></h4>

                <p><?= $Select_Favorite_People->getMessage(); ?>.</p>
            </div>

            <?php
        }
    }

    $Anirepo = null;
?>