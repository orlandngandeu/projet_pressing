<?php

session_start();
require_once '../php/model.php';

$db = new Database();


// creation des sechages
if(isset($_POST['action']) && $_POST['action'] === 'create')
{
    $sec = array("id_lavage"=>"","id_lavageError"=>"","comments"=>"","typeSec"=>"","typeSecError"=>"",
    "success"=>false,"proprete"=>false);

    $sec["id_lavage"] = $db->verify_input($_POST["idLav"]);
    $sec["comments"] = $db->verify_input($_POST["comsec"]);
    $sec["typeSec"] = $db->verify_input($_POST["sechage"]);
    $sec["success"] = true;
    $sec["proprete"] = false;

    if(empty($sec["typeSec"]))
    {
        $sec["success"] = false;
        $sec["typeSecError"] = "Veuillez entrer le mode de séchage !";
    }

    if(empty($sec["id_lavage"]))
    {
        $sec["success"] = false;
        $sec["id_lavageError"] = "Veuillez entrer le lavage!";
    }else{
        $isPRET = $db->verifLavage($sec["id_lavage"]);
        if(!$isPRET)
        {
            $sec["success"] = false;
            $sec["id_lavageError"] = "Soit il n'existe pas,ou il n'est pas Terminée ou son état de Propriété !";
        }else{
            if($isPRET["proprete"] == "sale")
            {
                $sec["proprete"] = true;
            }
            
        }
    }

    if($sec["success"])
    {
      $tempsSec = date('Y-m-d H:i:s');
      $db->insert_sechage($sec["id_lavage"],$tempsSec,$sec["comments"],"En cours",$sec["typeSec"],
      $_SESSION['unique_id']);
      $db->sechage_statut($sec["id_lavage"]);
    }

    echo json_encode($sec);
}


if (isset($_POST['action']) && $_POST['action'] ==='fetch')
{
  $bills = $db->read_sechage();
  $output = '';

   if($db->countBillsec()>0)
   {
    $output .= '
    <table class="table table-stripred">
    <thead>
     <tr>
      <th scope="col">Séchage N_</th>
      <th scope="col">N_lavage</th>
      <th scope="col">temps début</th>
      <th scope="col">Commentaire</th>
      <th scope="col">Type sechage</th>
      <th scope="col">Statut</th>
      <th scope="col">Action</th>
     </tr>
    </thead>
    <tbody>';
    foreach($bills as $bill)
    {
        $output .= "
        <tr>
        <th scope=\"row\">$bill->id_sechage</th>
        <td>$bill->id_lavage</td>
        <td>$bill->temps_debut</td>
        <td>$bill->commentaires</td>
        <td>$bill->type_sechage</td>
        <td>$bill->statut</td>
        <td class=\"action-cell\">
        <button class=\"action-button\">Opérations</button>
        <div class=\"action-list\">
          <a href=\"#\" class =\"text-info me-2 infoBtn\" title=\"infos séchage\" data-id=\"$bill->id_sechage\">
          <span class=\"material-symbols-outlined\">info</span>
          <p>Informations</p>
          <span>></span>
          </a>";

          if($bill->statut != "Terminée")
          {
            $output .= "
            <a href=\"#\" class =\"text-warning me-2 stop\" title=\"Stopper\" data-id=\"$bill->id_sechage\"> 
            <span class=\"material-symbols-outlined\">check_small</span>
            <p>Fin séchage</p>
            <span>></span>
            </a>";
          }else{
            $output .= "<a href=\"#\" class =\"text-warning me-2 prop\" title=\"Propriété\" data-id=\"$bill->id_sechage\"> 
            <span class=\"material-symbols-outlined\">cleaning_bucket</span>
            <p>Propriété</p>
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
    echo "<h3>Aucun séchage pour le moment </h3>";
  }
} 



if(isset($_POST['stopId']))
{
  $stopId = (int)$_POST['stopId'];
  $stoptime = date('Y-m-d H:i:s');
  $reponse = $db->stop_sechage($stopId,$stoptime);

  echo json_encode($reponse);
}

if(isset($_POST['choixId']))
{
  $choixId = (int)$_POST['choixId'];
  $propriete = $db->verify_input($_POST['userChoice']);

  $reponse = $db->update_choixsec($choixId,$propriete);
  echo json_encode($reponse);

}

if(isset($_POST['informationId']))
{
  $infoId = (int)$_POST['informationId'];
  $infotab = $db->getsinglesec($infoId);
  echo json_encode($infotab);
}


//exporter un sechement

if(isset($_GET['action']) && $_GET['action'] === 'export')
{
  $excelFileName = "sechages".date('YmdHis').'.xls';
  header("Content-Type: application/vnd.ms-excel");
  header("Content-Disposition: attachment; filename=$excelFileName");

  $columnName = ['identifiant','id_lavage', 'Heure debut', 'Heure fin', 'commentaires', 'statut', 'type sechage', 'etat sechement'];

  $data = implode("\t", array_values($columnName))."\n";
  if($db->countBillsec()>0)
  {
    $bills = $db->read_sechage();
    foreach($bills as $bill){
      $excelData = [$bill->id_sechage, $bill->id_lavage, $bill->temps_debut, $bill->temps_fin, $bill->commentaires, 
      $bill->statut ,$bill->type_sechage, $bill->etat_sechement];

      $data .= implode("\t", $excelData). "\n";
    }

  }else{
    $data = "Aucun sechage planifier .... " . "\n";
  }

  echo $data;
  die();
}

if(isset($_POST['repId']))
{
  $repId = (int)$_POST['repId'];
  $reponse = $db->verifLavage($secId);
  if($reponse)
  {
    echo json_encode($reponse);
  }else{
    echo json_encode(false);
  }
 

}

?>