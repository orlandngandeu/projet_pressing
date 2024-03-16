$(document).ready(function(){

    $('#menu-btn').click(function(){
        $('#menu').toggleClass("active");
    })


    $(function(){

        $('table').DataTable();

        //creer un listing
        $('#create').on('click', function(e){

            let formOrder = $('#formOrder')
            
                e.preventDefault();
                $.ajax({
                    url:'../../../server/php/listing.php',
                    type: 'post',
                    data: formOrder.serialize() + '&action=create',
                    dataType: 'json',
                    success: function(response){
                        
                        if(response.ensuccess){
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
                            getBills();
                        }else{
                            $("#id_comm + .comments").html(response.id_commerror);
                            $("#listing + .comments").html(response.listingerror);
                            $("#quantify + .comments").html(response.quantifyerror);
                            $("#unit + .comments").html(response.uniterror);
                        }
                    }
                })

        })


               //recupere les commandes
               getBills();
               function getBills()
               {
                   $.ajax({
                       url:'../../../server/php/listing.php',
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


               $('body').on('click','.editBtn',function(e){

                e.preventDefault();
                $.ajax({
                    url:'../../../server/php/listing.php',
                    type: 'post',
                    data: {workingId: this.dataset.id},
                    success: function(response)
                    {
                        let billINFO = JSON.parse(response);
                        $('#id_list').val(billINFO.id_listing);
                        $('#id_UPcomm').val(billINFO.id_commande);
                        $('#quantifyUP').val(billINFO.quantity);
                        $('#qtelast').val(billINFO.quantity);
                        $('#unitUP').val(billINFO.prix_unitaire);
                        $('#unitlast').val(billINFO.prix_unitaire);
                        $('#listingUP').val(billINFO.Listing);
                        $('#instructUP').val(billINFO.Instructions_speciales);
            
                    }
                })
            })

                   //update des commandes

        $('#update').on('click', function(e){

            let formOrder = $('#formupdateOrder')
            
                e.preventDefault();
                $.ajax({
                    url:'../../../server/php/listing.php',
                    type: 'post',
                    data: formOrder.serialize() + '&action=update',
                    dataType: 'json',
                    success: function(response){
                        if(response.success){
                            let paragraphsup = document.getElementsByClassName("commentsup");
                            for(var i = 0; i<paragraphsup.length; i++)
                            {
                                paragraphsup[i].innerHTML = "";
                            }
                            $('#updateModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Mise à jour éffectué',
                            })
                            formOrder[0].reset();
                            getBills();
                        }else{
                            $("#listingUP + .commentsup").html(response.listingUPerror);
                            $("#quantifyUP + .commentsup").html(response.quantifyUPerror);
                            $("#unitUP + .commentsup").html(response.unitUPerror);
                        }
                    }
                })

        })


        $('body').on('click','.infoBtn', function(e){

            e.preventDefault();
            $.ajax({
                url:'../../../server/php/listing.php',
                type: 'post',
                data: {informationId: this.dataset.id},
                success: function(response)
                {
                    let information = JSON.parse(response);
                    Swal.fire({
                        title: `<strong>Information du listing N° ${information.id_listing}</strong>`,
                        icon: 'info',
                        html:
                        `Identifiant de la commande: <b>  ${information.id_commande}</b><br>
                        listing: <b>  ${information.Listing}</b><br>
                        Quantité: <b>  ${information.quantity}</b><br>
                        Prix untaire: <b>  ${information.prix_unitaire}</b><br>
                        montant: <b>  ${information.montant}</b><br>
                        Instructions spéciales: <b>  ${information.Instructions_speciales}</b><br> `,
                        showCloseButton: true,
                        focusConfirm: false,
                        confirmButtonText:
                          '<i class="fa fa-thumbs-up"></i> Super!',
                        confirmButtonAriaLabel: 'Bravo, super!',
                    })
                }
            })
        })




        $('body').on('click','.deleteBtn', function(e){

            e.preventDefault();

            Swal.fire({
                title: 'Vous allez supprimer le listing ?' + this.dataset.id,
                text: "Cette action est irreversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'oui, j\'en suis sùr!'
              }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../../../server/php/listing.php',
                        type: 'post',
                        data: {deleteId: this.dataset.id},
                        success: function(response)
                        {
                            if(response == 1)
                            {
                                Swal.fire(
                                    'Suppression!',
                                    'Opération réussie.',
                                    'success'
                                )
                                getBills();
                            }else{
                                $('#updateModal').modal('hide');
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Sorry...',
                                        text: 'Ce listing a deja une facture !',
                                        footer: 'Impossible de supprimer ici '
                                    })
                            }
                        }
                    })
                 
                }
             })

        })


        $('body').on('click', '.factureBtn', function(e) {
            e.preventDefault();
            let idcommande = this.dataset.id;
          
            let commandPage = window.open("./facture.php");
            commandPage.onload = function() {
              let $boutonCommande = $(commandPage.document).find("#clicknew");
              let $inputCommande = $(commandPage.document).find("#idcomm");
              $boutonCommande.trigger("click");
              $inputCommande.val(idcommande);
              let keyupEvent = new KeyboardEvent("keyup", {
                key: "Enter",
                keyCode: 13,
                which: 13,
                bubbles: true,
              });
              $inputCommande[0].dispatchEvent(keyupEvent);
          
              window.close();
            };
        });


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
                let uppagraphss = document.getElementsByClassName("commentsup");
                for(var t = 0; t<uppagraphss.length; t++)
                {
                    uppagraphss[t].innerHTML = "";
                }


            }) 

        });






    })
})