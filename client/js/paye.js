$(document).ready(function(){

    $('#menu-btn').click(function(){
        $('#menu').toggleClass("active");
    })


    $(function(){

        $('table').DataTable();


        $('#emplSearch').keyup(function() {
            var query = $(this).val();
            if (query !== '') {
                $.ajax({
                    url: '../../../server/php/recu.php',
                    method: 'POST',
                    data: {query: query},
                    success: function(data) {
                        $('#emplList').fadeIn();
                        $('#emplList').html(data);
                    }
                });
            } else {
                $('#emplList').fadeOut();
                $('#emplList').html('');
            }
        });


        $(document).on('click', '#emplList li', function() {
            var emplId = $(this).data('id');
            var emplName = $(this).text();
            $('#emplSearch').val(emplName);
            $('#emplId').val(emplId);
            $('#emplList').fadeOut();
        });




        $('#create').on('click', function(e)
        {

            let formOrder = $('#formOrder')
            
                e.preventDefault();
                $.ajax({
                    url:'../../../server/php/recu.php',
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
                            $("#emplSearch + .comments").html(response.emplSearcherror);
                            $("#montant + .comments").html(response.montanterror);
                            $("#mode + .comments").html(response.modeerror);
                            $("#idfact + .comments").html(response.idfacterror);
                        }
                    }
                })

        })


          getBills();
          function getBills()
          {
              $.ajax({
                  url:'../../../server/php/recu.php',
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
                url:'../../../server/php/recu.php',
                type: 'post',
                data: {workingId: this.dataset.id},
                success: function(response)
                {
                    let billINFO = JSON.parse(response);
                    $('#id_recu').val(billINFO.id_recu);
                    $('#idfactUP').val(billINFO.id_facture);
                    $('#emplUPSearch').val(billINFO.nomComplet_employé);
                    $('#emplUPId').val(billINFO.id_employé);
                    $('#montantUP').val(billINFO.montant_paye);
                    $('#montantsaveUP').val(billINFO.montant_paye);
                    $('#modeUP').val(billINFO.mode_paiement);
                    $('#dateUP').val(billINFO.date_paiement);
                    $('#heureUP').val(billINFO.heure_paiement);
                }
            })
        })

        $('#emplUPSearch').keyup(function() {
            var query = $(this).val();
            if (query !== '') {
                $.ajax({
                    url: '../../../server/php/recu.php',
                    method: 'POST',
                    data: {query: query},
                    success: function(data) {
                        $('#emplUPList').fadeIn();
                        $('#emplUPList').html(data);
                    }
                });
            } else {
                $('#emplUPList').fadeOut();
                $('#emplUPList').html('');
            }
        });


        $(document).on('click', '#emplUPList li', function() {
            var emplUPId = $(this).data('id');
            var emplUPName = $(this).text();
            $('#emplUPSearch').val(emplUPName);
            $('#emplUPId').val(emplUPId);
            $('#emplUPList').fadeOut();
        });




        $('#update').on('click', function(e){

            let formOrder = $('#formupdateOrder')
            
                e.preventDefault();
                $.ajax({
                    url:'../../../server/php/recu.php',
                    type: 'post',
                    data: formOrder.serialize() + '&action=update',
                    dataType: 'json',
                    success: function(response)
                    {
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
                            $("#montantUP + .commentsup").html(response.montantUPerror);
                            $("#modeUP + .commentsup").html(response.modeUPerror);
                        }
                    }
                })
    
        })




        $('body').on('click','.infoBtn', function(e){

            e.preventDefault();
            $.ajax({
                url:'../../../server/php/recu.php',
                type: 'post',
                data: {informationId: this.dataset.id},
                success: function(response)
                {
                    let information = JSON.parse(response);
                    Swal.fire({
                        title: `<strong>Information payement  N° ${information.id_recu}</strong>`,
                        icon: 'info',
                        html:
                        `Identifiant de la facture: <b>  ${information.id_facture}</b><br>
                        Nom de l'employé: <b>  ${information.nomComplet_employé}</b><br>
                        Montant payé: <b>  ${information.montant_paye}</b><br>
                        mode de paiement : <b>  ${information.mode_paiement}</b><br>
                        date de payement : <b>  ${information.date_paiement}</b><br>
                        Heure de payement: <b>  ${information.heure_paiement}</b><br>`,
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
                title: 'Vous allez supprimer ce payement ?' + this.dataset.id,
                text: "Cette action est irreversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'oui, j\'en suis sùr!'
              }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../../../server/php/recu.php',
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
                            }
                        }
                    })
                 
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
                let uppagraphss = document.getElementsByClassName("commentsup");
                for(var t = 0; t<uppagraphss.length; t++)
                {
                    uppagraphss[t].innerHTML = "";
                }


            }) 

        });


    


    })    
})