<?php 
session_start();
require_once '../php/model.php'; 

$db =  new Database();


$list = array("id_comm"=>"","listing"=>"","quantify"=>"","unit"=>"","instruct"=>"","ensuccess"=>false,
"id_commerror"=>"","listingerror"=>"","quantifyerror"=>"","uniterror"=>"");

if(isset($_POST['action']) && $_POST['action'] === 'create')
{

    $list["id_comm"] = $db->verify_input($_POST["id_comm"]);
    $list["listing"] = $db->verify_input($_POST["listing"]);
    $list["quantify"] = $db->verify_input($_POST["quantify"]);
    $list["unit"] = $db->verify_input($_POST["unit"]);
    $list["instruct"] = $db->verify_input($_POST["instruct"]);
    $list["ensuccess"] = true;


    if(empty($list["listing"]))
    {
      $list["ensuccess"] = false;
      $list["listingerror"] = "Veuillez remplir le listing !";
    }

    if($list["quantify"]<0 || empty($list["quantify"]))
    {
      $list["ensuccess"] = false;
      $list["quantifyerror"] = "La quantité doit etre positive !";
    }


    if($list["unit"]<0 || empty($list["unit"]))
    {
      $list["ensuccess"] = false;
      $list["uniterror"] = "Le prix doit etre positif !";
    }

    if($list["id_comm"]<0 || empty($list["id_comm"]))
    {
      $list["ensuccess"] = false;
      $list["id_commerror"] = "Une commande est positive !";
    }
    else
    {
      $test = $db->valididcomm($list["id_comm"]);

      if(!$test)
      {
        $list["ensuccess"] = false;
        $list["id_commerror"] = "cet identifiant n'existe pas !";
      }
      
      if($list["ensuccess"])
      {
        $db ->save_list($list["id_comm"],$list["listing"],$list["quantify"],$list["unit"],$list["instruct"],$_SESSION['unique_id']); 
        $avoir = $db->avoirfactures($list["id_comm"]);
        if($avoir)
        {
          $db->update_facture($list["id_comm"],$list["unit"]*$list["quantify"]);
        }
      }
    }
 
    

  echo json_encode($list);


}



if (isset($_POST['action']) && $_POST['action'] ==='fetch')
{
  $bills = $db->read_list();
  $output = '';

  if($db->countBilllist()>0){
    $output .= '
    <table class="table table-stripred">
    <thead>
     <tr>
      <th scope="col">Numéro</th>
      <th scope="col">Identifiant commande</th>
      <th scope="col">Quantité</th>
      <th scope="col">Prix unitaire</th>
      <th scope="col">montant</th>
      <th scope="col">instructions</th>
      <th scope="col">Action</th>
     </tr>
    </thead>
    <tbody>';
    foreach($bills as $bill)
    {
        $output .= "
        <tr>
        <th scope=\"row\">$bill->id_listing</th>
        <td>$bill->id_commande</td>
        <td>$bill->quantity</td>
        <td>$bill->prix_unitaire</td>
        <td>".round($bill->montant,2)."</td>
        <td>$bill->Instructions_speciales</td>
        <td class=\"action-cell\">
          <button class=\"action-button\">Opérations</button>
          <div class=\"action-list\">
            <a href=\"#\" class =\"text-info me-2 infoBtn\" title=\"voir infos\" data-id=\"$bill->id_listing\">
            <span class=\"material-symbols-outlined\">info</span>
            <p>Informations</p>
            <span>></span>
            </a>";

            $champ = $db->getlistings($bill->id_listing);
            $position = $db->getposition($_SESSION['unique_id']);
            if(($_SESSION['unique_id'] == $champ->unique_id) || ($position->position == 'Admin'))
            {
              $output .="
              <a href=\"#\" class =\"text-primary me-2 editBtn\" title=\"modifier\" data-id=\"$bill->id_listing\" 
            data-bs-toggle='modal' data-bs-target='#updateModal'><span class=\"material-symbols-outlined\">update</span>
            <p>Modifier</p>
            <span>></span>
            </a>

            <a href=\"#\" class =\"text-danger me-2 deleteBtn\" title=\"supprimer\" data-id=\"$bill->id_listing\">
            <span class=\"material-symbols-outlined\">delete</span>
            <p>Supprimer</p>
            <span>></span>
            </a>";
            }

            $output .="
            <a href=\"#\" class =\"text-warning me-2 factureBtn\" title=\"Nouvelle facture\" data-id=\"$bill->id_commande\">
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
    echo "<h3>Aucun listing  pour le moment </h3>";
  }
} 



//infos pour details du listing 


if(isset($_POST['workingId']))
{
  $workingId = (int)$_POST['workingId'];
  echo json_encode($db->getlistings($workingId));
}


//update des commandes

$tab = array("listingUP"=>"","quantifyUP"=>"","unitUP"=>"","id_UPcomm"=>"","listingUPerror"=>"","quantifyUPerror"=>"",
"unitUPerror"=>"","instructUP"=>"","id_list"=>"","qtelast"=>"","unitlast"=>"","success"=>false);

if(isset($_POST['action']) && $_POST['action'] === 'update')
{

    $tab["listingUP"] = $db->verify_input($_POST["listingUP"]);
    $tab["quantifyUP"] = $db->verify_input($_POST["quantifyUP"]);
    $tab["unitUP"] = $db->verify_input($_POST["unitUP"]);
    $tab["qtelast"] = $db->verify_input($_POST["qtelast"]);
    $tab["unitlast"] = $db->verify_input($_POST["unitlast"]);
    $tab["id_UPcomm"] = $db->verify_input($_POST["id_UPcomm"]);
    $tab["instructUP"] = $db->verify_input($_POST["instructUP"]);
    $tab["id_list"] = $db->verify_input($_POST["id_list"]);
    $tab["success"] = true;

    if(empty($tab["listingUP"]))
    {
      $tab["success"] = false;
      $tab["listingUPerror"] = "Veuillez remplir le listing";
    }
    if(empty($tab["quantifyUP"]) || $tab["quantifyUP"]<0)
    {
      $tab["success"] = false;
      $tab["quantifyUPerror"] = "la quantité doit etre positive !!";
    }

    if(empty($tab["unitUP"]) || $tab["unitUP"]<0)
    {
      $tab["success"] = false;
      $tab["unitUPerror"] = "le prix doit etre positive !!";
    }
        
    if($tab["success"])
    {
      $db ->update_list($tab["id_list"],$tab["id_UPcomm"],$tab["listingUP"],$tab["quantifyUP"],$tab["unitUP"],
      $tab["instructUP"]); 
      $fact = $db->avoirfactures($tab["id_UPcomm"]);
      if($fact){
        $db->misejourfact($tab["id_UPcomm"],($tab["unitUP"]*$tab["quantifyUP"]),($tab["unitlast"]*$tab["qtelast"]));
      }
    }

    echo json_encode($tab);


}

if(isset($_POST['informationId']))
{
  $infoId = (int)$_POST['informationId'];
  echo json_encode($db->getlistings($infoId));
}



//suppression d'un listing

if(isset($_POST['deleteId']))
{
  $deleteId = (int)$_POST['deleteId'];
  $test = $db->verifsupplist($deleteId);
  if($test)
  {  
    echo false;
  }else{
    echo $db->delete_listing($deleteId); 
  }
}

//exporter une facture

if(isset($_GET['action']) && $_GET['action'] === 'export')
{
    $excelFileName = "listing".date('YmdHis').'.xls';
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$excelFileName");

    $columnName = ['identifiant', 'Identifiant de la commande', 'quantity', 'prix_unitaire', 'montant', 'Instructions speciales'];

    $data = implode("\t", array_values($columnName))."\n";
    if($db->countBilllist()>0)
    {
        $bills = $db->read_list();

        $action = "Les informations des  listings ont été exportées au format excel ";
        $db->enregistrerJournal($_SESSION["unique_id"],$action);

        foreach($bills as $bill){
            $excelData = [$bill->id_listing, $bill->id_commande,  $bill->quantity, $bill->prix_unitaire, $bill->montant, $bill->Instructions_speciales];
            $data .= implode("\t", $excelData). "\n";
        }

    }else{
        $data = "Aucun listing .... " . "\n";
    }

    echo $data;
    die();
}


?>