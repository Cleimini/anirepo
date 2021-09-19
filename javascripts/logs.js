$(function() {
    $("#Logs_Link").addClass("Active-Link");

    $("#Download_Logs").click(function() {
        var Download_Logs = $(this).prop("disabled", true).val();

        $("#Download_Logs_Message").addClass("mt-3").html("<p class='text-info'><i class='fas fa-hand-paper'></i> Wait for a moment.</p>").load("functions/logs/download-logs.php", {
            Download_Logs: Download_Logs
        });
    });

    $("#Date_Cataloged_Keywords, #Sort_Log_Type, #Sort_Logs, #Limit_Logs").on("keypress change", function() {
        var Date_Cataloged_Keywords = $("#Date_Cataloged_Keywords").val(),
            Sort_Log_Type = $("#Sort_Log_Type").val(),
            Sort_Logs = $("#Sort_Logs").val(),
            Limit_Logs = $("#Limit_Logs").val();

        $("#Logs_Placeholder").load("functions/logs/search-logs.php", {
            Date_Cataloged_Keywords: Date_Cataloged_Keywords,
            Sort_Log_Type: Sort_Log_Type,
            Sort_Logs: Sort_Logs,
            Limit_Logs: Limit_Logs
        });
    });
});