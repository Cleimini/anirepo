<?php
    include_once "../database-connection.php";

    if (!isset($_POST["Update_Account"]) || !isset($_POST["Email_Address"]) || !isset($_POST["New_Password"]) || !isset($_POST["Repeat_New_Password"]) || !isset($_SESSION["User_ID"])) {
        $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Unable to update account, refresh the page.</p> <?php
    } else {
        $Email_Address = trim(filter_var($_POST["Email_Address"], FILTER_SANITIZE_EMAIL));
        $New_Password = trim($_POST["New_Password"]);
        $Repeat_New_Password = trim($_POST["Repeat_New_Password"]);

        try {
            $Select_Users = $Anirepo->prepare("SELECT * FROM users WHERE User_ID = :User_ID");
            $Select_Users->bindParam(":User_ID", $_SESSION["User_ID"]);
            $Select_Users->execute();
            $User = $Select_Users->fetch();

            $Check_Email_Address = $Anirepo->prepare("SELECT * FROM users WHERE Email_Address = :Email_Address");
            $Check_Email_Address->bindParam(":Email_Address", $Email_Address);
            $Check_Email_Address->execute();

            if ($Select_Users->rowCount() < 1) {
                $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> User not found, refresh the page.</p> <?php
            } else {
                if (empty($Email_Address)) {
                    $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Email address is required.</p> <?php
                } else if (!filter_var($Email_Address, FILTER_VALIDATE_EMAIL)) {
                    $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Invalid email address format.</p> <?php
                } else if ($Email_Address != $User["Email_Address"] && $Check_Email_Address->rowCount() > 0) {
                    $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Email address is already in use.</p> <?php
                }
        
                if (!empty($Password) && $Password != $Repeat_Password) {
                    $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Both passwords do not match.</p> <?php
                }

                if ($Error < 1) {
                    try {
                        if (!empty($New_Password)) {
                            $New_Password = password_hash($New_Password, PASSWORD_ARGON2ID);
                        } else {
                            $New_Password = $User["Password"];
                        }

                        $Update_Users = $Anirepo->prepare("UPDATE users SET Email_Address = :Email_Address, Password = :Password WHERE User_ID = :User_ID");
                        $Update_Users->bindParam(":User_ID", $_SESSION["User_ID"]);
                        $Update_Users->bindParam(":Email_Address", $Email_Address);
                        $Update_Users->bindParam(":Password", $New_Password);
                        $Update_Users->execute();

                        $Insert_Logs = $Anirepo->prepare("INSERT INTO logs VALUES ('', :User_ID, 'Update Account', NOW())");
                        $Insert_Logs->bindParam(":User_ID", $_SESSION["User_ID"]);
                        $Insert_Logs->execute();

                        ?>

                        <p class="text-success"><i class="fas fa-check-circle"></i> Account has been updated.</p>

                        <script>
                            $(function() {
                                $("#New_Password, #Repeat_New_Password").val("");
                            });
                        </script>

                        <?php
                    } catch(PDOException $Update_Users_Insert_Logs) {
                        $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Update_Users_Insert_Logs:</b> <?= $Update_Users_Insert_Logs->getMessage(); ?>.</p> <?php
                    }
                }
            }
        } catch(PDOException $Select_Users) {
            $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Select_Users:</b> <?= $Select_Users->getMessage(); ?>.</p> <?php
        }
    }

    $Anirepo = null;
?>

<script>
    $(function() {
        $("#Update_Account").prop("disabled", false);
    });
</script>