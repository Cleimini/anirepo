$(function() {
    $("#Reviews_Link").addClass("Active-Link");

    $("#Show_Anime_Reviewer").click(function() {
        var Show_Anime_Reviewer = $(this).val();

        $(".modal-content").html("").load("components/reviews/anime-reviewer.php", {
            Show_Anime_Reviewer: Show_Anime_Reviewer
        });
    });

    $("#Anime_Title_Keywords, #Sort_Reviews, #Limit_Reviews").on("keyup change", function() {
        var Anime_Title_Keywords = $("#Anime_Title_Keywords").val(),
            Sort_Reviews = $("#Sort_Reviews").val(),
            Limit_Reviews = $("#Limit_Reviews").val(),
            Refresh_Reviews = true;

        $("#Reviews_Placeholder").load("functions/reviews/search-reviews.php", {
            Anime_Title_Keywords: Anime_Title_Keywords,
            Sort_Reviews: Sort_Reviews,
            Limit_Reviews: Limit_Reviews
        });
    });
});