<?php require_once "../../functions/database-connection.php"; ?>

<div class="modal-header text-white">
    <h1><strong>MY ACCOUNT</strong></h1>
    <h2 data-bs-dismiss="modal"><i class="fas fa-times-circle"></i></h2>
</div>

<div class="modal-body text-dark">
    <?php
        if (!isset($_POST["Show_My_Account"]) || !isset($_SESSION["User_ID"])) {
            echo $Modal_Error_Box;
        } else {
            try {
                $Select_Users = $Anirepo->prepare("SELECT * FROM users WHERE User_ID = :User_ID");
                $Select_Users->bindParam(":User_ID", $_SESSION["User_ID"]);
                $Select_Users->execute();
                $User = $Select_Users->fetch();

                $Select_Logs = $Anirepo->prepare("SELECT * FROM logs WHERE User_ID = :User_ID AND Log_Type = 'Update Account' ORDER BY Date_Cataloged DESC LIMIT 1");
                $Select_Logs->bindParam(":User_ID", $_SESSION["User_ID"]);
                $Select_Logs->execute();
                $Log = $Select_Logs->fetch();

                if ($Select_Users->rowCount() < 1) {
                    ?>

                    <div class="text-center text-danger user-select-none">
                        <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                        <h4><strong>USER NOT FOUND</strong></h4>

                        <p>Refresh the page.</p>
                    </div>

                    <?php
                } else {
                    if (!empty($Log["Date_Cataloged"])) {
                        ?> <h4 class="mb-5 text-center text-info"><strong><i class="fas fa-info-circle"></i> Last Modified: <?= date("M d, Y h:i:s A", strtotime($Log["Date_Cataloged"])) ?></strong></h4> <?php
                    }

                    ?>

                    <form id="My_Account_Form" method="POST">
                        <label for="Email_Address">Email Address:</label>

                        <div class="form-group position-relative">
                            <input class="form-control form-control-lg w-100" id="Email_Address" placeholder="e.g. johnsmith@hogwarts.edu" type="email" value="<?= $User['Email_Address']; ?>">

                            <span class="position-absolute"><i class="fas fa-at"></i></span>
                        </div>

                        <label class="mt-4" for="New_Password">New Password:</label>

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
                            <input id="Show_Password" type="checkbox">

                            <label for="Show_Password">Show Password</label>
                        </div>

                        <div class="mt-4 row">
                            <div class="col-md">
                                <button class="bg-primary btn btn-lg d-block mt-2 mx-auto px-5 py-3 text-white" id="Update_Account" type="submit"><b>UPDATE ACCOUNT</b> <i class="fas fa-edit"></i></button>
                            </div>

                            <div class="col-md">
                                <button class="bg-danger btn btn-lg d-block mt-2 mx-auto px-5 py-3 text-white" id="Delete_Account" type="submit"><b>DELETE ACCOUNT</b> <i class="fas fa-trash-alt"></i></button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-3" id="My_Account_Message"></div>

                    <script>
                        $(function() {
                            $("#Show_Password").click(function() {
                                if ($(this).is(":checked")) {
                                    $("#New_Password, #Repeat_New_Password").prop("type", "text");
                                } else {
                                    $("#New_Password, #Repeat_New_Password").prop("type", "password");
                                }
                            });

                            $("#Update_Account").click(function(No_Refresh) {
                                No_Refresh.preventDefault();

                                var Update_Account = $(this).prop("disabled", true).val(),
                                    Email_Address = $("#Email_Address").val(),
                                    New_Password = $("#New_Password").val(),
                                    Repeat_New_Password = $("#Repeat_New_Password").val();

                                $("#My_Account_Message").html("<?= $Waiting_Message; ?>").load("functions/navigation/update-account.php", {
                                    Update_Account: Update_Account,
                                    Email_Address: Email_Address,
                                    New_Password: New_Password,
                                    Repeat_New_Password: Repeat_New_Password
                                });
                            });

                            $("#Delete_Account").click(function(No_Refresh) {
                                No_Refresh.preventDefault();

                                var Delete_Account = $(this).prop("disabled", true).val();

                                if (confirm("Are you sure you wanted to delete your account?")) {
                                    $("#My_Account_Message").html("<?= $Waiting_Message; ?>").load("functions/navigation/delete-account.php", {
                                        Delete_Account: Delete_Account
                                    });
                                }
                            });
                        });
                    </script>

                    <?php
                }
            } catch(PDOException $Select_Users) {
                ?>

                <div class="text-center text-danger user-select-none">
                    <h1 class="Large-Font-Size"><i class="fas fa-exclamation-triangle"></i></h1>
                    <h4><strong>SELECT_USERS</strong></h4>

                    <p><?= $Select_Users->getMessage(); ?>.</p>
                </div>

                <?php
            }
        }
    ?>
</div>