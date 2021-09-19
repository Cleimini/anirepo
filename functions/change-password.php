<?php
    include_once "database-connection.php";

    if (!isset($_POST["Change_Password"]) || !isset($_POST["Selector"]) || !isset($_POST["Token_Code"]) || !isset($_POST["New_Password"]) || !isset($_POST["Repeat_New_Password"]) || isset($_SESSION["User_ID"])) {
        ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Unable to change password, refresh the page.</p> <?php
    } else {
        $Selector = trim($_POST["Selector"]);
        $Token_Code = trim(hex2bin($_POST["Token_Code"]));
        $New_Password = trim($_POST["New_Password"]);
        $Repeat_New_Password = trim($_POST["Repeat_New_Password"]);
        $Delete_Resets = false;

        try {
            $Select_Resets = $Anirepo->prepare("SELECT * FROM resets WHERE Selector = :Selector");
            $Select_Resets->bindParam(":Selector", $Selector);
            $Select_Resets->execute();
            $Reset = $Select_Resets->fetch();

            if (empty($New_Password) || empty($Repeat_New_Password)) {
                ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Fill up both password.</p> <?php
            } else if ($New_Password != $Repeat_New_Password) {
                ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Passwords do not match.</p> <?php
            } else if ($Select_Resets->rowCount() < 1) {
                $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Request not found, redirecting you back to the homepage.</p> <?php
            } else if (!password_verify($Token_Code, $Reset["Token_Code"])) {
                $Error = 1;
                $Delete_Resets = true;

                ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Invalid credentials, redirecting you back to the homepage.</p> <?php
            } else if ($Reset["Date_Token_Code_Expires"] <= date("Y-m-d h:i:s")) {
                $Error = 1;
                $Delete_Resets = true;

                ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Request has been expired, redirecting you back to the homepage.</p> <?php
            } else {
                try {
                    $New_Password = password_hash($New_Password, PASSWORD_ARGON2ID);
                    $Delete_Resets = true;

                    $Update_Users = $Anirepo->prepare("UPDATE users SET Password = :Password WHERE User_ID = :User_ID");
                    $Update_Users->bindParam(":User_ID", $Reset["User_ID"]);
                    $Update_Users->bindParam(":Password", $New_Password);
                    $Update_Users->execute();

                    $Insert_Logs = $Anirepo->prepare("INSERT INTO logs VALUES ('', :User_ID, 'Change Password', NOW())");
                    $Insert_Logs->bindParam(":User_ID", $Reset["User_ID"]);
                    $Insert_Logs->execute();

                    ?>

                    <p class="text-success"><i class="fas fa-check-circle"></i> Password has been changed, redirecting you back to the homepage.</p>

                    <script>
                        $(function() {
                            $("#Change_Password_Form input").val("");

                            setTimeout(() => {
                                window.location.href = "index.php";
                            }, 1000);
                        });
                    </script>

                    <?php
                } catch(PDOException $Update_Users_Insert_Logs) {
                    ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Update_Users_Insert_Logs:</b> <?= $Update_Users_Insert_Logs->getMessage(); ?>.</p> <?php
                }
            }

            if ($Delete_Resets) {
                try {
                    $Delete_Resets = $Anirepo->prepare("DELETE FROM resets WHERE User_ID = :User_ID");
                    $Delete_Resets->bindParam(":User_ID", $Reset["User_ID"]);
                    $Delete_Resets->execute();
                } catch(PDOException $Delete_Resets) {
                    ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Delete_Resets:</b> <?= $Delete_Resets->getMessage(); ?>.</p> <?php
                }
            }
        } catch(PDOException $Select_Resets) {
            ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Select_Resets:</b> <?= $Select_Resets->getMessage(); ?>.</p> <?php
        }
    }

    $Anirepo = null;
?>

<script>
    $(function() {
        $("#Change_Password").prop("disabled", false);

        <?php
            if ($Error > 0) {
                ?>

                setTimeout(() => {
                    window.location.href = "index.php";
                }, 1000);

                <?php
            }
        ?>
    });
</script>