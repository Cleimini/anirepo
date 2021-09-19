$(function() {
    $("#Dashboard_Link").addClass("Active-Link");

    $("#Trending_Type").change(function() {
        var Trending_Type = $(this).val();

        $("#Trendings_Placeholder").load("functions/dashboard/switch-trendings.php", {
            Trending_Type: Trending_Type
        });
    });

    $("#Show_All_Studios").click(function() {
        var Show_All_Studios = $(this).val();

        $(".modal-content").html("").load("functions/dashboard/show-all-studios.php", {
            Show_All_Studios: Show_All_Studios
        });
    });

    $("#Year_Premiered").change(function() {
        var Year_Premiered = $(this).val();

        $("#Premiered_Placeholder").load("functions/dashboard/switch-premiered.php", {
            Year_Premiered: Year_Premiered
        });
    });

    $("#Show_Undefined_Premiered").click(function() {
        var Show_Undefined_Premiered = $(this).val();

        $(".modal-content").html("").load("functions/dashboard/show-undefined-premiered.php", {
            Show_Undefined_Premiered: Show_Undefined_Premiered
        });
    });
});