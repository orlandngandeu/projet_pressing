$(document).ready(function(){

    $('#menu-btn').click(function(){
        $('#menu').toggleClass("active");
    })

    $(function(){

        $('table').DataTable();

        //creer une livraison

        $('#create').on('click', function(e){

            let formOrder = $('#formOrder')
            
                e.preventDefault();
                $.ajax({
                    url:'../../../server/php/livraison.php',
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
                            $("#idcomm + .comments").html(response.idcommerror);
                            $("#idDate + .comments").html(response.idDateError);
                            $("#idTime + .comments").html(response.idTimeError);
                        }
                    }
                })

        })




             //recupere les livraisons
             getBills();
             function getBills()
             {
                 $.ajax({
                     url:'../../../server/php/livraison.php',
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
                    url:'../../../server/php/livraison.php',
                    type: 'post',
                    data: {workingId: this.dataset.id},
                    success: function(response)
                    {
                        let billINFO = JSON.parse(response);
                        $('#id_liv').val(billINFO.id_livraison);
                        $('#idUPcomm').val(billINFO.id_commande);
                        $('#idUPDate').val(billINFO.date_livraison);
                        $('#idUPTime').val(billINFO.heure_livraison);
                        let select = document.querySelector('#statusUP');
                        let sexeOptions = Array.from(select.options);
                        sexeOptions.forEach((opt, idx) => {
                            if(opt.value === billINFO.statut_livraison) select.selectedIndex = idx;
                        })
                    }
                })
            })


            $('#update').on('click', function(e){

                let formOrder = $('#formupdateOrder')
                
                    e.preventDefault();
                    $.ajax({
                        url:'../../../server/php/livraison.php',
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
                                $("#idUPcomm + .commentsup").html(response.id_UPcommError);
                            }
                        }
                    })
    
            })


            $('body').on('click','.infoBtn', function(e){

                e.preventDefault();
                $.ajax({
                    url:'../../../server/php/livraison.php',
                    type: 'post',
                    data: {informationId: this.dataset.id},
                    success: function(response)
                    {
                        let information = JSON.parse(response);
                        Swal.fire({
                            title: `<strong>Information de la livraison N° ${information.id_livraison}</strong>`,
                            icon: 'info',
                            html:
                            `Identifiant de la commande: <b>  ${information.id_commande}</b><br>
                            date de la livraison: <b>  ${information.date_livraison}</b><br>
                            Heure de la livraison: <b>  ${information.heure_livraison}</b><br>
                            statut: <b>  ${information.statut_livraison}</b><br>`,
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
                    title: 'Vous allez supprimer la livraison ?' + this.dataset.id,
                    text: "Cette action est irreversible !",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'oui, j\'en suis sùr!'
                  }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '../../../server/php/livraison.php',
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


                    //fin de la livraison
                    $('body').on('click','.fin_liv', function(e){

                        e.preventDefault();
                        $.ajax({
                        url:'../../../server/php/livraison.php',
                        type: 'post',
                        data: {stopId: this.dataset.id},
                        dataType: 'json',
                        success: function(response)
                        {
                            if(response){
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Ok la livraison est Terminée',
                                })
                            }
                            getBills();
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