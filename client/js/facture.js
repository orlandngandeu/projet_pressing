$(document).ready(function(){

    let tot = 0;
    let p = 0;
    
    $('#menu-btn').click(function(){
        $('#menu').toggleClass("active");
    })


    $(function(){

        $('table').DataTable();

        $('#idcomm').keyup(function() {
            let remiseInput = document.getElementById('remise');
            let identifiantCommande = document.getElementById('idcomm');
            let query = $(this).val();
            if (query === '') {
                query = identifiantCommande.value.trim();
            }
            console.log("uuuuuuuuuu"+query);

            if (query !== ''){
                $.ajax({
                    url: '../../../server/php/facture.php',
                    method: 'POST',
                    data: {query: query},
                    dataType: 'json',
                    success: function(data) {
                        if(data)
                        {
                            $('#montantTOT').val(data.amount);
                            $('#netpayer').val(data.amount);
                            let montcaisse = (data.amount/100)*(100-data.pourcent);
                            $('#montantcaisse').val(montcaisse);
                            remiseInput.addEventListener('input',function(){

                                let remise = parseFloat(remiseInput.value);
                                if(data)
                                {
                                    if(remise != '' && remise>0 && remise<100)
                                    {
                                        let netpayer = (data.amount/100)*(100-remise);
                                        $('#netpayer').val(netpayer);
                                        let montcaisse = (netpayer/100)*(100-data.pourcent);
                                        $('#montantcaisse').val(montcaisse);
                                    }
                                    else{
                                        $('#montantTOT').val(data.amount);
                                        $('#netpayer').val(data.amount);
                                        let montcaisse = (data.amount/100)*(100-data.pourcent);
                                        $('#montantcaisse').val(montcaisse);
                                    }
                                }else{
                                    $('#montantTOT').val("");
                                    $('#netpayer').val("");
                                    $('#montantcaisse').val("");
                                }
                          

                            })
                            
                        }else{
                            $('#montantTOT').val("");
                            $('#netpayer').val("");
                            $('#montantcaisse').val("");
                        }
                    }
                });
            }
        });
        


        //remplir unr facture 
         $('#create').on('click', function(e)
         {
 
             let formOrder = $('#formOrder');

             
                 e.preventDefault();
                 $('#error').empty();
                 $.ajax({
                     url:'../../../server/php/facture.php',
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
                            $("#remise + .comments").html(response.remiseerror);
                            $("#echeance + .comments").html(response.echeanceerror);
                         }
                     }
                 })
 
         })



         //recupere les factures
         getBills();
         function getBills()
         {
             $.ajax({
                 url:'../../../server/php/facture.php',
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
                url:'../../../server/php/facture.php',
                type: 'post',
                data: {workingId: this.dataset.id},
                success: function(response)
                {
                    let billINFO = JSON.parse(response);
                    $('#id_fact').val(billINFO.id_facture);
                    $('#idcommUP').val(billINFO.id_commande);
                    $('#montantTOTUP').val(billINFO.montant_total);
                    $('#remiseUP').val(billINFO.remise);
                    $('#netpayerUP').val(billINFO.net_payer);
                    $('#montantcaisseUP').val(billINFO.montant_caisse);
                    $('#dateUPfacture').val(billINFO.date_facture);
                    $('#heureUPfacture').val(billINFO.heure_facture);
                    $('#echeanceUP').val(billINFO.date_echeance);
                    tot = billINFO.montant_total;
                    p = 100*(1-(billINFO.montant_caisse/billINFO.net_payer));
                }
            })
        })


    
         
        
                
        $('#remiseUP').keyup(function() {

          let rem = $(this).val();
          if (rem<100 || rem == '')
          {
            if(rem == '' || rem<=0)
            {
                rem = 0;
            }
            let netpayer = (tot/100)*(100-rem);
            $('#netpayerUP').val(netpayer);
            let montcaisse = (netpayer/100)*(100-p);
            $('#montantcaisseUP').val(montcaisse);
          }else{
            $('#netpayerUP').val("");
            $('#montantcaisseUP').val("");
          }
                                 
        })
                                

        $('#update').on('click', function(e){

            let formOrder = $('#formupdateOrder')
            
                e.preventDefault();
                $.ajax({
                    url:'../../../server/php/facture.php',
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
                            $("#remiseUP + .commentsup").html(response.remiseUPerror);
                            $("#echeanceUP + .commentsup").html(response.echeanceUPerror);
                        }
                    }
                })

        })


        //informations sur la commande

        $('body').on('click','.infoBtn', function(e){

            e.preventDefault();
            $.ajax({
                url:'../../../server/php/facture.php',
                type: 'post',
                data: {informationId: this.dataset.id},
                success: function(response)
                {
                    let information = JSON.parse(response);
                    Swal.fire({
                        title: `<strong>Information de la facture N° ${information.id_facture}</strong>`,
                        icon: 'info',
                        html:
                        `Information de la commande: <b>  ${information.id_commande}</b><br>
                        montant facture: <b>  ${information.montant_total}</b><br>
                        remise: <b>  ${information.remise}</b><br>
                        net payer: <b>  ${information.net_payer}</b><br>
                        montant caisse: <b>  ${information.montant_caisse}</b><br>
                        date facture: <b>  ${information.date_facture}</b><br>
                        heure facture: <b>  ${information.heure_facture}</b><br>
                        statut: <b>  ${information.statut}</b><br>
                        reste payer: <b>  ${information.reste_payer}</b><br>
                        date echeance: <b>  ${information.date_echeance}</b><br> `,
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
                title: 'Vous allez supprimer la facture ?' + this.dataset.id,
                text: "Cette action est irreversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'oui, j\'en suis sùr!'
              }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../../../server/php/facture.php',
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


        $('body').on('click','.printBtn', function(e)
        {
            e.preventDefault();
            let printId = this.dataset.id;
            localStorage.setItem("printId", printId);
            window.location.href = '../dashboard/pdf.php';
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