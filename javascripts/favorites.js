$(function() {
    $("#Favorites_Link").addClass("Active-Link");

    $("#Show_Favorites_Enlister").click(function() {
        var Show_Favorites_Enlister = $(this).val();

        $(".modal-content").html("").load("components/favorites/favorites-enlister.php", {
            Show_Favorites_Enlister: Show_Favorites_Enlister
        });
    });

    $("#Show_Favorites_Importer").click(function() {
        var Show_Favorites_Importer = $(this).val();

        $(".modal-content").html("").load("components/favorites/favorites-importer.php", {
            Show_Favorites_Importer: Show_Favorites_Importer
        });
    });

    $("#Favorites_Keywords, #Sort_Favorite_Type, #Sort_Favorites, #Limit_Favorites").on("keyup change", function() {
        var Favorites_Keywords = $("#Favorites_Keywords").val(),
            Sort_Favorite_Type = $("#Sort_Favorite_Type").val(),
            Sort_Favorites = $("#Sort_Favorites").val(),
            Limit_Favorites = $("#Limit_Favorites").val();

        $("#Favorites_Placeholder").load("functions/favorites/search-favorites.php", {
            Favorites_Keywords: Favorites_Keywords,
            Sort_Favorite_Type: Sort_Favorite_Type,
            Sort_Favorites: Sort_Favorites,
            Limit_Favorites: Limit_Favorites
        });
    });

    $("#Refresh_Favorites").click(function() {
        var Refresh_Favorites = $(this).val();

        $("#Favorites_Placeholder").load("functions/favorites/refresh-favorites.php", {
            Refresh_Favorites: Refresh_Favorites
        });
    });
});