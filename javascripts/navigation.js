$(function() {
    $("#Show_Navigation_Menu").click(function() {
        $(".Hamburger div:first-child").toggleClass("Cross-First-Burger");
        $(".Hamburger div:last-child").toggleClass("Cross-Last-Burger");
        $("aside").toggleClass("Show-Navigation-Menu");
        $("#Center_Burger").stop().toggle();
    });

    $("#Show_My_Account").click(function() {
        var Show_My_Account = $(this).val();

        $(".modal-content").html("").load("components/navigation/my-account.php", {
            Show_My_Account: Show_My_Account
        });
    });

    $("#Sign_Out").click(function() {
        var Sign_Out = $(this).val();

        $(".modal-content").html("").load("functions/navigation/sign-out.php", {
            Sign_Out: Sign_Out
        });
    });
});