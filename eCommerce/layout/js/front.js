$(function () {
   'use strict';

   //ajax for user sign up
    $("#username").keyup(function () {
     var name = $("#username").val();
     $.post('/signupajax.php' , {name:name} , function (match) {
           $('#ajax').html(match);
     });
    });

   //switch between login & signup
    $(document).ready(function () {
        $('.sign').hide();
    });

    $('.login-page h1 span').click(function () {
        $(this).addClass('selected').siblings().removeClass('selected');
        if ($(this).hasClass('selected') && $(this).hasClass('signup')){
           $('.frm').hide();
            $('.sign').show();
        }else {
           $('.sign').hide();
            $('.frm').show();
        }
    })

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

    //confirm message on button
    $('.confirm').click(function () {
       return confirm('Are you sure');
    });

    $('.live-name').keyup(function () {
       $('.live-prev .caption h3').text($(this).val());
    });

    $('.live-desc').keyup(function () {
        $('.live-prev .caption p').text($(this).val());
    });

    $('.live-price').keyup(function () {
        $('.live-prev span').text('$' + $(this).val());
    });

     /*$('.class').hover(function () {
        $(this).next('a').show();
     },function () {
        $(this).next('a').hide();
        $(this).find('a').hide();
     });*/

});



















