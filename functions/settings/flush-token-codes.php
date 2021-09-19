<?php
    include_once "../database-connection.php";

    if (!isset($_POST["Flush_Token_Codes"]) || !isset($_SESSION["User_ID"]) || $_SESSION["User_ID"] != 1) {
        ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Unable to flush token codes, refresh the page.</p> <?php
    } else {
        try {
            $Delete_Resets = $Anirepo->prepare("DELETE FROM resets WHERE Date_Token_Code_Expires <= DATE_ADD(NOW(), INTERVAL 5 MINUTE)");
            $Delete_Resets->execute();

            ?>
            
            <p class="text-success"><i class="fas fa-times-circle"></i> Token codes has been flushed.</p>

            <script>
                $(function() {
                    $("#Expired_Token_Codes_Placeholder").html("<div class='text-info user-select-none'><h1 class='Large-Font-Size'><i class='fas fa-thumbs-up'></i></h1><h4><strong>NO EXPIRED TOKEN CODES FOUND</strong></h4></div>");
                });
            </script>
            
            <?php
        } catch(PDOException $Delete_Resets) {
            ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Delete_Resets:</b> <?= $Delete_Resets->getMessage(); ?>.</p> <?php
        }
    }

    $Anirepo = null;
?>

<script>
    $(function() {
        $("#Flush_Token_Codes").prop("disabled", false);
    });
</script>