<?php

session_start();

require_once '../php/model.php';

$db = new Database();


if(isset($_POST["query"]))
{
    $query = $_POST['query'];
    $clients = $db->select_client($query);
    if ($clients) {
        foreach ($clients as $client) {
            echo '<li data-id="' . $client['id_client'] . '">' . $client['nom_client'] .' '.$client['surname_client']. '</li>';
        }
    } else {
        echo '<li class="invalid-name">Aucun client trouvé</li>';
    }
}

if(isset($_POST["qresp"]))
{
    $qresp = $_POST['qresp'];
    $respo = $db->select_respo($qresp);
    if ($respo) {
        foreach ($respo as $resp) {
            echo '<li data-id="' . $resp['id_responsable'] . '">' . $resp['nom_respo'] . ' '.$resp['surname_respo']. '</li>';
        }
    } else {
        echo '<li class="invalid-name">Aucun responsable trouvé</li>';
    }
}


// creation des commandes
if(isset($_POST['action']) && $_POST['action'] === 'create')
{
    $comm = array("clientSearch"=>"","clientList"=>"","respoList"=>"","clientListerror"=>"","respoListerror"=>"","clientId"=>"",
    "respoSearch"=>"","nbrepcserror"=>"","respoId"=>"","nbrepcs"=>"","comments"=>"","status"=>"","ensuccess"=>false);

    $comm["clientSearch"] = $db->verify_input($_POST["clientSearch"]);
    $comm["clientId"] = $db->verify_input($_POST["clientId"]);
    $comm["respoSearch"] = $db->verify_input($_POST["respoSearch"]);
    $comm["respoId"] = $db->verify_input($_POST["respoId"]);
    $comm["nbrepcs"] = $db->verify_input($_POST["nbrepcs"]);
    $comm["comments"] = $db->verify_input($_POST["comments"]);
    $comm["status"] = $db->verify_input($_POST["status"]);
    $comm["ensuccess"] = true;

    $a =$db->SELECT_RESP($comm["respoSearch"]);
    $b =$db->SELECT_CL($comm["clientSearch"]);

    if(empty($comm["clientSearch"]) || empty($comm["clientId"]) || !$b)
    {
        $comm["ensuccess"] = false;
        $comm["clientListerror"] = "Veuilez entrer un client !";
    }
    if(empty($comm["respoSearch"]) || empty($comm["respoId"]) || !$a)
    {
        $comm["ensuccess"] = false;
        $comm["respoListerror"] = "Veuilez entrer un responsable !";
    }

    if($comm["nbrepcs"]<0)
    {
        $comm["ensuccess"] = false;
        $comm["nbrepcserror"] = "Attention amigo positif!";
    }

    if(empty($comm["nbrepcs"]))
    {
        $comm["ensuccess"] = false;
        $comm["nbrepcserror"] = "Veuillez entrer un nombre !";
    }
    
    if($comm["ensuccess"] && $_SESSION['unique_id'])
    {
        $db ->enreg_commande($comm["clientId"],$comm["respoId"] , $comm["nbrepcs"],$comm["status"],$comm["comments"],$_SESSION['unique_id']);
        $nom = $db->nom_prenom($comm["clientId"]);
        $action = "une nouvelle commande  a été créée, sous le nom du  client ".$nom["nom_client"]." ".$nom["surname_client"];
        $db->enregistrerJournal($_SESSION["unique_id"],$action);
    }
    
    echo json_encode($comm);
    
}


//recuperation des commandes



if (isset($_POST['action']) && $_POST['action'] ==='fetch')
{
  $bills = $db->read_commande();
  $output = '';

   if($db->countBillcom()>0)
   {
    $output .= '
    <table class="table table-stripred">
    <thead>
     <tr>
      <th scope="col">Numéro</th>
      <th scope="col">Nom_client</th>
      <th scope="col">Prénom_client</th>
      <th scope="col">Nom_respo</th>
      <th scope="col">pièces</th>
      <th scope="col">Date</th>
      <th scope="col">Date_livraison</th>
      <th scope="col">Observations</th>
      <th scope="col">Statut</th>
      <th scope="col">Action</th>
     </tr>
    </thead>
    <tbody>';
    foreach($bills as $bill)
    {
        $output .= "
        <tr>
        <th scope=\"row\">$bill->id_commande</th>
        <td>$bill->nom_client</td>
        <td>$bill->surname_client</td>
        <td>$bill->nom_respo</td>
        <td>$bill->nombre_pieces</td>
        <td>$bill->Entry_date</td>
        <td>$bill->date_livraison</td>
        <td>$bill->commentaire_employee</td>
        <td>$bill->statut</td>
        <td class=\"action-cell\">
        <button class=\"action-button\">Opérations</button>
        <div class=\"action-list\">
          <a href=\"#\" class =\"text-info me-2 infoBtn\" title=\"voir infos\" data-id=\"$bill->id_commande\">
          <span class=\"material-symbols-outlined\">info</span>
          <p>Informations</p>
          <span>></span>
          </a>";

          $champ = $db->getsinglecomm($bill->id_commande);
          $position = $db->getposition($_SESSION['unique_id']);
          if(($_SESSION['unique_id'] == $champ->unique_id) || ($position->position == 'Admin'))
          {
            $output .= "
            <a href=\"#\" class =\"text-primary me-2 editBtn\" title=\"modifier\" data-id=\"$bill->id_commande\" data-bs-toggle='modal' 
          data-bs-target='#updateModal'><span class=\"material-symbols-outlined\">update</span>
          <p>Modifier</p>
          <span>></span>
          </a>

          <a href=\"#\" class =\"text-danger me-2 deleteBtn\" title=\"supprimer\" data-id=\"$bill->id_commande\">
          <span class=\"material-symbols-outlined\">delete</span>
          <p>Supprimer</p>
          <span>></span>
          </a>";
          }

          $output .="
          <a href=\"#\" class =\"text-warning me-2 listingBtn\" title=\"Nouveau listing\" data-id=\"$bill->id_commande\">
          <span class=\"material-symbols-outlined\">contract</span>
          <p>Listing</p>
          <span>></span>
          </a>

          <a href=\"#\" style=\"color: green;\" class =\"me-2 factureBtn\" title=\"Nouvelle facture\" data-id=\"$bill->id_commande\">
          <span class=\"material-symbols-outlined\">scan</span>
          <p>Facture</p>
          <span>></span>
          </a>

        </div>
      </td>
      </tr>
      ";
    }
    $output .="</tbody></table>";
    echo $output;
    }else{
      echo "<h3>Aucune Commande  pour le moment </h3>";
  }
} 


//infos pour details de la commande 


if(isset($_POST['workingId']))
{
  $workingId = (int)$_POST['workingId'];
  echo json_encode($db->getsinglecomm($workingId));
}


//update de la commande

function isDateValid($inputDate) {
    $formattedDate = date('Y-m-d', strtotime($inputDate));
    return ($formattedDate == $inputDate);
}

function isHeureValide($time) {
    $pattern = '/^(?:[01]\d|2[0-3]):(?:[0-5]\d):(?:[0-5]\d)$/';
    return preg_match($pattern, $time);
}



if(isset($_POST['action']) && $_POST['action'] === 'update')
{

    $tab = array("commUPId"=>"","clientUPSearch"=>"","clientUPId"=>"","respoUPSearch"=>"","respoUPId"=>"",
    "nbreUPpcs"=>"","commentsUP"=>"","EntryUPdate"=>"","EntryUPhour"=>"","dateUPlivraison"=>"","dateUPlivraisonerror"=>"",
    "statusUP"=>"","success"=>false,"nbreUPpcserror"=>"","dateheureerror"=>"");

    $tab["commUPId"] = $db->verify_input($_POST["commUPId"]);
    $tab["clientUPSearch"] = $db->verify_input($_POST["clientUPSearch"]);
    $tab["clientUPId"] = $db->verify_input($_POST["clientUPId"]);
    $tab["respoUPSearch"] = $db->verify_input($_POST["respoUPSearch"]);
    $tab["respoUPId"] = $db->verify_input($_POST["respoUPId"]);
    $tab["nbreUPpcs"] = $db->verify_input($_POST["nbreUPpcs"]);
    $tab["commentsUP"] = $db->verify_input($_POST["commentsUP"]);
    $tab["EntryUPdate"] = $db->verify_input($_POST["EntryUPdate"]);
    $tab["EntryUPhour"] = $db->verify_input($_POST["EntryUPhour"]);
    $tab["dateUPlivraison"] = $db->verify_input($_POST["dateUPlivraison"]);
    $tab["statusUP"] = $db->verify_input($_POST["statusUP"]);
    $tab["success"] = true;

    if($tab["nbreUPpcs"]<0){
        $tab["success"] = false;
        $tab["nbreUPpcserror"] = "un nombre strictement positif !";
    }
    if(empty($tab["nbreUPpcs"])){
        $tab["success"] = false;
        $tab["nbreUPpcserror"] = "Veuillez entrer un nombre !";
    }
    if(!isDateValid($tab["EntryUPdate"]) || !isDateValid($tab["dateUPlivraison"]) || !isHeureValide($tab["EntryUPhour"]))
    {
        $tab["success"] = false;
        $tab["dateheureerror"] = "une date ou heure valide !";
    }
    if($tab["dateUPlivraison"] <= $tab["EntryUPdate"])
    {
        $tab["success"] = false;
        $tab["dateUPlivraisonerror"] = "Impossible de livrer a cette date !";

    }

    if($tab["success"]){
        $db ->update_commande($tab["commUPId"],$tab["clientUPId"],$tab["respoUPId"],$tab["nbreUPpcs"],
        $tab["EntryUPdate"],$tab["EntryUPhour"],$tab["statusUP"], $tab["dateUPlivraison"],  $tab["commentsUP"]);

        $nomUP = $db->nom_prenom($tab["clientUPId"]);
        $action = "La commande d'identifiant ".$tab["commUPId"]. "  a été modifiée, elle correspond au client ".$nomUP["nom_client"]." ".$nomUP["surname_client"];
        $db->enregistrerJournal($_SESSION["unique_id"],$action);
    }

    echo json_encode($tab);


}

if(isset($_POST['informationId']))
{
  $infoId = (int)$_POST['informationId'];
  echo json_encode($db->getsinglecomm($infoId));
}


//suppression d'une commande

if(isset($_POST['deleteId']))
{
  $deleteId = (int)$_POST['deleteId'];
  $verif = $db->avoir_listing($deleteId);
  if($verif){
    echo false;
  }else{
    echo $db->delete_commande($deleteId);
    $action = " un commande a été supprimé ";
    $db->enregistrerJournal($_SESSION["unique_id"],$action);
  }
}

//exporter une commande

if(isset($_GET['action']) && $_GET['action'] === 'export')
{
    $excelFileName = "commandes".date('YmdHis').'.xls';
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$excelFileName");

    $columnName = ['identifiant', 'nom du client', 'prenom du client', 'Nom du responsable', 'Nombres de pièces', 'Date d\'entrée', 'Heure d\'entrée', 'Date de livraison', 'commentaire', 'statut'];

    $data = implode("\t", array_values($columnName))."\n";
    if($db->countBillcom()>0)
    {
        $bills = $db->read_commande();

        $action = "Les informations des  commandes  ont été exportées au format excel ";
        $db->enregistrerJournal($_SESSION["unique_id"],$action);


        foreach($bills as $bill){
            $excelData = [$bill->id_commande, $bill->nom_client, $bill->surname_client, $bill->nom_respo, 
            $bill->nombre_pieces ,$bill->Entry_date, $bill->Entry_hour, $bill->date_livraison,
            $bill->commentaire_employee, $bill->statut];

            $data .= implode("\t", $excelData). "\n";
        }

    }else{
        $data = "Aucune commande .... " . "\n";
    }

    echo $data;
    die();
}





?>

