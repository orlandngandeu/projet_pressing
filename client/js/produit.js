$(document).ready(function(){

    $('#menu-btn').click(function(){
        $('#menu').toggleClass("active");
    })

    $(function(){


        $('table').DataTable();

        //creer un produit

        $('#create').on('click', function(e){

            let formOrder = $('#formOrder')
            
                e.preventDefault();
                $.ajax({
                    url:'../../../server/php/prod.php',
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
                            $("#nom_produit + .comments").html(response.nomError);
                            $("#qte_produit + .comments").html(response.qteError);
                            $("#prix_produit + .comments").html(response.priceError);
                        }
                    }
                })

        })


             //recupere les categories
             getBills();
             function getBills()
             {
                 $.ajax({
                     url:'../../../server/php/prod.php',
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
                url:'../../../server/php/prod.php',
                type: 'post',
                data: {workingId: this.dataset.id},
                success: function(response)
                {
                    let billINFO = JSON.parse(response);
                    $('#prod').val(billINFO.id_produit);
                    $('#nom_produitUP').val(billINFO.nom_produit);
                    $('#qte_produitUP').val(billINFO.quantity_stock);
                    $('#prix_produitUP').val(billINFO.prix_unitaire);
                    $('#descup').val(billINFO.Description_produit);
                }
            })
        })



        //update des categories

        $('#update').on('click', function(e){

            let formOrder = $('#formupdateOrder')
            
                e.preventDefault();
                $.ajax({
                    url:'../../../server/php/prod.php',
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
                            $("#nom_produitUP + .commentsup").html(response.nomUPerror);
                            $("#qte_produitUP + .commentsup").html(response.qteUPerror);
                            $("#prix_produitUP + .commentsup").html(response.priceUPerror);
                        }
                    }
                })

        })


        $('body').on('click','.infoBtn', function(e){

            e.preventDefault();
            $.ajax({
                url:'../../../server/php/prod.php',
                type: 'post',
                data: {informationId: this.dataset.id},
                success: function(response)
                {
                    let information = JSON.parse(response);
                    Swal.fire({
                        title: `<strong>Information produit N° ${information.id_produit}</strong>`,
                        icon: 'info',
                        html:
                        `Nom Produit: <b>  ${information.nom_produit}</b><br>
                        Quantité en stock: <b>  ${information.quantity_stock}</b><br> 
                        Prix unitaire: <b>  ${information.prix_unitaire}</b><br> 
                        Description du produit: <b>  ${information.Description_produit}</b><br> `,
                        showCloseButton: true,
                        focusConfirm: false,
                        confirmButtonText:
                          '<i class="fa fa-thumbs-up"></i> Super!',
                        confirmButtonAriaLabel: 'Bravo, super!',
                    })
                }
            })
        })




        $('body').on('click','.deleteBtn', function(e)
        {

            e.preventDefault();

            Swal.fire({
                title: 'Vous allez supprimer la catégorie ?' + this.dataset.id,
                text: "Cette action est irreversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'oui, j\'en suis sùr!'
              }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../../../server/php/prod.php',
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