$(document).ready(function(){

    $('#menu-btn').click(function(){
        $('#menu').toggleClass("active");
    })

    $(function(){

        $('table').DataTable();

        //saisir des commandes_lavages 
        $('#commande_lavage').on('click', function(e)
        {
            let id_comm = document.getElementById("id_comm").value;
            let nbrepcs = document.getElementById("nbrepcs").value;

            let formData = new FormData();
            formData.append("id_comm", id_comm);
            formData.append("nbrepcs", nbrepcs);
            formData.append("action", "commande_lavage");

            e.preventDefault();
            $('.comments').empty();
           
            $.ajax({
                url:'../../../server/php/trait_lav.php',
                type: 'post',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response){
                    if(response.success){
                        let paragraphs = document.getElementsByClassName("comments");
                        for(var i = 0; i<paragraphs.length; i++)
                        {
                            paragraphs[i].innerHTML = "";
                        }
                        document.getElementById("id_comm").value = "";
                        document.getElementById("nbrepcs").value = "";
                    }else{
                        $("#id_comm + .comments").html(response.id_commerror);
                        $("#nbrepcs + .comments").html(response.nbrepcserror);
                    }
                }
            })

        })

        //saisir des lavages_produits
        
        $('#searchProd').keyup(function() {
            var query = $(this).val();
            if (query !== '') {
                $.ajax({
                    url: '../../../server/php/trait_lav.php',
                    method: 'POST',
                    data: {query: query},
                    success: function(data) {
                        $('#prodList').fadeIn();
                        $('#prodList').html(data);
                    }
                });
            } else {
                $('#prodList').fadeOut();
                $('#prodList').html('');
            }
        });


        $(document).on('click', '#prodList li', function() {
            var prodId = $(this).data('id');
            var prodName = $(this).text();
            $('#searchProd').val(prodName);
            $('#prodId').val(prodId);
            $('#prodList').fadeOut();
        });


        //saisir des produit_lavages 
        $('#lavage_produit').on('click', function(e)
        {
            let id_produit = document.getElementById("prodId").value;
            let Utilisation = document.getElementById("Utilisation").value;

            let donnform = new FormData();
            donnform.append("id_produit", id_produit);
            donnform.append("Utilisation", Utilisation);
            donnform.append("action", "produit_lavage");
            e.preventDefault();
            $('.comments').empty();
           
            $.ajax({
                url:'../../../server/php/trait_lav.php',
                type: 'post',
                data: donnform,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response){
                    if(response.success){
                        let paragraphs = document.getElementsByClassName("comments");
                        for(var i = 0; i<paragraphs.length; i++)
                        {
                            paragraphs[i].innerHTML = "";
                        }
                        document.getElementById("searchProd").value = "";
                        document.getElementById("prodId").value = "";
                        if(response.usage){
                            Swal.fire('un produit a été rétiré dans le stock !');
                        }
                    }else{
                        $("#searchProd + .comments").html(response.prodIderror);
                    }
                }
            })

        })

        //planifier un lavage 
        $('#create').on('click', function(e)
        {
            let machine = document.getElementById("machine").value;
            let masse = document.getElementById("masse").value;
            let comlav = document.getElementById("comlav").value;

            let createform = new FormData();
            createform.append("machine", machine);
            createform.append("masse", masse);
            createform.append("comlav", comlav);
            createform.append("action", "planifier");
            e.preventDefault();
            $('.comments').empty();

            $.ajax({
                url:'../../../server/php/trait_lav.php',
                type: 'post',
                data: createform,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response){
                        
                  if(response.success){
                    let paragraphs = document.getElementsByClassName("comments");
                    for(var i = 0; i<paragraphs.length; i++)
                    {
                        paragraphs[i].innerHTML = "";
                    }
                    document.getElementById("machine").value = "";
                    document.getElementById("masse").value = "";
                    document.getElementById("comlav").value = "";
                    $('#createModal').modal('hide');
                        Swal.fire({
                        icon: 'success',
                        title: 'Success',
                    })
                    getlavages();
                  }else{
                    $("#masse + .comments").html(response.masseerror);
                    $("#machine + .comments").html(response.machineerror);
                    if(response.valeur)
                    {
                        Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Verifiez les commandes et les produits !',
                        })
                    }
                  }
                }
            })

        })

       //recupere les lavages
        getlavages();
        function getlavages()
        {
            $.ajax({
                url:'../../../server/php/trait_lav.php',
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

        //stopper le lavage
        $('body').on('click','.stop', function(e){

            e.preventDefault();
            $.ajax({
             url:'../../../server/php/trait_lav.php',
             type: 'post',
             data: {stopId: this.dataset.id},
             dataType: 'json',
             success: function(response)
             {
                if(response){
                    Swal.fire({
                        icon: 'success',
                        title: 'Le lavage est Terminée',
                    })
                }
                getlavages();
             }
            })
        })


        // Gestion du clic sur le bouton "augmenter"
        $('body').on('click', '.augmenter', function (e){
            e.preventDefault();
            let contenu = '<input id="productName" class="swal2-input" placeholder="Nom du produit">' +
            '<div id="popupList"></div>' +
            '<input type="hidden" id="productId">' +
            '<select id="productSelect" class="swal2-select">' +
            '<option value="Première fois">Première fois</option>' +
            '<option value="En cours">En cours</option>' +
            '<option value="Dernière fois">Dernière fois</option>' +
            '</select>';

            Swal.fire({
            title: 'Augmenter un produit',
            html: contenu,
            showCancelButton: true,
            didOpen: () => {
                $('#productName').on('input', function (){
                    var query = $(this).val();
                    if (query !== '') {
                        $.ajax({
                            url: '../../../server/php/trait_lav.php',
                            method: 'POST',
                            data: { query: query },
                            success: function (data) {
                            $('#popupList').fadeIn();
                            $('#popupList').html(data);
                            }
                        });
                    }else{
                        $('#popupList').fadeOut();
                        $('#popupList').html('');
                    }
                });

                $(document).on('click', '#popupList li', function () {
                    var prodsweetId = $(this).data('id');
                    var prodsweetName = $(this).text();
                    $('#productName').val(prodsweetName);
                    $('#productId').val(prodsweetId);
                    $('#popupList').fadeOut();
                });
            },
            preConfirm: () => {
                const productName = Swal.getPopup().querySelector('#productName').value;
                const productId = Swal.getPopup().querySelector('#productId').value;
                $('#productId').val("");
                const productSelect = Swal.getPopup().querySelector('#productSelect').value;
        
                if (!productName || !productId) {
                Swal.showValidationMessage('Le nom du produit est requis');
                }
        
                return { productId, productSelect };
            }
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                url: '../../../server/php/trait_lav.php',
                type: 'post',
                data: {
                    productId: result.value.productId,
                    productSelect: result.value.productSelect,
                    augmenterId: this.dataset.id
                },
                dataType: 'json',
                success: function (response) {
                    if (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Le produit est inséré',
                    });
                    }
                }
                });
            }
            });
        });

        // Gestion du clic sur le bouton "propriete"
        $('body').on('click', '.prop', function (e) {
            e.preventDefault();
          
            const userChoice = prompt('Choisissez entre "propre" ou "sale":');
         
            if (userChoice !== null && userChoice !== '') 
            {
              if (userChoice === 'propre' || userChoice === 'sale') {
                $.ajax({
                  url: '../../../server/php/trait_lav.php',
                  type: 'post',
                  data: {
                    userChoice: userChoice,
                    choixId: this.dataset.id
                  },
                  dataType: 'json',
                  success: function (response) {
                    if (response) {
                     if(userChoice === 'sale'){
                        alert('Veuillez noter que ce lavage n\'est pas entièrement propre et ainsi les commandes y figurant ne seront pas prètes pour un repassage tant que cet etat ne sera pas mis a jour !');
                     }else{
                        alert('Ok le lavage s\'est bien déroulé donc vous pouvez maintenant passez au séchage');
                     }
                     getlavages();
                    }
                  }
                });
              }else{
                alert('Veuillez choisir entre "propre" ou "sale".');
              }
            }else{
              alert('Veuillez faire un choix entre "propre" ou "sale".');
            }
        });
        
   
        
        //informations sur un  lavage
        $('body').on('click', '.infoBtn', function(e) {
            e.preventDefault();
            
            $.ajax({
              url: '../../../server/php/trait_lav.php',
              type: 'post',
              data: { informationId: this.dataset.id },
              dataType: 'json',
              success: function(response) {
                let html = `<strong>Information du lavage N° ${response.planifier.id_lavage}</strong><br>`;
                let contenu = `
                  Date début : <b>${response.planifier.temps_debut}</b><br>
                  Date Fin : <b>${response.planifier.temps_fin}</b><br>
                  Commentaire : <b>${response.planifier.commentaire_lavage}</b><br>
                  Statut : <b>${response.planifier.statut}</b><br>
                  Propriété : <b>${response.planifier.proprete}</b><br>
                  Type machine : <b>${response.planifier.type_machine}</b><br>
                  Masse : <b>${response.planifier.masse}</b><br>
                `;
                
                for (var i = 0; i < response.commlav.length; i++) {
                  contenu += `Commande : <b>${response.commlav[i].id_commande} --> 
                  ${response.commlav[i].nombre_pieces} Pièces</b><br>`;
                }
                
                for (var j = 0; j < response.prodlav.length; j++) {
                  contenu += `Produit : <b>${response.prodlav[j].nom_produit} --> 
                  ${response.prodlav[j].utilisation_produit}</b><br>`;
                }
                
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


        $('body').on('click', '.sec', function(e) {
            e.preventDefault();
            $.ajax({
              url: '../../../server/php/trait_lav.php',
              type: 'post',
              dataType: 'json',
              data: { secId: this.dataset.id },
              success: function(response) {
                if(response)
                {
                  let commandPage = window.open("./sechage.php");
                  commandPage.onload = function() {
                  let $boutonCommande = $(commandPage.document).find("#clicknew");
                  $boutonCommande.trigger("click");
                  $(commandPage.document).find("#idLav").val(response.id_lavage);
                  
                  window.close();
                  
                  };
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'ce lavage ne peut encore passé au séchage',
                    })
                }
                
              }
            });
        });

    })
});