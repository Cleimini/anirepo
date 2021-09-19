<?php require_once "functions/database-connection.php"; ?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <?php include_once "components/head.html"; ?>

        <style>
            body {
                background-attachment: fixed;
                background-image: url("images/backgrounds/613d3f03732be_20210912074259.jpg");
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
            }

            section {
                background-color: var(--bs-purple);
                padding: 15px;
                width: 450px;
            }

            @media only screen and (max-width: 480px) {
                section {
                    padding: 5px;
                    width: 95%;
                }
            }
        </style>

        <title>AniRepo: Change Password Form</title>
    </head>

    <body>
        <?php
            if ($Disconnected) {
                header("Location: disconnected.php");
            } else if (isset($_SESSION["User_ID"]) || !isset($_GET["Selector"]) || !isset($_GET["Token_Code"]) || empty($_GET["Selector"]) || empty($_GET["Token_Code"]) || !ctype_xdigit($_GET["Selector"]) || !ctype_xdigit($_GET["Token_Code"])) {
                header("Location: index.php");
            } else {
                $Selector = trim($_GET["Selector"]);
                $Token_Code = trim($_GET["Token_Code"]);

                ?>

                <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="Bootstrap_Modal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header text-white">
                                <h1 class="text-center w-100"><strong>CHANGE PASSWORD FORM</strong></h1>
                            </div>

                            <div class="modal-body">
                                <form action="functions/change-password.php" id="Change_Password_Form" method="POST">
                                    <input id="Selector" type="hidden" value="<?= $Selector; ?>">
                                    <input id="Token_Code" type="hidden" value="<?= $Token_Code; ?>">

                                    <label for="New_Password">New Password:</label>

                                    <div class="form-group position-relative">
                                        <input class="form-control form-control-lg w-100" id="New_Password" type="password">

                                        <span class="position-absolute"><i class="fas fa-key"></i></span>
                                    </div>

                                    <label class="mt-4" for="Repeat_New_Password">Repeat New Password:</label>

                                    <div class="form-group position-relative">
                                        <input class="form-control form-control-lg w-100" id="Repeat_New_Password" type="password">

                                        <span class="position-absolute"><i class="fas fa-redo"></i></span>
                                    </div>

                                    <div class="mt-2">
                                        <input id="Show_New_Password" type="checkbox">

                                        <label for="Show_New_Password">Show Password</label>
                                    </div>

                                    <button class="bg-primary btn btn-lg d-block mt-4 mx-auto px-5 py-3 text-white" id="Change_Password" type="submit"><b> CHANGE PASSWORD</b> <i class="fas fa-edit"></i></button>
                                </form>

                                <div class="mt-3" id="Change_Password_Message"></div>

                                <script>
                                    $(function() {
                                        $("#Show_New_Password").click(function() {
                                            if ($(this).is(":checked")) {
                                                $("#New_Password, #Repeat_New_Password").prop("type", "text");
                                            } else {
                                                $("#New_Password, #Repeat_New_Password").prop("type", "password");
                                            }
                                        });

                                        $("#Change_Password").click(function(No_Refresh) {
                                            No_Refresh.preventDefault();

                                            var Change_Password = $(this).prop("disabled", true).val(),
                                                Selector = $("#Selector").val(),
                                                Token_Code = $("#Token_Code").val(),
                                                New_Password = $("#New_Password").val(),
                                                Repeat_New_Password = $("#Repeat_New_Password").val();

                                            $("#Change_Password_Message").html("<?= $Waiting_Message; ?>").load("functions/change-password.php", {
                                                Change_Password: Change_Password,
                                                Selector: Selector,
                                                Token_Code: Token_Code,
                                                New_Password: New_Password,
                                                Repeat_New_Password: Repeat_New_Password
                                            });
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    $(function() {
                        $("#Bootstrap_Modal").modal("show");
                    });
                </script>

                <?php
            }
        ?>
    </body>
</html>