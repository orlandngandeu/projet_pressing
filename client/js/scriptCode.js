$(document).ready(function(){
    
    
    $(function (){
    
      $('#forgot-form').submit(function(e){
    
        e.preventDefault(); // Empêche le rechargement de la page
        $('.comments').empty();
        let postdata = $('#forgot-form').serialize();
    
        $.ajax({
          type: 'POST',
          url: '../../../server/php/scriptCode.php',
          data: postdata,
          dataType: 'json',
          success: function(result) {
                 
            if(result.ensuccess){
              $("#forgot-form")[0].reset();
                Swal.fire('Le code a été envoyé avec succès').then(function() {
                window.location.href = 'codecontrol.php';
              })
            }
            else
            {
              $("#mailForgot + .comments").html(result.mailForgotError);
              $("#phoneForgot + .comments").html(result.phoneForgotError);
    
            }
    
          }
    
        })
    
    
      });
    
})
    
})
    
    
    