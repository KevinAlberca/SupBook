$(function(){

    var sidebar = $('#sidebar');
    var container = $('.container');

    $('#toggle-sidebar').click(function(){
        if(sidebar.hasClass('expanded')){
            sidebar.removeClass('expanded', 400);
            //container.css({
            //  "margin-left": "auto",
            // "margin-right": "auto"
            // });
        } else {
            sidebar.addClass('expanded', 400);
            //container.css({
            //  "margin-left": "auto",
            //  "margin-right": "auto"
            // });
        }
    });
});