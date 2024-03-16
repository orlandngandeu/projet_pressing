$(document).ready(function(){

    let facstyle = "facture_orlxio.pdf";

    $(function(){

        let printId = parseInt(localStorage.getItem("printId"));

        function estEntier(valeur) {
            return typeof valeur === 'number' && Number.isInteger(valeur);
        }

        if(estEntier(printId))
        {
            getbodyfact(printId);
            function getbodyfact(paramd)
            {
                $.ajax({
                    url:'../../../server/php/trait_pdf.php',
                    type:'post',
                    data:{paramd},
                    success:function(response)
                    {
                        $('#corpspdf').html(response);
                    }
                })
            }

            $.ajax({
                url: '../../../server/php/trait_pdf.php',
                type: 'post',
                data: {printId},
                success: function(response)
                {
                    let result = JSON.parse(response);
                    $('#nomclient').text(result.nom_client+' '+ result.surname_client);
                    $('#adresse').text(result.Adresse);
                    $('#telephone').text(result.Telephone);
                }
            })

            let maintenant = new Date();
            let annee = maintenant.getFullYear();
            let mois = String(maintenant.getMonth() + 1).padStart(2, '0');
            let jour = String(maintenant.getDate()).padStart(2, '0');

            let dateFormatee = `${annee}-${mois}-${jour}`;
            facstyle = `F${annee}-${mois}-${jour}-`+printId;
            $('#auday').text(dateFormatee);
            $('#factpaper').text(facstyle);

        }

        let generateButton = document.getElementById('generatePDF');
        generateButton.addEventListener('click',function(){

            let elements = document.querySelectorAll("#pdf");

            let container = document.createElement("div");
        
            for (let i = 0; i < elements.length; i++) {
              let element = elements[i];
        
              if (element.classList.contains("content")) {
                element.style.width = "85%";
              }
              container.appendChild(element.cloneNode(true));
            }
        
            let options = {
              filename: facstyle,
              image: { type: "jpeg", quality: 0.98 },
              html2canvas: { scale: 2 },
              jsPDF: { unit: "mm", format: "a4", orientation: "portrait" }
            };
        
            html2pdf().set(options).from(container).save();

            html2pdf().set(options).from(container).toPdf().get("pdf").then(function(pdf) {
                // Vérifier si l'imprimante est disponible
                if (typeof pdf.getPrintMode === "function" && pdf.getPrintMode().available) {
                  // Imprimer le PDF directement
                  pdf.autoPrint();
                } else {
                  // Ouvrir le PDF dans un nouvel onglet
                  window.open(pdf.output("bloburl"), "_blank");
                }
              });

            for (let f = 0; f < elements.length; f++) {
              let element = elements[f];
              let styleElement = document.createElement("style");
        
              if (element.classList.contains("content")) {
                element.style.width = "70%";
              }
              styleElement.textContent = '@media (max-width: 768px) { .content{width: 100%;}}';
              document.head.appendChild(styleElement);
            }
        })
          
        let factureButton = document.getElementById("rentrer");
        factureButton.addEventListener('click', function(){
          window.location.href = '../dashboard/facture.php';
        })

    });
});