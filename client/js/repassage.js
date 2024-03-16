$(document).ready(function(){

    $('#menu-btn').click(function(){
        $('#menu').toggleClass("active");
    })

    $(function(){
        
        $('table').DataTable();

        //demarrer un repassage 
        $('#create').on('click', function(e)
        {

            let formOrder = $('#formOrder'); 
            e.preventDefault();
            $.ajax({
                url:'../../../server/php/rep.php',
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
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                        })
                        formOrder[0].reset();
                        getrep();
                    }else{
                        $("#idcomm + .comments").html(response.idcommError);
                    }
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


        //recupere les repassages
        getrep();
        function getrep()
        {
            $.ajax({
                url:'../../../server/php/rep.php',
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


        //stopper le repassage
        $('body').on('click','.stop', function(e){

            e.preventDefault();
            $.ajax({
             url:'../../../server/php/rep.php',
             type: 'post',
             data: {stopId: this.dataset.id},
             dataType: 'json',
             success: function(response)
             {
                if(response){
                    Swal.fire({
                        icon: 'success',
                        title: 'Le repassage est Terminée',
                    })
                }
                getrep();
             }
            })
        })


        // Gestion du clic sur le bouton "pieces"
        $('body').on('click', '.prop', function (e) {
            e.preventDefault();
          
            const userChoice = prompt('Entrer le nombre de pièces que vous avez repassé : ');
         
            if (userChoice !== null && userChoice !== '' && userChoice > 0) 
            {
                $.ajax({
                  url: '../../../server/php/rep.php',
                  type: 'post',
                  data: {
                    userChoice: userChoice,
                    choixId: this.dataset.id
                  },
                  dataType: 'json',
                  success: function (response) {
                    if (response.pieceselevee) {
                        alert('Impossible mon gar cette commande ne contient pas autant de pièces. Attention !!, elle contient  ' + response.piecesnonrepasser + '  pièces');
                    }else if(response.piecesegale)
                    {
                        alert('Ok toutes les pièces sont repassées et sont propres, vous pouvez maintenant emballer ! ');
                    }else if(response.piecesinfer){
                        alert(response.piecesnonrepasser + ' pièces n\'ont pas été repassé donc vous ne pouvez pas emballer cette commande tant que c\'est état ne sera pas mis a jour ! ');
                    } 
                    getrep();
                  }
                });
            }else{
              alert('Veuillez entrer un nombre strictement positif.');
            }
        });

        //informations sur un  repassage
        $('body').on('click', '.infoBtn', function(e) {
            e.preventDefault();
            
            $.ajax({
              url: '../../../server/php/rep.php',
              type: 'post',
              data: { informationId: this.dataset.id },
              dataType: 'json',
              success: function(response) {
                let html = `<strong>Information du répassage N° ${response.id_repassage}</strong><br>`;
                let contenu = `
                  Identifiant commande : <b>${response.id_commande}</b><br>
                  nom client : <b>${response.nom_client}  ${response.surname_client}</b><br>
                  Date début : <b>${response.temps_debut}</b><br>
                  Date Fin : <b>${response.temps_fin}</b><br>
                  Commentaire : <b>${response.commentaires}</b><br>
                  Statut : <b>${response.statut}</b><br>
                  pièces répassées : <b>${response.pieces}</b><br>
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



        $('body').on('click', '.emballage', function(e) {
            let data = this.dataset.id;
            let commandPage = window.open("./emballage.php");
            commandPage.onload = function() 
            {
                let $boutonCommande = $(commandPage.document).find("#clicknew");
                $boutonCommande.trigger("click");
                $(commandPage.document).find("#id_rep").val(data);
                  
                window.close();
                  
            };
        });


    });
});    