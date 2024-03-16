document.addEventListener('DOMContentLoaded', function(){
  
  $(function (){

    $('#contact-form').submit(function(e){

      e.preventDefault();
      $('.comments').empty();
      let imageFile = $('#image')[0].files[0];
      if (!imageFile) {
        $('#image + .comments').text('Veuillez sélectionner une image.');
        return;
      }

      let formData = new FormData();
      formData.append('image', imageFile);

      // Sérialiser les données du formulaire sous forme de tableau d'objets clé-valeur
      let formArray = $('#contact-form').serializeArray();
      $.each(formArray, function(i, field) {
        formData.append(field.name, field.value);
      });

      $.ajax({
        type: 'POST',
        url: '../../../server/php/traitement_inscription.php',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(result) {
             
          if(result.ensuccess){
            Swal.fire({
              icon: 'success',
              title: 'Successfull operation',
            })
            $("#contact-form")[0].reset();
            window.location.href = '../dashboard/border.php';
          }
          else{
            $("#nomCompl + .comments").html(result.nomComplError);
            $("#nomUser + .comments").html(result.nomUserError);
            $("#email + .comments").html(result.emailError);
            $("#phone + .comments").html(result.phoneError);
            $("#age + .comments").html(result.ageError);
            $("#pass + .comments").html(result.passError);
            $("#confpass + .comments").html(result.confpassError);
            $("#verif + .comments").html(result.verifError);
            $("#image + .comments").html(result.imageerror);
          }
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
      })
    });

})

})