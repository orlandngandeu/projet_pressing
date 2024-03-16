$(document).ready(function(){

    $(function (){

        $('#reset-form').submit(function(e){
    
          e.preventDefault();   //empeche le chargement de la page...
          $('.comments').empty();
          let postdata = $('#reset-form').serialize();
    
          $.ajax({
            type: 'POST',
            url: '../../../server/php/resetps.php',
            data: postdata,
            dataType: 'json',
            success: function(result) {
                 
              if(result.ensuccess){
                $("#reset-form")[0].reset();
                Swal.fire({
                    icon: 'success',
                    title: 'Mot de passe modifié avec succès',
                })
              }
              else{
                $("#newpasswd + .comments").html(result.newpasswdError);
                $("#confpasswd + .comments").html(result.confpasswdError);
              }
            }
          })
        });
    
    })

})