$(function(){
    $(".button-collapse").sideNav();
    $('select').material_select();


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