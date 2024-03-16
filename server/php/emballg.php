<?php

session_start();
require_once '../php/model.php';

$db = new Database();

// creation des emballages
if(isset($_POST['action']) && $_POST['action'] === 'create')
{
  $emb = array("idrep"=>"","idrepError"=>"","comments"=>"","success"=>false);

  $emb["idrep"] = $db->verify_input($_POST["id_rep"]);
  $emb["comments"] = $db->verify_input($_POST["comEmb"]);
  $emb["success"] = true;

  if(empty($emb["idrep"]))
  {
    $emb["success"] = false;
    $emb["idrepError"] = "Veuillez entrer un nombre positif";
  }else{
    $result = $db->valididrep($emb["idrep"]);
    if($result)
    {
      $piecesInitiaux = $db->valididcomm($result["id_commande"]);
      if($result["pieces"] != $piecesInitiaux["nombre_pieces"])
      {
        $emb["success"] = false;
        $emb["idrepError"] = "".abs($result["pieces"] - $piecesInitiaux["nombre_pieces"]) . " pièces ne sont pas encore repassées !";
      }else{
        $db->update_commande_emballage($result["id_commande"]);
      }
    }else{
        $emb["success"] = false;
        $emb["idrepError"] = "Ce repassage n'existe pas !";
    }
  }

  if($emb["success"])
  {
    $timeemballage =  date('Y-m-d H:i:s');
    $db->save_emballage($emb["idrep"], $timeemballage,$emb["comments"],$_SESSION['unique_id']);
    $vnom = $db->select_nom_employee($_SESSION['unique_id']);
    $action = " L'emballage a  ete planifie par ".$vnom["nomComplet_employé"];
    $db->enregistrerJournal($_SESSION["unique_id"],$action);
  }

  echo json_encode($emb);


}





if (isset($_POST['action']) && $_POST['action'] ==='fetch')
{
  $bills = $db->read_emballage();
  $output = '';

  if($db->countBillemballage()>0)
  {
    $output .= '
    <table class="table table-stripred">
    <thead>
     <tr>
      <th scope="col">emballage N_</th>
      <th scope="col">RépassageN_</th>
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
        <th scope=\"row\">$bill->id_emballage</th>
        <td>$bill->id_repassage</td>
        <td>$bill->date_debut</td>
        <td>$bill->commentaires</td>
        <td>$bill->statut</td>
        <td class=\"action-cell\">
        <button class=\"action-button\">Opérations</button>
        <div class=\"action-list\">
          <a href=\"#\" class =\"text-info me-2 infoBtn\" title=\"infos emballage\" data-id=\"$bill->id_emballage\">
          <span class=\"material-symbols-outlined\">info</span>
          <p>Informations</p>
          <span>></span>
          </a>
          
          <a href=\"#\" class =\"text-primary me-2 editBtn\" title=\"modifier plastiques\" data-id=\"$bill->id_emballage\" data-bs-toggle='modal' 
          data-bs-target='#updateModal'>
          <span class=\"material-symbols-outlined\">update</span>
          <p>Modifier</p>
          <span>></span>
          </a>";

          if($bill->statut != "Terminée")
          {
            $output .= "
            <a href=\"#\" class =\"text-warning me-2 stop\" title=\"Stopper\" data-id=\"$bill->id_emballage\"> 
            <span class=\"material-symbols-outlined\">check_small</span>
            <p>Fin emballage</p>
            <span>></span>
            </a>";
          }else{
            $output .= "<a href=\"#\" class =\"text-success me-2 livraison\" title=\"livraison\" data-id=\"$bill->id_emballage\"> 
            <span class=\"material-symbols-outlined\">history_toggle_off</span>
            <p>Planifier livraison</p>
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

//infos pour details de l'emballage 


if(isset($_POST['workingId']))
{
  $workingId = (int)$_POST['workingId'];
  echo json_encode($db->getSinglEemballage($workingId));
}


if(isset($_POST['stopId']))
{
  $stopId = (int)$_POST['stopId'];
  $stoptime = date('Y-m-d H:i:s');
  $reponse = $db->stop_emballage($stopId,$stoptime);

  echo json_encode($reponse);
}



if(isset($_POST['informationId']))
{
  $infoId = (int)$_POST['informationId'];
  $infotab = $db->getSingleempq($infoId);
  echo json_encode($infotab);
}


//exporter un emballage

if(isset($_GET['action']) && $_GET['action'] === 'export')
{
    $excelFileName = "emballage".date('YmdHis').'.xls';
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$excelFileName");

    $columnName = ['identifiant','identifiant_repassage', 'Heure debut', 'Heure fin', 'commentaire', 'statut'];

    $action = "La liste des emballages a ete exporte au format EXcel ";
    $db->enregistrerJournal($_SESSION["unique_id"],$action);

    $data = implode("\t", array_values($columnName))."\n";
    if($db->countBillemballage()>0)
    {
      $bills = $db->read_emballage();
      foreach($bills as $bill){
        $excelData = [$bill->id_emballage, $bill->id_repassage, $bill->date_debut, $bill->date_fin,
        $bill->commentaires,$bill->statut];

        $data .= implode("\t", $excelData). "\n";
      }

    }else{
      $data = "Aucun emballage planifier .... " . "\n";
    }

    echo $data;
    die();
}


if(isset($_POST['livraisonId']))
{
  $livId = (int)$_POST['livraisonId'];
  $id_comm = $db->getcommandeempaquettage($livId);

  echo json_encode($id_comm);
}



//update des clients

if(isset($_POST['action']) && $_POST['action'] === 'update')
{
  $tab = array("id_emballage"=>"","classiq_petit"=>"","id_repass"=>"","classiq_moyen"=>"","prestige_petit"=>"",
  "prestige_moyen"=>"","emballage_petit"=>"","emballage_grand"=>"","veste"=>"","success"=>false);

  $tab["id_emballage"] = $db->verify_input($_POST["id_emballage"]);
  $tab["id_repass"] = $db->verify_input($_POST["id_repass"]);
  $tab["classiq_petit"] = $db->verify_input($_POST["classiq_petit"]);
  $tab["classiq_moyen"] = $db->verify_input($_POST["classiq_moyen"]);
  $tab["prestige_petit"] = $db->verify_input($_POST["prestige_petit"]);
  $tab["prestige_moyen"] = $db->verify_input($_POST["prestige_moyen"]);
  $tab["emballage_petit"] = $db->verify_input($_POST["emballage_petit"]);
  $tab["emballage_grand"] = $db->verify_input($_POST["emballage_grand"]);
  $tab["veste"] = $db->verify_input($_POST["veste"]);
  $tab["success"] = true;

  if (
    !isset($tab["id_emballage"]) || $tab["id_emballage"] === "" ||
    !isset($tab["id_repass"]) || $tab["id_repass"] === "" ||
    !isset($tab["classiq_petit"]) || $tab["classiq_petit"] === "" ||
    !isset($tab["classiq_moyen"]) || $tab["classiq_moyen"] === "" ||
    !isset($tab["prestige_petit"]) || $tab["prestige_petit"] === "" ||
    !isset($tab["prestige_moyen"]) || $tab["prestige_moyen"] === "" ||
    !isset($tab["emballage_petit"]) || $tab["emballage_petit"] === "" ||
    !isset($tab["emballage_grand"]) || $tab["emballage_grand"] === "" ||
    !isset($tab["veste"]) || $tab["veste"] === ""
  ) 
  {
    $tab["success"] = false;
  }

  if($tab["success"])
  {
    $good = $db ->update_emballage($tab["id_emballage"],$tab["classiq_petit"],$tab["classiq_moyen"], $tab["prestige_petit"],
    $tab["prestige_moyen"],$tab["emballage_petit"],$tab["emballage_grand"],$tab["veste"]); 
    if($good)
    {
      $action = "L'emballage N_ ".$tab["id_emballage"]." pour le repassage N_ ".$tab["id_repass"]." a été mis a jour ";
      $db->enregistrerJournal($_SESSION["unique_id"],$action);
    }
  
  }

  echo json_encode($tab);


}