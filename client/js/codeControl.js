$(document).ready(function(){

    $(function (){

        $('#code-form').submit(function(e){
    
          e.preventDefault();
          $('.comments').empty();
          let postdata = $('#code-form').serialize();
    
          $.ajax({
            type: 'POST',
            url: '../../../server/php/verif.php',
            data: postdata,
            dataType: 'json',
            success: function(result) {
                 
              if(result.ensuccess){
                $("#code-form")[0].reset();
                window.location.href = 'resetpassword.php';
              }
              else{
                $("#codeVerif + .comments").html(result.codeverifError);
              }
            }
          })
        });
    
    })

})