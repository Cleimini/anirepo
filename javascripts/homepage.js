$(function() {
    $("#Homepage_Text").hide();

    $("#Toggle_Sign_In_Form").click(function() {
        $("aside").toggleClass("Active-Sign-In-Form");
        $("main").toggleClass("Inactive-Homepage");
        $("#Sign_In_Text, #Homepage_Text").stop().toggle();
    });

    $("#Show_Password").change(function() {
        if ($(this).is(":checked")) {
            $("#Password").prop("type", "text");
        } else {
            $("#Password").prop("type", "password");
        }
    });

    $("#Sign_In").click(function(No_Refresh) {
        No_Refresh.preventDefault();

        var Sign_In = $(this).prop("disabled", true).val(),
            Email_Address = $("#Email_Address").val(),
            Password = $("#Password").val();

        $("#Sign_In_Message").html("<p class='text-info'><i class='fas fa-hand-paper'></i> Wait for a moment.</p>").load("functions/homepage/sign-in.php", {
            Sign_In: Sign_In,
            Email_Address: Email_Address,
            Password: Password
        });
    });

    $("#Show_Sign_Up_Form").click(function() {
        var Show_Sign_Up_Form = $(this).val();

        $(".modal-content").html("").load("components/homepage/sign-up-form.php", {
            Show_Sign_Up_Form: Show_Sign_Up_Form
        });
    });

    $("#Show_Forgot_Password_Form").click(function() {
        var Show_Forgot_Password_Form = $(this).val();

        $(".modal-content").html("").load("components/homepage/forgot-password-form.php", {
            Show_Forgot_Password_Form: Show_Forgot_Password_Form
        });
    });
});