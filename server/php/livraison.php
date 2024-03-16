<?php 
session_start();
require_once '../php/model.php'; 

$db =  new Database();


$list = array("idcomm"=>"","idcommerror"=>"","idDate"=>"","idTime"=>"","status"=>"","ensuccess"=>false,
"idDateError"=>"","idTimeError"=>"");

if(isset($_POST['action']) && $_POST['action'] === 'create')
{

  $list["idcomm"] = $db->verify_input($_POST["idcomm"]);
  $list["idDate"] = $db->verify_input($_POST["idDate"]);
  $list["idTime"] = $db->verify_input($_POST["idTime"]);
  $list["status"] = $db->verify_input($_POST["status"]);
  $list["ensuccess"] = true;

  if(empty($list["idDate"])){
    $list["ensuccess"] = false;
    $list["idDateError"] = "Veuillez entrer une date de livraison !!";
  }

  if(empty($list["idTime"])){
    $list["ensuccess"] = false;
    $list["idTimeError"] = "Veuillez entrer une heure de livraison !!";
  }

  if(empty($list["idcomm"]) || $list["idcomm"]<0) 
  {
    $list["ensuccess"] = false;
    $list["idcommerror"] = "L'identifiant doit etre un entier positif !!";
  }else{
    $test = $db->valididcomm($list["idcomm"]);
    if(!$test)
    {
      $list["ensuccess"] = false;
      $list["idcommerror"] = "Cet identifiant n'existe pas !!";
    }
       
  }


  if($list["ensuccess"])
  {
    $db ->save_liv($list["idcomm"],$list["idDate"],$list["idTime"],$list["status"]);
  }

  echo json_encode($list);


}


if (isset($_POST['action']) && $_POST['action'] ==='fetch')
{
  $bills = $db->read_liv();
  $output = '';

  if($db->countBilliv()>0){
    $output .= '
    <table class="table table-stripred">
    <thead>
     <tr>
      <th scope="col">Numéro</th>
      <th scope="col">Identifiant commande</th>
      <th scope="col">Date de la livraison</th>
      <th scope="col">Heure de la livraison</th>
      <th scope="col">statut livraison</th>
      <th scope="col">Action</th>
     </tr>
    </thead>
    <tbody>';
    foreach($bills as $bill)
    {
        $output .= "
        <tr>
        <th scope=\"row\">$bill->id_livraison</th>
        <td>$bill->id_commande</td>
        <td>$bill->date_livraison</td>
        <td>$bill->heure_livraison</td>
        <td>$bill->statut_livraison</td>
        <td class=\"action-cell\">
          <button class=\"action-button\">Opérations</button>
          <div class=\"action-list\">
            <a href=\"#\" class =\"text-info me-2 infoBtn\" title=\"voir infos\" data-id=\"$bill->id_livraison\"><span class=\"material-symbols-outlined\">info</span>
            <p>Informations</p>
            <span>></span>
            </a>

            <a href=\"#\" class =\"text-primary me-2 editBtn\" title=\"modifier\" data-id=\"$bill->id_livraison\" data-bs-toggle='modal' data-bs-target='#updateModal'><span class=\"material-symbols-outlined\">update</span>
            <p>Modifier</p>
            <span>></span>
            </a>

            <a href=\"#\" class =\"text-warning me-2 fin_liv\" title=\"Livrer\" data-id=\"$bill->id_livraison\"><span class=\"material-symbols-outlined\">done_all</span>
            <p>Livrée</p>
            <span>></span>
            </a>

            <a href=\"#\" class =\"text-danger me-2 deleteBtn\" title=\"supprimer\" data-id=\"$bill->id_livraison\"><span class=\"material-symbols-outlined\">delete</span>
            <p>Supprimer</p>
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
    echo "<h3>Aucune livraison  pour le moment </h3>";
  }
} 


if(isset($_POST['workingId']))
{
  $workingId = (int)$_POST['workingId'];
  echo json_encode($db->getliv($workingId));
}


//update des commandes

$tab = array("listingUP"=>"","quantifyUP"=>"","unitUP"=>"","id_UPcomm"=>"","id_UPcommError"=>"",
"instructUP"=>"","id_list"=>"","success"=>"");

if(isset($_POST['action']) && $_POST['action'] === 'update')
{

  $tab = array("id_liv"=>"" ,"idUPcomm"=>"" ,"idUPDate"=>"" ,"idUPTime"=>"", "statusUP"=>"",
  "success" => false);

  $tab["id_liv"] = $db->verify_input($_POST["id_liv"]);
  $tab["idUPcomm"] = $db->verify_input($_POST["idUPcomm"]);
  $tab["idUPDate"] = $db->verify_input($_POST["idUPDate"]);
  $tab["idUPTime"] = $db->verify_input($_POST["idUPTime"]);
  $tab["statusUP"] = $db->verify_input($_POST["statusUP"]);
  $tab["success"] = true;

  if(empty($tab["idUPcomm"]) || $tab["idUPcomm"]<0 || empty($tab["idUPDate"]) || empty($tab["idUPTime"]) || 
  empty($tab["statusUP"]))
  {
    $tab["success"] = false;
    $tab["id_UPcommError"] = "Veuillez entrer votre identifiant !!";
  }
  else{
    $testUP = $db->valididcomm($tab["idUPcomm"]);
    if(!$testUP)
    {
      $tab["success"] = false;
      $tab["id_UPcommError"] = "Cet identifiant n'existe pas!!";
    }
  }

  if($tab["success"])
  {
    $db ->update_liv($tab["id_liv"],$tab["idUPcomm"],$tab["idUPDate"],$tab["idUPTime"],$tab["statusUP"]); 
  }

  echo json_encode($tab);


}

if(isset($_POST['informationId']))
{
  $infoId = (int)$_POST['informationId'];
  echo json_encode($db->getliv($infoId));
}



//suppression d'une livraison 

if(isset($_POST['deleteId']))
{
  $deleteId = (int)$_POST['deleteId'];
  echo $db->delete_liv($deleteId);
}



//exporter une livraison

if(isset($_GET['action']) && $_GET['action'] === 'export')
{
  $excelFileName = "livraison".date('YmdHis').'.xls';
  header("Content-Type: application/vnd.ms-excel");
  header("Content-Disposition: attachment; filename=$excelFileName");

  $columnName = ['identifiant', 'Identifiant de la commande', 'date de la livraison', 'Heure de la livraison', 'Statut'];

  $data = implode("\t", array_values($columnName))."\n";
  if($db->countBilliv()>0)
  {
    $bills = $db->read_liv();
    foreach($bills as $bill){
      $excelData = [$bill->id_livraison, $bill->id_commande,  $bill->date_livraison, $bill->heure_livraison, $bill->statut_livraison];
      $data .= implode("\t", $excelData). "\n";
    }

  }else{
    $data = "Aucune livraison .... " . "\n";
  }

  echo $data;
  die();
}


if(isset($_POST['stopId']))
{
  $stopId = (int)$_POST['stopId'];
  $date_livraison = date('Y-m-d');
  $heure_livraison = date('H:i:s');
  $reponse = $db->stop_livraison($stopId,$date_livraison,$heure_livraison);

  echo json_encode($reponse);
}


?>