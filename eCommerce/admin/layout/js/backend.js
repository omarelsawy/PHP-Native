$(function () {
   'use strict';

   //dashboard
    $('.toggle-info').click(function () {
       $(this).toggleClass('selected');
       if ($(this).hasClass('selected')){
           $(this).parent().next('.panel-body').hide();
           $(this).html('<i class="fa fa-minus fa-lg"></i>');
       }else {
           $(this).html('<i class="fa fa-plus fa-lg"></i>');
           $(this).parent().next('.panel-body').show();
       }
    });

   //hide placeholder on form focus
    $('[placeholder]').focus(function () {
       $(this).attr('data-text' , $(this).attr('placeholder'));
       $(this).attr('placeholder' , '');
    }).blur(function () {
       $(this).attr('placeholder' , $(this).attr('data-text'));
    });

    //add astrin on required field
    $('input').each(function () {
        if ($(this).attr('required') === 'required'){
            $(this).after('<span class="astric">*</span>');
        }
    });

    //convert passwored field to text field on hover
    var passfield = $('.password');
    $('.show-pass').hover(function () {
        passfield.attr('type' , 'text');
    } , function () {
        passfield.attr('type' , 'password');
    });

    //confirm message on button
    $('.confirm').click(function () {
       return confirm('Are you sure');
    });

    //category view option
    $('.option span').click(function () {
       $(this).addClass('active').siblings('span').removeClass('active');
       if ($(this).data('view') === 'full'){
           $('.view2').removeClass('view2');
       }else {
           $('.classic').addClass('view2');
       }
    });

    /*trigger the selectboxit
    $("select").selectBoxIt();*/

});



















