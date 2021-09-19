<?php require_once "../../functions/database-connection.php"; ?>

<div class="modal-header text-white">
    <h1><strong>SIGN UP FORM</strong></h1>
    <h2 data-bs-dismiss="modal"><i class="fas fa-times-circle"></i></h2>
</div>

<div class="modal-body text-dark">
    <?php
        if (!isset($_POST["Show_Sign_Up_Form"]) || isset($_SESSION["User_ID"])) {
            echo $Modal_Error_Box;
        } else {
            ?>

            <form action="functions/homepage/sign-up.php" id="Sign_Up_Form" method="POST">
                <label for="Sign_Up_Email_Address">Email Address:</label>

                <div class="form-group position-relative">
                    <input class="form-control form-control-lg w-100" id="Sign_Up_Email_Address" placeholder="e.g. johnsmith@hogwarts.edu" type="email">

                    <span class="position-absolute"><i class="fas fa-at"></i></span>
                </div>

                <label class="mt-4" for="Sign_Up_Password">Password:</label>

                <div class="form-group position-relative">
                    <input class="form-control form-control-lg w-100" id="Sign_Up_Password" type="password">

                    <span class="position-absolute"><i class="fas fa-key"></i></span>
                </div>

                <label class="mt-4" for="Sign_Up_Repeat_Password">Repeat Password:</label>

                <div class="form-group position-relative">
                    <input class="form-control form-control-lg w-100" id="Sign_Up_Repeat_Password" type="password">

                    <span class="position-absolute"><i class="fas fa-redo"></i></span>
                </div>

                <div class="mt-2">
                    <input id="Show_Sign_Up_Password" type="checkbox">

                    <label for="Show_Sign_Up_Password">Show Password</label>
                </div>

                <button class="bg-primary btn btn-lg d-block mt-4 mx-auto px-5 py-3 text-white" id="Sign_Up" type="submit"><b>SIGN UP</b> <i class="fas fa-user-plus"></i></button>
            </form>

            <div class="mt-3" id="Sign_Up_Message"></div>

            <script>
                $(function() {
                    $("#Show_Sign_Up_Password").click(function() {
                        if ($(this).is(":checked")) {
                            $("#Sign_Up_Password, #Sign_Up_Repeat_Password").prop("type", "text");
                        } else {
                            $("#Sign_Up_Password, #Sign_Up_Repeat_Password").prop("type", "password");
                        }
                    });

                    $("#Sign_Up").click(function(No_Refresh) {
                        No_Refresh.preventDefault();

                        var Sign_Up = $(this).prop("disabled", true).val(),
                            Email_Address = $("#Sign_Up_Email_Address").val(),
                            Password = $("#Sign_Up_Password").val(),
                            Repeat_Password = $("#Sign_Up_Repeat_Password").val();

                        $("#Sign_Up_Message").html("<?= $Waiting_Message; ?>").load("functions/homepage/sign-up.php", {
                            Sign_Up: Sign_Up,
                            Email_Address: Email_Address,
                            Password: Password,
                            Repeat_Password: Repeat_Password
                        });
                    });
                });
            </script>

            <?php
        }
    ?>
</div>