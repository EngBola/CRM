
$(function(){
    'use strict';
 
    //Hide Placeholder on form focus
    $('[placeholder]').focus(function(){
        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    }).blur(function(){
        $(this).attr('placeholder', $(this).attr('data-text'));
    });

//// show password clickable//////

var passfield = $('.password');
$('.showpass').hover(function () {
    passfield .attr('type', 'text');
}, function() {
    passfield.attr('type', 'password');

});
$('.confirm').click(() => confirm('Are You Sure?'));

setTimeout(function() {
    $('.alert-success').fadeOut('fast');
}, 1000);

$('.card-header h5').click(function(){
    $(this).next('.viewAll').fadeToggle(500);
});
$('.viewInfo').click(function(){
    $(this).next('.viewAll').fadeToggle(500);
});
$('.view-option span').click(function(){
    $(this).addClass('active').siblings('span').removeClass('active');
    if($(this).data('view')=== 'full'){
        $('.viewAll').fadeIn(200);
    }else{
        $('.viewAll').fadeOut(200);
    }
});
   // SelectBox
   $('select').selectBoxIt({
       autoWidth: false
   });
});



