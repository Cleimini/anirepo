<?php require_once "../../functions/database-connection.php"; ?>

<div class="modal-header text-white">
    <h1><strong>FORGOT PASSWORD FORM</strong></h1>
    <h2 data-bs-dismiss="modal"><i class="fas fa-times-circle"></i></h2>
</div>

<div class="modal-body text-dark">
    <?php
        if (!isset($_POST["Show_Forgot_Password_Form"]) || isset($_SESSION["User_ID"])) {
            echo $Modal_Error_Box;
        } else {
            ?>

            <form action="functions/homepage/send-request.php" id="Send_Request_Form" method="POST">
                <label for="Forgot_Password_Email_Address">Email Address:</label>

                <div class="form-group position-relative">
                    <input class="form-control form-control-lg w-100" id="Forgot_Password_Email_Address" placeholder="e.g. johnsmith@hogwarts.edu" type="email">

                    <span class="position-absolute"><i class="fas fa-at"></i></span>
                </div>

                <button class="bg-primary btn btn-lg d-block mt-4 mx-auto px-5 py-3 text-white" id="Send_Request" type="submit"><b> SEND REQUEST</b> <i class="fas fa-paper-plane"></i></button>
            </form>

            <div class="mt-3" id="Send_Request_Message"></div>

            <script>
                $(function() {
                    $("#Send_Request").click(function(No_Refresh) {
                        No_Refresh.preventDefault();

                        var Send_Request = $(this).prop("disabled", true).val(),
                            Email_Address = $("#Forgot_Password_Email_Address").val();

                        $("#Send_Request_Message").html("<?= $Waiting_Message; ?>").load("functions/homepage/send-request.php", {
                            Send_Request: Send_Request,
                            Email_Address: Email_Address
                        });
                    });
                });
            </script>

            <?php
        }
    ?>
</div>