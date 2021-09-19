<?php
    include_once "database-connection.php";

    if (!isset($_POST["Anime_Title"]) || !isset($_SESSION["User_ID"])) {
        ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Unable to search anime titles, refresh the page.</p> <?php
    } else {
        $Anime_Title = trim($_POST["Anime_Title"]);
        $Wildcard_Anime_Title = "%" . $Anime_Title . "%";

        try {
            $Select_Anime = $Anirepo->prepare("SELECT * FROM anime WHERE User_ID = :User_ID AND Anime_Title LIKE :Anime_Title LIMIT 10");
            $Select_Anime->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Anime->bindParam(":Anime_Title", $Wildcard_Anime_Title);
            $Select_Anime->execute();
            $Anime = $Select_Anime->fetchAll();

            if ($Select_Anime->rowCount() < 1) {
                ?>

                <script>
                    $(function() {
                        $("#Anime_Title_Searches").html("").removeClass("mt-3");
                    });
                </script>

                <?php
            } else {
                ?> <h6 class="mb-2"><strong>SEARCH RESULTS (MUST CLICK THE ANIME TITLE):</strong></h6> <?php

                foreach ($Anime as $Anime) {
                    ?>
                    
                    <p id="Anime_<?= $Anime['Anime_ID']; ?>"><a href="javascript:void(0);"><?= $Anime["Anime_Title"]; ?></a></p>
                    
                    <script>
                        $(function() {
                            $("#Anime_<?= $Anime['Anime_ID']; ?>").click(function() {
                                $("#Selected_Anime_ID").val("<?= $Anime['Anime_ID']; ?>");
                                $("#Anime_Title").val("<?= $Anime['Anime_Title']; ?>");
                            });
                        });
                    </script>
                    
                    <?php
                }
            }
        } catch(PDOException $Select_Anime) {
            ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Select_Anime:</b> <?= $Select_Anime->getMessage(); ?>.</p> <?php
        }
    }

    $Anirepo = null;
?>