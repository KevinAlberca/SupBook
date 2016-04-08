$(function(){

    $(".reply-to").click(function(){
        console.log($(this).val());
        $(".comments-"+$(this).val()).css({
            "display":"block"
        });

        $(".comments-"+$(this).val()).css({
            "display":"block"
        });

    })



});