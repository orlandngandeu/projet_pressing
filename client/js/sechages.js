$(document).ready(function(){

    $('#menu-btn').click(function(){
        $('#menu').toggleClass("active");
    })

    $(function(){

        $('table').DataTable();

        //demarrer un sechage 
        $('#create').on('click', function(e)
        {

            let formOrder = $('#formOrder'); 
            e.preventDefault();
            $.ajax({
                url:'../../../server/php/trait_sec.php',
                type: 'post',
                data: formOrder.serialize() + '&action=create',
                dataType: 'json',
                success: function(response){
                        
                    if(response.success){
                        let paragraphs = document.getElementsByClassName("comments");
                        for(var i = 0; i<paragraphs.length; i++)
                        {
                            paragraphs[i].innerHTML = "";
                        }
                        $('#createModal').modal('hide');
                        if(response.proprete){
                            Swal.fire('Veuillez noter que ce lavage contient des pièces sales et il sera impossible de faire son repassage tant que son etat n\'est pas mis a jour !');
                        }else{
                            Swal.fire({
                              icon: 'success',
                              title: 'Success',
                            })
                        }
                        formOrder[0].reset();
                        getsechages();
                    }else{
                        $("#idLav + .comments").html(response.id_lavageError);
                        $("#sechage + .comments").html(response.typeSecError);
                    }
                }
            })

        });

        //recupere les sechages
        getsechages();
        function getsechages()
        {
            $.ajax({
                url:'../../../server/php/trait_sec.php',
                type:'post',
                data:{action: 'fetch'},
                success:function(response)
                {
                    $('#orderTable').html(response);
                    $('table').DataTable({
                        order : [0, 'desc'],
                    });
                }
            })
        }

        //stopper le sechage
        $('body').on('click','.stop', function(e){

            e.preventDefault();
            $.ajax({
             url:'../../../server/php/trait_sec.php',
             type: 'post',
             data: {stopId: this.dataset.id},
             dataType: 'json',
             success: function(response)
             {
                if(response){
                    Swal.fire({
                        icon: 'success',
                        title: 'Le séchage est Terminée',
                    })
                }
                getsechages();
             }
            })
        })

        let croixannul = document.querySelectorAll('.btn-close');
        let textannul = document.querySelectorAll(".btn.btn-secondary");
        let annulation = Array.from(croixannul).concat(Array.from(textannul));

        annulation.forEach(function(element) {
            element.addEventListener('click', function(){
                let paragraphss = document.getElementsByClassName("comments");
                for(var j = 0; j<paragraphss.length; j++)
                {
                   paragraphss[j].innerHTML = "";
                }
            }) 

        });



        // Gestion du clic sur le bouton "propriete"
        $('body').on('click', '.prop', function (e) {
            e.preventDefault();
          
            const userChoice = prompt('Choisissez entre "parfait" ou "mauvais":');
         
            if (userChoice !== null && userChoice !== '') 
            {
              if (userChoice === 'parfait' || userChoice === 'mauvais') {
                $.ajax({
                  url: '../../../server/php/trait_sec.php',
                  type: 'post',
                  data: {
                    userChoice: userChoice,
                    choixId: this.dataset.id
                  },
                  dataType: 'json',
                  success: function (response) {
                    if (response) {
                     if(userChoice === 'mauvais'){
                        alert('Veuillez noter que ce séchage n\'est pas entièrement parfait et ainsi les commandes y figurant ne seront pas prètes pour un repassage tant que cet etat ne sera pas mis a jour !');
                     }else{
                        alert('Ok le séchage s\'est bien déroulé donc vous pouvez maintenant passez au repassage');
                     }
                     getsechages();
                    }
                  }
                });
              }else{
                alert('Veuillez choisir entre "parfait" ou "mauvais".');
              }
            }else{
              alert('Veuillez faire un choix entre "parfait" ou "mauvais".');
            }
        });



        //informations sur un  sechage
        $('body').on('click', '.infoBtn', function(e) {
            e.preventDefault();
            
            $.ajax({
              url: '../../../server/php/trait_sec.php',
              type: 'post',
              data: { informationId: this.dataset.id },
              dataType: 'json',
              success: function(response) {
                let html = `<strong>Information du séchage N° ${response.id_sechage}</strong><br>`;
                let contenu = `
                  Identifiant lavage : <b>${response.id_lavage}</b><br>
                  Date début : <b>${response.temps_debut}</b><br>
                  Date Fin : <b>${response.temps_fin}</b><br>
                  Commentaire : <b>${response.commentaires}</b><br>
                  Statut : <b>${response.statut}</b><br>
                  Propriété : <b>${response.etat_sechement}</b><br>
                  Type séchage : <b>${response.type_sechage}</b><br>
                `;
                
                Swal.fire({
                  title: html,
                  icon: 'info',
                  html: contenu,
                  showCloseButton: true,
                  focusConfirm: false,
                  confirmButtonText: '<i class="fa fa-thumbs-up"></i> Super!',
                  confirmButtonAriaLabel: 'Bravo, super!',
                });
              },
              error: function(xhr, status, error) {
                console.error(error);
                Swal.fire({
                  icon: 'error',
                  title: 'Erreur',
                  text: 'Une erreur s\'est produite.',
                });
              }
            });
        });


        

    });


});    