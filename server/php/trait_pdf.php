<?php 

require_once './model.php';

$db = new Database();


if(isset($_POST['printId']))
{
 $idfact = (int)$_POST['printId'];
 echo json_encode($db->infoclient($idfact));
}


if(isset($_POST['paramd']))
{
    $infos = $db->retrieveinfos($_POST['paramd']);
    $counter = 0;
    $amount = 0;
    $remise = $db->getremise($_POST['paramd']);
    $pieces = $db->numberpieces($_POST['paramd']);
    $retrieve = '';
  
     if($db->countlistings($_POST['paramd'])>0)
     {
      $retrieve .= '
      <table class="liste">
      <thead>
     <tr>
     <th scope="col">Désignation</th>
     <th scope="col">Quantité</th>
     <th scope="col">Prix</th>
     <th scope="col">Prix Total</th>
     </tr>
     </thead>
     <tbody>';
      foreach($infos as $bill)
      {
        $class = ($counter % 2 == 0) ? "listone" : "listwo";
        $retrieve .= "
        <tr class=\"$class\">
        <th scope=\"row\">$bill->Listing</th>
        <td>$bill->quantity</td>
        <td>$bill->prix_unitaire<span>FCFA</span></td>
        <td>$bill->montant<span>FCFA</span></td>
        </tr>
        ";
        $counter++;
        $amount = $amount + $bill->montant;
      }

        $retrieve .="
        <tr class=\"listfin\">
        <th scope=\"row\">Nombre de pièces :<span>$pieces<span>pièces.</span></span></th>
        </tr>
        <tr class=\"listone\">
        <th scope=\"row\"></th>
        <td></td>
        <td>Montant total:</td>
        <td>$amount<span>FCFA</span></td>
        </tr>";
        if($remise!=0){
            $retrieve .="
            <tr class=\"listone\">
            <th scope=\"row\"></th>
            <td></td>
            <td>Remise pour fidélité</td>
            <td>$remise<span>%</span></td>
            </tr>";
        }
        $retrieve .="
        <tr class=\"netpayer\">
        <th scope=\"row\"></th>
        <td></td>
        <td>PRIX A PAYER :</td>
        <td>".($amount/100)*(100-$remise)."<span>FCFA</span></td>
        </tr></tbody></table>";

      echo $retrieve;

      }else{
        echo "<h3>Aucune Information  pour le moment </h3>";
    }
}

?>