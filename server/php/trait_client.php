<?php 
session_start();
require_once '../php/model.php';

$db =  new Database();

function is_phone($val)
{
  return preg_match('/^[0-9\+\-]+$/', $val);
}

function is_email($val)
{
  return filter_var($val, FILTER_VALIDATE_EMAIL);
}


//creation des clients

$tabcl = array("namecustomer"=>"","surnamecustomer"=>"","adresse"=>"","email"=>"","telephone"=>"","sexe"=>"","ensuccess"=>false,
"errornamecustomer"=>"","errorsurnamecustomer"=>"","erroradresse"=>"","erroremail"=>"","errortelephone"=>"");
$tab = array("id"=>"","nameUPcustomer"=>"","surnameUPcustomer"=>"","adresseUP"=>"","emailUP"=>"","telephoneUP"=>"","sexeUP"=>"","success"=>false,
"nameUPcustomererror"=>"","surnameUPcustomererror"=>"","adresseUPerror"=>"","emailUPerror"=>"","telephoneUPerror"=>"");


if(isset($_POST['action']) && $_POST['action'] === 'create')
{
  $tabcl["namecustomer"] = $db->verify_input($_POST["namecustomer"]);
  $tabcl["surnamecustomer"] = $db->verify_input($_POST["surnamecustomer"]);
  $tabcl["adresse"] = $db->verify_input($_POST["adresse"]);
  $tabcl["email"] = $db->verify_input($_POST["email"]);
  $tabcl["telephone"] = $db->verify_input($_POST["telephone"]);
  $tabcl["sexe"] = $db->verify_input($_POST["sexe"]);
  $tabcl["ensuccess"] = true;

  if(empty($tabcl["surnamecustomer"]))
  {
    $tabcl["ensuccess"] = false;
    $tabcl["errorsurnamecustomer"] = "Veuillez remplir un prénom !!";
  } 
  if(empty($tabcl["namecustomer"]))
  {
    $tabcl["ensuccess"] = false;
    $tabcl["errornamecustomer"] = "Veuillez remplir un nom !!";
  } 
  if(empty($tabcl["adresse"]))
  {
    $tabcl["ensuccess"] = false;
    $tabcl["erroradresse"] = "Veuillez remplir une adresse !!";
  }
  if(!is_phone($tabcl["telephone"]))
  {
    $tabcl["ensuccess"] = false;
    $tabcl["errortelephone"] = "Veuillez remplir un téléphone valide !!";
  }
  if($tabcl['email'] != '')
  {
    if(!is_email($tabcl["email"])){
      $tabcl["ensuccess"] = false; $tabcl["erroremail"] = "Veuillez remplir un mail valide !!";}
    else{
      if($tabcl["ensuccess"] && isset($_SESSION['unique_id']))
      {
        $db ->enregistrer($tabcl["namecustomer"],$tabcl["surnamecustomer"],$tabcl["adresse"],$tabcl["telephone"],
        $tabcl["email"],$tabcl["sexe"],$_SESSION['unique_id']);
        $action = "Le client ".$tabcl["namecustomer"]." ".$tabcl["surnamecustomer"]." vient d'etre enrégistré Toutes les informations y sont meme l'email";
        $db->enregistrerJournal($_SESSION["unique_id"],$action);
      }   
    }
  }else{
    if($tabcl["ensuccess"] && isset($_SESSION['unique_id'])){
      $db ->enregistrerwithoutemail($tabcl["namecustomer"],$tabcl["surnamecustomer"],$tabcl["adresse"],$tabcl["telephone"],
      $tabcl["sexe"],$_SESSION['unique_id']); 
      $action = "Le client ".$tabcl["namecustomer"]." ".$tabcl["surnamecustomer"]." vient d'etre enrégistré sans email";
      $db->enregistrerJournal($_SESSION["unique_id"],$action);
    }
  }

  echo json_encode($tabcl);

}

//recuperation des factures



if (isset($_POST['action']) && $_POST['action'] ==='fetch')
{
  $bills = $db->read_client();
  $output = '';

  if($db->countBills()>0){
    $output .= '
    <table class="table table-stripred">
    <thead>
     <tr>
      <th scope="col">N_</th>
      <th scope="col">Nom</th>
      <th scope="col">Prénom</th>
      <th scope="col">Adresse</th>
      <th scope="col">Téléphone</th>
      <th scope="col">Email</th>
      <th scope="col">sexe</th>
      <th scope="col">Action</th>
     </tr>
    </thead>
    <tbody>';
    foreach($bills as $bill)
    {
        $output .= "
        <tr>
        <th scope=\"row\">$bill->id_client</th>
        <td>$bill->nom_client</td>
        <td>$bill->surname_client</td>
        <td>$bill->Adresse</td>
        <td>$bill->Telephone</td>
        <td>$bill->Email</td>
        <td>$bill->sexe</td>
        <td class=\"action-cell\">
        <button class=\"action-button\">Opérations</button>
        <div class=\"action-list\">
        <a href=\"#\" class =\"text-info me-2 infoBtn\" title=\"voir infos\" data-id=\"$bill->id_client\"><span class=\"material-symbols-outlined\">info</span>
        <p>Informations</p>
        <span>></span>
        </a>";

        $champ = $db->getSingleBill($bill->id_client);
        $position = $db->getposition($_SESSION['unique_id']);
        if(($_SESSION['unique_id'] == $champ->unique_id) || ($position->position == 'Admin'))
        {
          $output .="    <a href=\"#\" class =\"text-primary me-2 editBtn\" title=\"modifier\" data-id=\"$bill->id_client\" data-bs-toggle='modal' 
          data-bs-target='#updateModal'>
          <span class=\"material-symbols-outlined\">update</span>
          <p>Modifier</p>
          <span>></span>
          </a>
          <a href=\"#\" class =\"text-danger me-2 deleteBtn\" title=\"supprimer\" data-id=\"$bill->id_client\"><span class=\"material-symbols-outlined\">delete</span>
          <p>Supprimer</p>
          <span>></span>
          </a>";
        }

        $output .="
        <a href=\"#\" class =\"text-warning me-2 commandBtn\" title=\"New command\" data-id=\"$bill->id_client\"><span class=\"material-symbols-outlined\">shopping_cart</span>
        <p>Commande</p>
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
    echo "<h3>Aucun client  pour le moment </h3>";
  }
} 



//infos pour details du client 


if(isset($_POST['workingId']))
{
  $workingId = (int)$_POST['workingId'];
  echo json_encode($db->getSingleBill($workingId));
}



//update des clients

if(isset($_POST['action']) && $_POST['action'] === 'update')
{

    $tab["nameUPcustomer"] = $db->verify_input($_POST["nameUPcustomer"]);
    $tab["surnameUPcustomer"] = $db->verify_input($_POST["surnameUPcustomer"]);
    $tab["adresseUP"] = $db->verify_input($_POST["adresseUP"]);
    $tab["emailUP"] = $db->verify_input($_POST["emailUP"]);
    $tab["telephoneUP"] = $db->verify_input($_POST["telephoneUP"]);
    $tab["sexeUP"] = $db->verify_input($_POST["sexeUP"]);
    $tab["id"] = $db->verify_input($_POST["id"]);
    $tab["success"] = true;

    if(empty($tab["nameUPcustomer"]))
    {
      $tab["nameUPcustomererror"] = "Veuillez remplir le nom !!";
      $tab["success"] = false;
    }

    if(empty($tab["surnameUPcustomer"]))
    {
      $tab["surnameUPcustomererror"] = "Veuillez remplir le prénom !!";
      $tab["success"] = false;
    }

    if(empty($tab["adresseUP"]))
    {
      $tab["adresseUPerror"] = "Veuillez remplir l'adresse !!";
      $tab["success"] = false;
    }
    if(!is_phone($tab["telephoneUP"]))
    {
      $tab["telephoneUPerror"] = "Un numéro valide !! !!";
      $tab["success"] = false;
    }

    if($tab['emailUP'] != '')
    {
      if(!is_email($tab["emailUP"]))
      {
        $tab["emailUPerror"] = "Un mail valide !! !!";
        $tab["success"] = false;
      }
      else{
        if($tab["success"]){
          $db ->update_client($tab["id"],$tab["nameUPcustomer"],$tab["surnameUPcustomer"],$tab["adresseUP"],
          $tab["telephoneUP"],$tab["emailUP"],$tab["sexeUP"]); 
          $action = "Les informations du  client ".$tab["nameUPcustomer"]." ".$tab["surnameUPcustomer"]." ont été modifié ";
          $db->enregistrerJournal($_SESSION["unique_id"],$action);
        }
      }
    }else{
        if($tab["success"])
        {
          $db ->update_client($tab["id"],$tab["nameUPcustomer"],$tab["surnameUPcustomer"],$tab["adresseUP"],
          $tab["telephoneUP"],$tab["emailUP"],$tab["sexeUP"]); 
          $action = "Les informations du  client ".$tab["nameUPcustomer"]." ".$tab["surnameUPcustomer"]." ont été modifié ";
          $db->enregistrerJournal($_SESSION["unique_id"],$action);
        }
      }
 

  echo json_encode($tab);


}


if(isset($_POST['informationId']))
{
  $infoId = (int)$_POST['informationId'];
  echo json_encode($db->getSingleBill($infoId));
}

//suppression d'un client

if(isset($_POST['deleteId']))
{
  $deleteId = (int)$_POST['deleteId'];
  $verif = $db->avoir_commande($deleteId);
  if($verif){
    echo false;
  }else{
    echo $db->delete_client($deleteId);
    $action = " un client a été supprimé ";
    $db->enregistrerJournal($_SESSION["unique_id"],$action);
  }
}

//exporter une facture

if(isset($_GET['action']) && $_GET['action'] === 'export')
{

  
    $excelFileName = "clients".date('YmdHis').'.xls';
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$excelFileName");

    $columnName = ['identifiant', 'nom du client', 'prenom ', 'adresse', 'telephone', 'email', 'sexe'];

    $data = implode("\t", array_values($columnName))."\n";
    if($db->countBills()>0)
    {

        $bills = $db->read_client();

        $action = "Les informations des  clients  ont été exportées au format excel ";
        $db->enregistrerJournal($_SESSION["unique_id"],$action);


        foreach($bills as $bill){
            $excelData = [$bill->id_client, $bill->nom_client, $bill->surname_client, $bill->Adresse, $bill->Telephone, $bill->Email, $bill->sexe];
            $data .= implode("\t", $excelData). "\n";
        }

    }else{
        $data = "Aucun client .... " . "\n";
    }


    echo $data;
    die();

}

//gerer la commande depuis le client

if(isset($_POST['commandId']))
{
  $commandId = (int)$_POST['commandId'];
  $resultat = $db->nom_prenom_client($commandId);

  $data = array(
    'resultat' => $resultat,
    'id_comm' => $commandId
  );
  
  $jsonData = json_encode($data);
  echo $jsonData;
}

?>
