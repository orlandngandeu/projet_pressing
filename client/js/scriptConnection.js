$(document).ready(function(){

const passwordInput = document.getElementById('passwd');
const eyeIcon = document.getElementById('eye-icon');

eyeIcon.addEventListener('click', function() {

  if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    eyeIcon.classList.remove('fa-eye');
    eyeIcon.classList.add('fa-eye-slash');
  } else {
    passwordInput.type = 'password';
    eyeIcon.classList.remove('fa-eye-slash');
    eyeIcon.classList.add('fa-eye');
  }
 });



 $(function (){

  $('#connect-form').submit(function(e){

    e.preventDefault(); // Empêche le rechargement de la page
    $('.comments').empty();
    let postdata = $('#connect-form').serialize();

    //reinitialse les bordures
    $('#nomUser').removeClass('error');
    $('#passwd').removeClass('error');

    $.ajax({
      type: 'POST',
      url: '../../../server/php/traitement_conn.php',
      data: postdata,
      dataType: 'json',
      success: function(result) {
             
        if(result.ensuccess){
          $("#connect-form")[0].reset();
          window.location.href = '../dashboard/border.php';
        }
        else
        {
          $("#nomUser + .comments").html(result.nomUserError);
          $(".pass + .comments").html(result.passwdError);

          let comments = document.querySelectorAll('.comments');
          
          for(var i = 0; i<comments.length; i++)
          {
            let comment = comments[i];
            let input =comment.previousElementSibling;

            if(comment.textContent.trim() === '')
            {
              input.style.borderColor = '#ccc';
            }else{
              input.style.borderColor = "#d82c2e";
            }
          }

        }

      }

    })


  });

})

})


