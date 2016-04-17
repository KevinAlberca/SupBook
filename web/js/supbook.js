$(function(){
    $(".button-collapse").sideNav();


    $(".reply-to").click(function(){
        $(".comment").each(function(){
            $(this).css({
                "display":"none"
            });
        });

        $("#comment-" + $(this).val()).css({
            "display":"block"
        });
    })
});