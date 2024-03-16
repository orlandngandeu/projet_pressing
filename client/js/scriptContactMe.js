$(function (){

    $('#contact-form').submit(function(e){

      e.preventDefault();
      $('.comments').empty();
      let postdata = $('#contact-form').serialize();

      $.ajax({
        type: 'POST',
        url: '../../../server/php/trait_contactME.php',
        data: postdata,
        dataType: 'json',
        success: function(result) {
             
            if(result.ensuccess){
                $("#contact-form").append("<p class='merci'>Votre message a bien été envoyé. Merci de m'avoir contacté ...</p>");
                $("#contact-form")[0].reset();

            }
            else
            {
                $("#firstname + .comments").html(result.firstnameError);
                $("#name + .comments").html(result.nameError);
                $("#email + .comments").html(result.emailError);
                $("#phone + .comments").html(result.phoneError);
                $("#message + .comments").html(result.messageError);
                
            }
        }
      })
    });

})