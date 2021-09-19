$(function() {
    $("#Settings_Link").addClass("Active-Link");

    $("#Update_Seasonals").click(function(No_Refresh) {
        No_Refresh.preventDefault();

        var Update_Seasonals = $(this).val();

        $("#Update_Seasonals, #Update_Airings, #Update_Episodes, #Update_Upcomings, #Update_Populars").prop("disabled", true);

        $("#Update_Trendings_Message").html("<p class='text-info'><i class='fas fa-hand-paper'></i> Wait for a moment.</p>").load("functions/settings/update-trendings.php", {
            Update_Seasonals: Update_Seasonals
        });
    });

    $("#Update_Airings").click(function(No_Refresh) {
        No_Refresh.preventDefault();

        var Update_Airings = $(this).val();

        $("#Update_Seasonals, #Update_Airings, #Update_Episodes, #Update_Upcomings, #Update_Populars").prop("disabled", true);

        $("#Update_Trendings_Message").html("<p class='text-info'><i class='fas fa-hand-paper'></i> Wait for a moment.</p>").load("functions/settings/update-trendings.php", {
            Update_Airings: Update_Airings
        });
    });

    $("#Update_Episodes").click(function(No_Refresh) {
        No_Refresh.preventDefault();

        var Update_Episodes = $(this).val();

        $("#Update_Seasonals, #Update_Airings, #Update_Episodes, #Update_Upcomings, #Update_Populars").prop("disabled", true);

        $("#Update_Trendings_Message").html("<p class='text-info'><i class='fas fa-hand-paper'></i> Wait for a moment.</p>").load("functions/settings/update-trendings.php", {
            Update_Episodes: Update_Episodes
        });
    });

    $("#Update_Upcomings").click(function(No_Refresh) {
        No_Refresh.preventDefault();

        var Update_Upcomings = $(this).val();

        $("#Update_Seasonals, #Update_Airings, #Update_Episodes, #Update_Upcomings, #Update_Populars").prop("disabled", true);

        $("#Update_Trendings_Message").html("<p class='text-info'><i class='fas fa-hand-paper'></i> Wait for a moment.</p>").load("functions/settings/update-trendings.php", {
            Update_Upcomings: Update_Upcomings
        });
    });

    $("#Update_Populars").click(function(No_Refresh) {
        No_Refresh.preventDefault();

        var Update_Populars = $(this).val();
        
        $("#Update_Seasonals, #Update_Airings, #Update_Episodes, #Update_Upcomings, #Update_Populars").prop("disabled", true);

        $("#Update_Trendings_Message").html("<p class='text-info'><i class='fas fa-hand-paper'></i> Wait for a moment.</p>").load("functions/settings/update-trendings.php", {
            Update_Populars: Update_Populars
        });
    });

    $("#Flush_Token_Codes").click(function(No_Refresh) {
        No_Refresh.preventDefault();

        var Flush_Token_Codes = $(this).val();

        $("#Flush_Token_Codes_Message").html("<p class='text-info'><i class='fas fa-hand-paper'></i> Wait for a moment.</p>").load("functions/settings/flush-token-codes.php", {
            Flush_Token_Codes: Flush_Token_Codes
        });
    });
});