<script>
    $(function() {
        $("#Sign_Up").prop("disabled", false);
    });
</script>

<?php
    include_once "../database-connection.php";

    if (!isset($_POST["Sign_Up"]) || !isset($_POST["Email_Address"]) || !isset($_POST["Password"]) || !isset($_POST["Repeat_Password"]) || isset($_SESSION["User_ID"])) {
        $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Unable to sign up, refresh the page.</p> <?php
    } else {
        $Email_Address = trim(filter_var($_POST["Email_Address"], FILTER_SANITIZE_EMAIL));
        $Password = trim($_POST["Password"]);
        $Repeat_Password = trim($_POST["Repeat_Password"]);

        try {
            $Select_Users = $Anirepo->prepare("SELECT * FROM users WHERE Email_Address = :Email_Address");
            $Select_Users->bindParam(":Email_Address", $Email_Address);
            $Select_Users->execute();

            if (empty($Email_Address)) {
                $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Email address is required.</p> <?php
            } else if (!filter_var($Email_Address, FILTER_VALIDATE_EMAIL)) {
                $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Invalid email address format.</p> <?php
            } else if ($Select_Users->rowCount() > 0) {
                $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Email address is already in use.</p> <?php
            }
    
            if (empty($Password) || empty($Repeat_Password)) {
                $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Fill up both passwords.</p> <?php
            } else if ($Password != $Repeat_Password) {
                $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Both passwords do not match.</p> <?php
            }

            if ($Error < 1) {
                try {
                    $Password = password_hash($Password, PASSWORD_ARGON2ID);

                    $Insert_Users = $Anirepo->prepare("INSERT INTO users VALUES ('', :Email_Address, :Password)");
                    $Insert_Users->bindParam(":Email_Address", $Email_Address);
                    $Insert_Users->bindParam(":Password", $Password);
                    $Insert_Users->execute();

                    $_SESSION["User_ID"] = $Anirepo->lastInsertId();

                    $Insert_Logs = $Anirepo->prepare("INSERT INTO logs VALUES ('', :User_ID, 'Sign Up', NOW())");
                    $Insert_Logs->bindParam(":User_ID", $_SESSION["User_ID"]);
                    $Insert_Logs->execute();

                    ?>

                    <p class="text-success"><i class="fas fa-check-circle"></i> Signing up, wait for a moment.</p>

                    <script>
                        $(function() {
                            $("#Sign_Up").prop("disabled", true);

                            $("#Sign_Up_Form input").val("");

                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        });
                    </script>

                    <?php
                } catch(PDOException $Insert_Users_Logs) {
                    $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Insert_Users_Logs:</b> <?= $Insert_Users_Logs->getMessage(); ?>.</p> <?php
                }
            }
        } catch(PDOException $Select_Users) {
            $Error = 1; ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Select_Users:</b> <?= $Select_Users->getMessage(); ?>.</p> <?php
        }
    }

    $Anirepo = null;
?>