<?php require_once "../../functions/database-connection.php"; ?>

<div class="modal-header text-white">
    <h1><strong>FAVORITES IMPORTER</strong></h1>
    <h2 data-bs-dismiss="modal"><i class="fas fa-times-circle"></i></h2>
</div>

<div class="modal-body text-dark">
    <?php
        if (!isset($_POST["Show_Favorites_Importer"]) || !isset($_SESSION["User_ID"])) {
            echo $Modal_Error_Box;
        } else {
            ?>

            <div class="mb-4 text-center">
                <h4><strong>CSV FILE FORMAT</strong></h4>

                <a class="bg-dark btn btn-sm mt-2 p-3 rounded text-decoration-none text-white" href="downloads/favorites.xlsx" download="Favorite Import File Format"><b>DOWNLOAD FILE</b> <i class="fas fa-download"></i></a>
            </div>

            <form action="functions/favorites/import-favorites.php" enctype="multipart/form-data" id="Import_Favorites_Form" method="POST">
                <label for="CSV_File">CSV File:</label>

                <div class="form-group">
                    <input accept=".csv" class="form-control form-control-lg w-100" id="CSV_File" name="CSV_File" type="file">
                </div>

                <button class="bg-primary btn btn-lg d-block mt-4 mx-auto px-5 py-3 text-white" id="Import_Favorites" type="submit"><b> IMPORT FAVORITES</b> <i class="fas fa-file-csv"></i></button>
            </form>

            <div class="mt-3" id="Import_Favorites_Message"></div>

            <script>
                $(function() {
                    $("#Import_Favorites").click(function(No_Refresh) {
                        No_Refresh.preventDefault();

                        var Form_Data = new FormData($("#Import_Favorites_Form")[0]);

                        Form_Data.append("Import_Favorites", $(this).prop("disabled", true).val());
                        Form_Data.append("CSV_File", $("#CSV_File").val());

                        $("#Import_Favorites_Message").html("<?= $Waiting_Message; ?>");

                        $.ajax({
                            cache: false,
                            contentType: false,
                            data: Form_Data,
                            enctype: "multipart/form-data",
                            method: "POST",
                            processData: false,
                            url: "functions/favorites/import-favorites.php",

                            success: function(Import_Favorites_Message) {
                                $("#Import_Favorites_Message").html(Import_Favorites_Message);
                            },

                            error: function() {
                                $("#Import_Favorites_Message").html("<p class='text-danger'><i class='fas fa-times-circle'></i> Forbidden access. File not found.</p>");

                                $("#Import_Favorites").prop("disabled", false);

                                $("#CSV_File").val("");
                            }
                        });
                    });
                });
            </script>

            <?php
        }
    ?>
</div>