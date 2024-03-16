$(document).ready(function(){

    $('#menu-btn').click(function(){
        $('#menu').toggleClass("active");
    })

    $(function(){

        $('table').DataTable();

        //creer un responsable
        $('#create').on('click', function(e){

            let formOrder = $('#formOrder')
            
                e.preventDefault();
                $.ajax({
                    url:'../../../server/php/trait_resp.php',
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
                            $("#nameresp + .comments").html(response.nameresperror);
                            $("#surnameresp + .comments").html(response.surnameresperror);
                            $("#telresp + .comments").html(response.telresperror);
                            $("#pourc + .comments").html(response.pourcerror);
                        }
                    }
                })

        })



        //recupere les clients
        getBills();
        function getBills()
        {
            $.ajax({
                url:'../../../server/php/trait_resp.php',
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
                url:'../../../server/php/trait_resp.php',
                type: 'post',
                data: {workingId: this.dataset.id},
                success: function(response)
                {
                    let billINFO = JSON.parse(response);
                    $('#bill_id').val(billINFO.id_responsable);
                    $('#nameUPresp').val(billINFO.nom_respo);
                    $('#surnameUPresp').val(billINFO.surname_respo);
                    $('#telUPresp').val(billINFO.telephone);
                    $('#pourcUP').val(billINFO.pourcentage);
                }
            })
        })


        //update des responsable

        $('#update').on('click', function(e){

            let formOrder = $('#formupdateOrder')
            
                e.preventDefault();
                $.ajax({
                    url:'../../../server/php/trait_resp.php',
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
                            $("#nameUPresp + .commentsup").html(response.nameUPresperror);
                            $("#surnameUPresp + .commentsup").html(response.surnameUPresperror);
                            $("#telUPresp + .commentsup").html(response.telUPresperror);
                            $("#pourcUP + .commentsup").html(response.pourcUPerror);
                        }
                    }
                })

        })




        $('body').on('click','.infoBtn', function(e){

            e.preventDefault();
            $.ajax({
                url:'../../../server/php/trait_resp.php',
                type: 'post',
                data: {informationId: this.dataset.id},
                success: function(response)
                {
                    let information = JSON.parse(response);
                    Swal.fire({
                        title: `<strong>Information du responsable N° ${information.id_responsable}</strong>`,
                        icon: 'info',
                        html:
                        `Nom du responsable: <b>  ${information.nom_respo}</b><br>
                        prénom du responsable: <b>  ${information.surname_respo}</b><br>
                        Téléphone du responsable: <b>  ${information.telephone}</b><br>
                        pourcentage: <b>  ${information.pourcentage}</b><br> `,
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
                title: 'Vous allez supprimer le responsable ?' + this.dataset.id,
                text: "Cette action est irreversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'oui, j\'en suis sùr!'
              }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../../../server/php/trait_resp.php',
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