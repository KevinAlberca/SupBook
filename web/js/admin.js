/**
 * Created by AwH on 03/06/16.
 */

$(function(){

    var sidebar = $('#sidebar');
    var container = $('.main');

    $('#toggle-sidebar').click(function(){
        if(sidebar.hasClass('collapsed')){
            sidebar.removeClass('collapsed', 400);
        } else {
            sidebar.addClass('collapsed', 400);
        }
    });
})