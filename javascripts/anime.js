$(function() {
    $("#Anime_Link").addClass("Active-Link");

    $("#Show_Anime_Enlister").click(function() {
        var Show_Anime_Enlister = $(this).val();

        $(".modal-content").html("").load("components/anime/anime-enlister.php", {
            Show_Anime_Enlister: Show_Anime_Enlister
        });
    });

    $("#Show_Anime_Importer").click(function() {
        var Show_Anime_Importer = $(this).val();

        $(".modal-content").html("").load("components/anime/anime-importer.php", {
            Show_Anime_Importer: Show_Anime_Importer
        });
    });

    $("#Anime_Title_Keywords, #Sort_Anime_Type, #Sort_Premiered, #Sort_Source, #Sort_Monitoring_Type, #Sort_Anime, #Limit_Anime").on("keyup change", function() {
        var Anime_Title_Keywords = $("#Anime_Title_Keywords").val(),
            Sort_Anime_Type = $("#Sort_Anime_Type").val(),
            Sort_Premiered = $("#Sort_Premiered").val(),
            Sort_Source = $("#Sort_Source").val(),
            Sort_Monitoring_Type = $("#Sort_Monitoring_Type").val(),
            Sort_Anime = $("#Sort_Anime").val(),
            Limit_Anime = $("#Limit_Anime").val();

        $("#Anime_List_Placeholder").load("functions/anime/search-anime.php", {
            Anime_Title_Keywords: Anime_Title_Keywords,
            Sort_Anime_Type: Sort_Anime_Type,
            Sort_Premiered: Sort_Premiered,
            Sort_Source: Sort_Source,
            Sort_Monitoring_Type: Sort_Monitoring_Type,
            Sort_Anime: Sort_Anime,
            Limit_Anime: Limit_Anime
        });
    });
});