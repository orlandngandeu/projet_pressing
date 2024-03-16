$(document).ready(function(){

    $('#menu-btn').click(function(){
        $('#menu').toggleClass("active");
    })


    $(function(){

        $('table').DataTable();


        //creer un client
        $('#create').on('click', function(e){

            let formOrder = $('#formOrder')
            
            e.preventDefault();
            $.ajax({
                url:'../../../server/php/trait_client.php',
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
                        $("#namecustomer + .comments").html(response.errornamecustomer);
                        $("#surnamecustomer + .comments").html(response.errorsurnamecustomer);
                        $("#adresse + .comments").html(response.erroradresse);
                        $("#email + .comments").html(response.erroremail);
                        $("#telephone + .comments").html(response.errortelephone);
                    }
                }
            })

        })



        //recupere les clients
        getBills();
        function getBills()
        {
            $.ajax({
                url:'../../../server/php/trait_client.php',
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
                url:'../../../server/php/trait_client.php',
                type: 'post',
                data: {workingId: this.dataset.id},
                success: function(response)
                {
                    let billINFO = JSON.parse(response);
                    $('#bill_id').val(billINFO.id_client);
                    $('#nameUPcustomer').val(billINFO.nom_client);
                    $('#surnameUPcustomer').val(billINFO.surname_client);
                    $('#adresseUP').val(billINFO.Adresse);
                    $('#emailUP').val(billINFO.Email);
                    $('#telephoneUP').val(billINFO.Telephone);
                    let select = document.querySelector('#stateUP');
                    let sexeOptions = Array.from(select.options);
                    sexeOptions.forEach((opt, idx) => {
                        if(opt.value === billINFO.sexe) select.selectedIndex = idx;
                    })
                }
            })
        })


        //update des clients

        $('#update').on('click', function(e){

            let formOrder = $('#formupdateOrder')
            
                e.preventDefault();
                $.ajax({
                    url:'../../../server/php/trait_client.php',
                    type: 'post',
                    data: formOrder.serialize() + '&action=update',
                    dataType: 'json',
                    success: function(response){
                        if(response.success){
                            let uppagraphs = document.getElementsByClassName("commentsup");
                            for(var i = 0; i<uppagraphs.length; i++)
                            {
                                uppagraphs[i].innerHTML = "";
                            }
                            $('#updateModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Mise à jour éffectué',
                            })
                            formOrder[0].reset();
                            getBills();
                        }else{
                            $("#nameUPcustomer + .commentsup").html(response.nameUPcustomererror);
                            $("#surnameUPcustomer + .commentsup").html(response.surnameUPcustomererror);
                            $("#adresseUP + .commentsup").html(response.adresseUPerror);
                            $("#emailUP + .commentsup").html(response.emailUPerror);
                            $("#telephoneUP + .commentsup").html(response.telephoneUPerror);
                        }
                    }
                })

        })




        $('body').on('click','.infoBtn', function(e){

            e.preventDefault();
            $.ajax({
                url:'../../../server/php/trait_client.php',
                type: 'post',
                data: {informationId: this.dataset.id},
                success: function(response)
                {
                    let information = JSON.parse(response);
                    Swal.fire({
                        title: `<strong>Information du client N° ${information.id_client}</strong>`,
                        icon: 'info',
                        html:
                        `Nom du client: <b>  ${information.nom_client}</b><br>
                        prénom du client: <b>  ${information.surname_client}</b><br>
                        Adresse du client: <b>  ${information.Adresse}</b><br>
                        Email du client: <b>  ${information.Email}</b><br>
                        Téléphone du client: <b>  ${information.Telephone}</b><br>
                        Sexe: <b>  ${information.sexe}</b><br> `,
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
                        url: '../../../server/php/trait_client.php',
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


        $('body').on('click', '.commandBtn', function(e) {
            e.preventDefault();
            $.ajax({
              url: '../../../server/php/trait_client.php',
              type: 'post',
              dataType: 'json',
              data: { commandId: this.dataset.id },
              success: function(response) {
                let commandPage = window.open("./commande.php");
                commandPage.onload = function() {
                  let $boutonCommande = $(commandPage.document).find("#clicknew");
                  $boutonCommande.trigger("click");
                  $(commandPage.document).find("#clientId").val(response.id_comm);
                  $(commandPage.document).find("#clientSearch").val(response.resultat['nom_client'] + ' ' + 
                  response.resultat['surname_client']);
                  
                  window.close();
                  
                };
              }
            });
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