<?php
 session_start();
 if(!isset($_SESSION['unique_id'])){
    header("location: ../employee/connexion.php");
 } 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/fact.css">
    <title>ORLXIO PDF</title>
</head>
<body>

    <div class="contenu">

     <div class="barre" id="pdf"></div>

      <div class="content" id="pdf">

        <div class="heading">
            <h1>FACTURE</h1>
          <div class="leftdate">
            <div><p>DATE:</p><span id="auday"></span></div>
            <div><p>N°:</p><span id="factpaper"></span></div>
          </div>
          
        </div>

        <div class="title-heading">
          <div class="infoheading">
            <h1>CCS PRESSING</h1>
            <p>Douala-CAMEROUN</p>
            <P>697-48-70-98 / 651-38-96-96</P>
          </div>

          <div class="clientinfo">
            <h1 id="nomclient"></h1>
            <p id="adresse"></p>
            <span id="telephone"></span>
          </div>

        </div>

        <div id="corpspdf"></div>

        <div class="avis">
          <p>Des questions ?<br>
            Envoyer-nous un mail au CathyCleanServices@gmail.com<br>
            Ou contacter nous au 697-48-70-98 / 651-38-96-96<br>
            Nous vous remercions pour votre confiance<br>
          </p>
        </div>
        <div class="border"></div>

        <div class="conclusion">
          <p>DOUALA-CAMEROUN</p>
          <P>Avec nous tout redevient neuf ...</p>
        </div>



      </div>

      <div class="farre"></div>

      <div class="bouton">
        <button id="generatePDF">Télécharger le PDF</button>
        <button>Envoyer le PDF</button>
        <button id="rentrer">FACTURE</button>
      </div>



    </div>
  </body>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/v/bs5/dt-1.13.4/datatables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../../js/pdf.js"></script>
</html>