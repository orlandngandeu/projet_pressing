<?php

session_start();
require_once '../php/model.php';

$db = new Database();

// creation des repassages
if(isset($_POST['action']) && $_POST['action'] === 'create')
{
  $rep = array("idcomm"=>"","idcommError"=>"","comments"=>"","success"=>false);

  $rep["idcomm"] = $db->verify_input($_POST["idcomm"]);
  $rep["comments"] = $db->verify_input($_POST["comrep"]);
  $rep["success"] = true;

  if(empty($rep["idcomm"]))
  {
    $rep["success"] = false;
    $rep["idcommError"] = "Veuillez entrer un nombre !";
  }else{
    $result = $db->valididcomm($rep["idcomm"]);
    if($result)
    {
      if($result["nombre_pieces"] != $result["pieces_lavee"])
      {
        $rep["success"] = false;
        $rep["idcommError"] = ($result["nombre_pieces"] - $result["pieces_lavee"])." pièces ne sont pas lavées !";
      }else{
        $lavprop = $db->areAllLavagesPropre($rep["idcomm"]);
        if($lavprop)
        {
          $sechage = $db->areAllsechageParfait($rep["idcomm"]);
          if($sechage)
          {
            if($result["statut"] != "repassage")
            {
              $rep["success"] = false;
              $rep["idcommError"] = "Cette commande n'est pas encore au repassage !";
            }
          }else{
            $rep["success"] = false;
            $rep["idcommError"] = "Verifie les sechages de cette commande certains vetements sont sales!";
          }
        }else{
          $rep["success"] = false;
          $rep["idcommError"] = "Verifie les lavages de cette commande certains vetements sont sales!";
        }
      }

      if($result["commentaire_employee"] == "repassage uniquement")
      {
        $rep["success"] = true;
        $db->update_status_commande($rep["idcomm"]);
      }
    }else
    {
      $rep["success"] = false;
      $rep["idcommError"] = "Cette commande n'existe pas !";
    }

  }

  if($rep["success"])
  {
    $timerepassage =  date('Y-m-d H:i:s');
    $db->save_repassage($rep["idcomm"],$timerepassage,$rep["comments"],$_SESSION['unique_id']);
  }

  echo json_encode($rep);


}



if (isset($_POST['action']) && $_POST['action'] ==='fetch')
{
  $bills = $db->read_repassage();
  $output = '';

  if($db->countbillrep()>0)
  {
    $output .= '
    <table class="table table-stripred">
    <thead>
     <tr>
      <th scope="col">Répassage N_</th>
      <th scope="col">commande N_</th>
      <th scope="col">temps début</th>
      <th scope="col">Commentaire</th>
      <th scope="col">Statut</th>
      <th scope="col">Action</th>
     </tr>
    </thead>
    <tbody>';
    foreach($bills as $bill)
    {
        $output .= "
        <tr>
        <th scope=\"row\">$bill->id_repassage</th>
        <td>$bill->id_commande</td>
        <td>$bill->temps_debut</td>
        <td>$bill->commentaires</td>
        <td>$bill->statut</td>
        <td class=\"action-cell\">
        <button class=\"action-button\">Opérations</button>
        <div class=\"action-list\">
          <a href=\"#\" class =\"text-info me-2 infoBtn\" title=\"infos répassage\" data-id=\"$bill->id_repassage\">
          <span class=\"material-symbols-outlined\">info</span>
          <p>Informations</p>
          <span>></span>
          </a>";

          if($bill->statut != "Terminée")
          {
            $output .= "
            <a href=\"#\" class =\"text-warning me-2 stop\" title=\"Stopper\" data-id=\"$bill->id_repassage\"> 
            <span class=\"material-symbols-outlined\">check_small</span>
            <p>Fin Répassage</p>
            <span>></span>
            </a>";
          }else{
            $output .= "<a href=\"#\" class =\"text-warning me-2 prop\" title=\"pièces\" data-id=\"$bill->id_repassage\">
            <span class=\"material-symbols-outlined\">cleaning_bucket</span>
            <p>pièces</p>
            <span>></span>
            </a>
            <a href=\"#\" class =\"text-success me-2 emballage\" title=\"Emballer\" data-id=\"$bill->id_repassage\"> 
            <span class=\"material-symbols-outlined\">masks</span>
            <p>Emballage</p>
            <span>></span>
            </a>";
          }

          $output .="
        </div>
      </td>
      </tr>
      ";
    }
    $output .="</tbody></table>";
    echo $output;
    }else{
    echo "<h3>Aucun repassage pour le moment </h3>";
  }
} 


if(isset($_POST['stopId']))
{
  $stopId = (int)$_POST['stopId'];
  $stoptime = date('Y-m-d H:i:s');
  $reponse = $db->stop_repassage($stopId,$stoptime);

  echo json_encode($reponse);
}

if(isset($_POST['choixId']))
{
  $resultat = array("pieceselevee"=>false,"piecesegale"=>false, "piecesinfer"=>false,"piecesnonrepasser"=>"");

  $choixId = (int)$_POST['choixId'];
  $propriete = (int)$db->verify_input($_POST['userChoice']);
  $pieces = $db->nombre_pieces_repasser($choixId);

  if($pieces["nombre_pieces"] < $propriete) {
    $resultat["pieceselevee"] = true; 
    $resultat["piecesnonrepasser"] = $pieces["nombre_pieces"];
  }else{
    if($pieces["nombre_pieces"] == $propriete)
    {
      $db->update_repassage_code($choixId,$propriete);
      $resultat["piecesegale"] = true;
    }else{
      if($pieces["nombre_pieces"] > $propriete)
      {
        $db->update_repassage_code($choixId,$propriete);
        $resultat["piecesinfer"] = true;
        $resultat["piecesnonrepasser"] = $pieces["nombre_pieces"] -  $propriete;
      }
    }
  }

  echo json_encode($resultat);

}

if(isset($_POST['informationId']))
{
  $infoId = (int)$_POST['informationId'];
  $infotab = $db->getsinglerep($infoId);
  echo json_encode($infotab);
}


//exporter un repassage

if(isset($_GET['action']) && $_GET['action'] === 'export')
{
    $excelFileName = "repassage".date('YmdHis').'.xls';
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$excelFileName");

    $columnName = ['identifiant', 'Heure debut', 'Heure fin','nom du client','identifiant_commande', 'commentaire', 'statut', 'pieces'];

    $data = implode("\t", array_values($columnName))."\n";
    if($db->countbillrep()>0)
    {
      $bills = $db->getrepassage();
      foreach($bills as $bill){
        $excelData = [$bill->id_repassage, $bill->temps_debut, $bill->temps_fin, $bill->nom_client,
        $bill->id_commande,$bill->commentaires, $bill->statut ,$bill->pieces];

        $data .= implode("\t", $excelData). "\n";
      }

    }else{
      $data = "Aucun repassage planifier .... " . "\n";
    }

    echo $data;
    die();
}




?>







