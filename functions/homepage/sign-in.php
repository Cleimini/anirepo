<script>
    $(function() {
        $("#Sign_In").prop("disabled", false);
    });
</script>

<?php
    include_once "../database-connection.php";

    if (!isset($_POST["Sign_In"]) || !isset($_POST["Email_Address"]) || !isset($_POST["Password"]) || isset($_SESSION["User_ID"])) {
        ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Unable to sign in, refresh the page.</p> <?php
    } else {
        $Email_Address = trim($_POST["Email_Address"]);
        $Password = trim($_POST["Password"]);

        try {
            $Select_Users = $Anirepo->prepare("SELECT * FROM users WHERE Email_Address = :Email_Address");
            $Select_Users->bindParam(":Email_Address", $Email_Address);
            $Select_Users->execute();
            $User = $Select_Users->fetch();

            if (empty($Email_Address) || empty($Password)) {
                ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Enter your email address and password.</p> <?php
            } else if ($Select_Users->rowCount() < 1 || !password_verify($Password, $User["Password"])) {
                ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Invalid email address or password.</p> <?php
            } else {
                try {
                    $_SESSION["User_ID"] = $User["User_ID"];

                    $Insert_Logs = $Anirepo->prepare("INSERT INTO logs VALUES ('', :User_ID, 'Sign In', NOW())");
                    $Insert_Logs->bindParam(":User_ID", $_SESSION["User_ID"]);
                    $Insert_Logs->execute();

                    ?>

                    <p class="text-success"><i class="fas fa-check-circle"></i> Signing in, wait for a moment.</p>

                    <script>
                        $(function() {
                            $("#Sign_In").prop("disabled", true);

                            $("#Sign_In_Form input").val("");

                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        });
                    </script>

                    <?php
                } catch(PDOException $Insert_Logs) {
                    ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Insert_Logs:</b> <?= $Insert_Logs->getMessage(); ?>.</p> <?php
                }
            }
        } catch(PDOException $Select_Users) {
            ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Select_Users:</b> <?= $Select_Users->getMessage(); ?>.</p> <?php
        }
    }

    $Anirepo = null;
?>