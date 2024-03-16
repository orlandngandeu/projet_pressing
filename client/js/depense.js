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
                    url: '../../../server/php/depense.php',
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




        //creer une depense 
        $('#create').on('click', function(e)
        {

            let formOrder = $('#formOrder')
            
                e.preventDefault();
                $.ajax({
                    url:'../../../server/php/depense.php',
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
                            $("#desc + .comments").html(response.descerror);
                            $("#amount + .comments").html(response.amounterror);
                            if(response.errorother)
                            {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Une erreur s\'est produite !',
                                    footer: 'Verifie toutes tes valeurs'
                                })
                            }
                        }
                    }
                })

        })




              //recupere les depenses
              getBills();
              function getBills()
              {
                  $.ajax({
                      url:'../../../server/php/depense.php',
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
                    url:'../../../server/php/depense.php',
                    type: 'post',
                    data: {workingId: this.dataset.id},
                    success: function(response)
                    {
                        let billINFO = JSON.parse(response);
                        $('#depUP').val(billINFO.id_depense);
                        $('#emplUPSearch').val(billINFO.nomComplet_employé);
                        $('#emplUPId').val(billINFO.id_employé);
                        $('#descUP').val(billINFO.descriptionss);
                        $('#amountUP').val(billINFO.montant_depense);
                        $('#dateUP').val(billINFO.date_depense);
                        $('#heureUP').val(billINFO.heure_depense);
                    }
                })
            })


            $('#emplUPSearch').keyup(function() {
                var query = $(this).val();
                if (query !== '') {
                    $.ajax({
                        url: '../../../server/php/depense.php',
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
    


    //update des depenses

    $('#update').on('click', function(e){

        let formOrder = $('#formupdateOrder')
        
            e.preventDefault();
            $.ajax({
                url:'../../../server/php/depense.php',
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
                        $("#descUP + .commentsup").html(response.descUPerror);
                        $("#amountUP + .commentsup").html(response.amountUPerror);
                        if(response.otherUPerror)
                        {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Une erreur s\'est produite !',
                                footer: 'Verifie toutes tes valeurs'
                            })
                        }
                        
                    }
                }
            })

    })



    //informations sur la depense

    $('body').on('click','.infoBtn', function(e){

        e.preventDefault();
        $.ajax({
            url:'../../../server/php/depense.php',
            type: 'post',
            data: {informationId: this.dataset.id},
            success: function(response)
            {
                let information = JSON.parse(response);
                Swal.fire({
                    title: `<strong>Information depense  N° ${information.id_depense}</strong>`,
                    icon: 'info',
                    html:
                    `Nom de l'employé: <b>  ${information.nomComplet_employé}</b><br>
                    Descriptions: <b>  ${information.descriptionss}</b><br>
                    Montant : <b>  ${information.montant_depense}</b><br>
                    Date dépense : <b>  ${information.date_depense}</b><br>
                    Heure : <b>  ${information.heure_depense}</b><br>`,
                    showCloseButton: true,
                    focusConfirm: false,
                    confirmButtonText:
                      '<i class="fa fa-thumbs-up"></i> Super!',
                    confirmButtonAriaLabel: 'Bravo, super!',
                })
            }
        })
    })



    //supprimer une depense

    $('body').on('click','.deleteBtn', function(e){

        e.preventDefault();

        Swal.fire({
            title: 'Vous allez supprimer cette information ?' + this.dataset.id,
            text: "Cette action est irreversible !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'oui, j\'en suis sùr!'
          }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../../../server/php/depense.php',
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