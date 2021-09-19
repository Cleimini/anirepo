<?php
    include_once "../database-connection.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require_once "../../vendor/autoload.php";

    if (!isset($_POST["Send_Request"]) || !isset($_POST["Email_Address"]) || isset($_SESSION["User_ID"])) {
        ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Unable to send request, refresh the page.</p> <?php
    } else {
        $Email_Address = trim($_POST["Email_Address"]);
        $Selector = bin2hex(random_bytes(8));
        $Token_Code = random_bytes(32);
        $URL = "http://localhost/anirepo/change-password-form.php?Selector=" . $Selector . "&Token_Code=" . bin2hex($Token_Code);

        try {
            $Select_Users = $Anirepo->prepare("SELECT * FROM users WHERE Email_Address = :Email_Address");
            $Select_Users->bindParam(":Email_Address", $Email_Address);
            $Select_Users->execute();
            $User = $Select_Users->fetch();

            if (empty($Email_Address)) {
                ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Email address is required.</p> <?php
            } else if ($Select_Users->rowCount() < 1) {
                ?> <p class="text-danger"><i class="fas fa-times-circle"></i> Email address does not exist.</p> <?php
            } else {
                try {
                    $Mail = new PHPMailer(true);
                    $Mail->isSMTP();
                    $Mail->Host = "smtp.gmail.com";
                    $Mail->SMTPAuth = true;
                    $Mail->Username = "cleinwepee@gmail.com";
                    $Mail->Password = "qrfdcumklpkpxomr";
                    $Mail->SMTPSecure = PHPMAILER::ENCRYPTION_STARTTLS;
                    $Mail->Port = 587;
                    $Mail->setFrom("cleinwepee@gmail.com", "AniRepo");
                    $Mail->addAddress("$Email_Address");
                    $Mail->isHTML(true);
                    $Mail->Subject = "AniRepo: Forgot Password";
                    $Mail->Body = "<p>We received your request for your forgotten password and has provided you a link for you to change your password with a new one: <a href='" . $URL . "'><b>Change Password Form Link</b></a>. Note that the link expires after 5 minutes.</p>";

                    if ($Mail->send()) {
                        try {
                            $Token_Code = password_hash($Token_Code, PASSWORD_DEFAULT);

                            $Insert_Resets = $Anirepo->prepare("INSERT INTO resets VALUES (:User_ID, '$Selector', '$Token_Code', DATE_ADD(NOW(), INTERVAL 5 MINUTE))
                                ON DUPLICATE KEY UPDATE Selector = '$Selector', Token_Code = '$Token_Code', Date_Token_Code_Expires = DATE_ADD(NOW(), INTERVAL 5 MINUTE)");
                            $Insert_Resets->bindParam(":User_ID", $User["User_ID"]);
                            $Insert_Resets->execute();

                            $Insert_Logs = $Anirepo->prepare("INSERT INTO logs VALUES ('', :User_ID, 'Send Request', NOW())");
                            $Insert_Logs->bindParam(":User_ID", $User["User_ID"]);
                            $Insert_Logs->execute();

                            ?>

                            <p class="text-success"><i class="fas fa-check-circle"></i> Request has been sent.</p>

                            <script>
                                $(function() {
                                    $("#Send_Request_Form input").val("");
                                });
                            </script>

                            <?php
                        } catch(PDOException $Insert_Resets_Logs) {
                            ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Insert_Resets_Logs:</b> <?= $Insert_Resets_Logs->getMessage(); ?>.</p> <?php
                        }
                    }
                } catch(Exception $Mail) {
                    ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Mail:</b> <?= $Mail->getMessage(); ?>.</p> <?php
                }
            }
        } catch(PDOException $Select_Users) {
            ?> <p class="text-danger"><i class="fas fa-times-circle"></i> <b>Select_Users:</b> <?= $Select_Users->getMessage(); ?>.</p> <?php
        }
    }

    $Anirepo = null;
?>

<script>
    $(function() {
        $("#Send_Request").prop("disabled", false);
    });
</script>