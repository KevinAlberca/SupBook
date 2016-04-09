$(function(){
    $(".button-collapse").sideNav();

    $(".reply-to").click(function(){
        console.log($(this).val());

        $(".comment").css({
            "display":"none"
        });

        $(".comments-"+$(this).val()).css({
            "display":"block"
        });

    })
});