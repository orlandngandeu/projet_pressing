$(document).ready(function(){

    $('#menu-btn').click(function(){
        $('#menu').toggleClass("active");
    })

    $(function(){
        
        $('table').DataTable();

        //demarrer un emballage 
        $('#create').on('click', function(e)
        {

            let formOrder = $('#formOrder'); 
            e.preventDefault();
            $.ajax({
                url:'../../../server/php/emballg.php',
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
                        getempb();
                    }else{
                        $("#id_rep + .comments").html(response.idrepError);
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


        //recupere les emballages
        getempb();
        function getempb()
        {
            $.ajax({
                url:'../../../server/php/emballg.php',
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


        //stopper l'emballage
        $('body').on('click','.stop', function(e){

            e.preventDefault();
            $.ajax({
             url:'../../../server/php/emballg.php',
             type: 'post',
             data: {stopId: this.dataset.id},
             dataType: 'json',
             success: function(response)
             {
                if(response){
                    Swal.fire({
                        icon: 'success',
                        title: 'L\'emballage est Terminée',
                    })
                }
                getempb();
             }
            })
        })


        

        //informations sur l'embalage
        $('body').on('click', '.infoBtn', function(e) {
            e.preventDefault();
            
            $.ajax({
              url: '../../../server/php/emballg.php',
              type: 'post',
              data: { informationId: this.dataset.id },
              dataType: 'json',
              success: function(response) {
                let html = `<strong>Information de l'emballage N° ${response.id_emballage}</strong><br>`;
                let contenu = `
                  Identifiant repassage : <b>${response.id_repassage}</b><br>
                  Date début : <b>${response.date_debut}</b><br>
                  Date Fin : <b>${response.date_fin}</b><br>
                  Commentaire : <b>${response.commentaires}</b><br>
                  Statut : <b>${response.statut}</b><br>
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


        $('body').on('click', '.livraison', function(e) {
            $.ajax({
                url: '../../../server/php/emballg.php',
                type: 'post',
                dataType: 'json',
                data: { livraisonId: this.dataset.id },
                success: function(response) {
                  let commandPage = window.open("./livraisons.php");
                  commandPage.onload = function() {
                    let $boutonCommande = $(commandPage.document).find("#clicknew");
                    $boutonCommande.trigger("click");
                    $(commandPage.document).find("#idcomm").val(response.id_commande);
                    
                    window.close();
                    
                  };
                }
              });
        });



        $('body').on('click','.editBtn',function(e){

            e.preventDefault();
            $.ajax({
                url:'../../../server/php/emballg.php',
                type: 'post',
                data: {workingId: this.dataset.id},
                success: function(response)
                {
                    let emballageINFO = JSON.parse(response);
                    $('#id_emballage').val(emballageINFO.id_emballage);
                    $('#id_repass').val(emballageINFO.id_repassage);
                    $('#classiq_petit').val(emballageINFO.classique_petit);
                    $('#classiq_moyen').val(emballageINFO.classique_moyen);
                    $('#prestige_petit').val(emballageINFO.prestige_petit);
                    $('#prestige_moyen').val(emballageINFO.prestige_moyen);
                    $('#emballage_petit').val(emballageINFO.emballage_petit);
                    $('#emballage_grand').val(emballageINFO.emballage_grand);
                    $('#veste').val(emballageINFO.veste);
                }
            })
        })


        //update des emballages

        $('#update').on('click', function(e){

            let formOrder = $('#formupdateOrder')
            
                e.preventDefault();
                $.ajax({
                    url:'../../../server/php/emballg.php',
                    type: 'post',
                    data: formOrder.serialize() + '&action=update',
                    dataType: 'json',
                    success: function(response){
                        if(response.success){
                            $('#updateModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Mise à jour éffectué',
                            })
                            formOrder[0].reset();
                            getempb();
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: 'Rempissez toutes vos valeurs !!.',
                            });
                        }
                    }
                })

        })


    });
}); 