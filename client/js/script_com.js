$(document).ready(function(){

    $('#menu-btn').click(function(){
        $('#menu').toggleClass("active");
    })

    $(function(){

        $('table').DataTable();

        $('#clientSearch').keyup(function() {
            var query = $(this).val();
            if (query !== '') {
                $.ajax({
                    url: '../../../server/php/trait_com.php',
                    method: 'POST',
                    data: {query: query},
                    success: function(data) {
                        $('#clientList').fadeIn();
                        $('#clientList').html(data);
                    }
                });
            } else {
                $('#clientList').fadeOut();
                $('#clientList').html('');
            }
        });


        $(document).on('click', '#clientList li', function() {
            var clientId = $(this).data('id');
            var clientName = $(this).text();
            $('#clientSearch').val(clientName);
            $('#clientId').val(clientId);
            $('#clientList').fadeOut();
        });


        $('#respoSearch').keyup(function() {
            var qresp = $(this).val();
            if (qresp !== '') {
                $.ajax({
                    url: '../../../server/php/trait_com.php',
                    method: 'POST',
                    data: {qresp: qresp},
                    success: function(data) {
                        $('#respoList').fadeIn();
                        $('#respoList').html(data);
                    }
                });
            } else {
                $('#respoList').fadeOut();
                $('#respoList').html('');
            }
        });




        $(document).on('click', '#respoList li', function() {
            let respoId = $(this).data('id');
            let respoName = $(this).text();
            $('#respoSearch').val(respoName);
            $('#respoId').val(respoId);
            $('#respoList').fadeOut();
        });



        //creer une commande 
        $('#create').on('click', function(e)
        {

            let formOrder = $('#formOrder')
            
                e.preventDefault();
                $.ajax({
                    url:'../../../server/php/trait_com.php',
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
                            $("#clientSearch + .comments").html(response.clientListerror);
                            $("#respoSearch + .comments").html(response.respoListerror);
                            $("#nbrepcs + .comments").html(response.nbrepcserror);
                        }
                    }
                })

        })


              //recupere les commandes
              getBills();
              function getBills()
              {
                  $.ajax({
                      url:'../../../server/php/trait_com.php',
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
                    url:'../../../server/php/trait_com.php',
                    type: 'post',
                    data: {workingId: this.dataset.id},
                    success: function(response)
                    {
                        let billINFO = JSON.parse(response);
                        $('#commUPId').val(billINFO.id_commande);
                        $('#clientUPSearch').val(billINFO.nom_client);
                        $('#clientUPId').val(billINFO.id_client);
                        $('#respoUPSearch').val(billINFO.nom_respo);
                        $('#respoUPId').val(billINFO.id_responsable);
                        $('#nbreUPpcs').val(billINFO.nombre_pieces);
                        $('#commentsUP').val(billINFO.commentaire_employee);
                        $('#EntryUPdate').val(billINFO.Entry_date);
                        $('#EntryUPhour').val(billINFO.Entry_hour);
                        $('#dateUPlivraison').val(billINFO.date_livraison);
                        let select = document.querySelector('#statusUP');
                        let sexeOptions = Array.from(select.options);
                        sexeOptions.forEach((opt, idx) => {
                            if(opt.value === billINFO.sexe) select.selectedIndex = idx;
                        })
                    }
                })
            })


            $('#clientUPSearch').keyup(function() {
                var query = $(this).val();
                if (query !== '') {
                    $.ajax({
                        url: '../../../server/php/trait_com.php',
                        method: 'POST',
                        data: {query: query},
                        success: function(data) {
                            $('#clientUPList').fadeIn();
                            $('#clientUPList').html(data);
                        }
                    });
                } else {
                    $('#clientUPList').fadeOut();
                    $('#clientUPList').html('');
                }
            });
    
    
            $(document).on('click', '#clientUPList li', function() {
                var clientUPId = $(this).data('id');
                var clientUPName = $(this).text();
                $('#clientUPSearch').val(clientUPName);
                $('#clientUPId').val(clientUPId);
                $('#clientUPList').fadeOut();
            });
    
    
            $('#respoUPSearch').keyup(function() {
                var qresp = $(this).val();
                if (qresp !== '') {
                    $.ajax({
                        url: '../../../server/php/trait_com.php',
                        method: 'POST',
                        data: {qresp: qresp},
                        success: function(data) {
                            $('#respoUPList').fadeIn();
                            $('#respoUPList').html(data);
                        }
                    });
                } else {
                    $('#respoUPList').fadeOut();
                    $('#respoUPList').html('');
                }
            });

            $(document).on('click', '#respoUPList li', function() {
                let respoUPId = $(this).data('id');
                let respoUPName = $(this).text();
                $('#respoUPSearch').val(respoUPName);
                $('#respoUPId').val(respoUPId);
                $('#respoUPList').fadeOut();
            });


            //update des commandes

        $('#update').on('click', function(e){

            let formOrder = $('#formupdateOrder')
            
                e.preventDefault();
                $.ajax({
                    url:'../../../server/php/trait_com.php',
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
                            $("#nbreUPpcs + .commentsup").html(response.nbreUPpcserror);
                            $("#EntryUPdate + .commentsup").html(response.dateheureerror);
                            $("#EntryUPhour + .commentsup").html(response.dateheureerror);
                            $("#dateUPlivraison + .commentsup").html(response.dateUPlivraisonerror);
                        }
                    }
                })

        })



        //informations sur la commande

        $('body').on('click','.infoBtn', function(e){

            e.preventDefault();
            $.ajax({
                url:'../../../server/php/trait_com.php',
                type: 'post',
                data: {informationId: this.dataset.id},
                success: function(response)
                {
                    let information = JSON.parse(response);
                    Swal.fire({
                        title: `<strong>Information de la commande N° ${information.id_commande}</strong>`,
                        icon: 'info',
                        html:
                        `Nom du client: <b>  ${information.nom_client}</b><br>
                        prénom du client: <b>  ${information.surname_client}</b><br>
                        Nom du responsable: <b>  ${information.nom_respo}</b><br>
                        Nombre de pièces: <b>  ${information.nombre_pieces}</b><br>
                        Date d'entrée: <b>  ${information.Entry_date}</b><br>
                        statut: <b>  ${information.statut}</b><br>
                        Heure d'entrée: <b>  ${information.Entry_hour}</b><br>
                        Date de livraison: <b>  ${information.date_livraison}</b><br>
                        Commentaire: <b>  ${information.commentaire_employee}</b><br>
                        pièces lavées: <b>  ${information.pieces_lavee}</b><br> `,
                        showCloseButton: true,
                        focusConfirm: false,
                        confirmButtonText:
                          '<i class="fa fa-thumbs-up"></i> Super!',
                        confirmButtonAriaLabel: 'Bravo, super!',
                    })
                }
            })
        })


        //supprimer une commande

        $('body').on('click','.deleteBtn', function(e){

            e.preventDefault();

            Swal.fire({
                title: 'Vous allez supprimer le client ?' + this.dataset.id,
                text: "Cette action est irreversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'oui, j\'en suis sùr!'
              }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../../../server/php/trait_com.php',
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
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Impossible de faire cette suppression !!',
                                })
                            }
                        }
                    })
                 
                }
             })

        })


        $('body').on('click', '.listingBtn', function(e) {
            e.preventDefault();
            let idcommande = this.dataset.id;
            
            let commandPage = window.open("./listing.php");
            commandPage.onload = function() {
                let $boutonCommande = $(commandPage.document).find("#clicknew");
                $boutonCommande.trigger("click");
                $(commandPage.document).find("#id_comm").val(idcommande);
                window.close();
            };
           
        });


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





    });
})
