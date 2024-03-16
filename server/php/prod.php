<?php 
session_start();
require_once '../php/model.php'; 
$db =  new Database();


$tabcl = array("nom"=>"","qte"=>"","price"=>"","desc"=>"",
"nomError"=>"","qteError"=>"","priceError"=>"","ensuccess"=>false);


if(isset($_POST['action']) && $_POST['action'] === 'create')
{

  $tabcl["nom"] = $db->verify_input($_POST["nom_produit"]);
  $tabcl["qte"] = $db->verify_input($_POST["qte_produit"]);
  $tabcl["price"] = $db->verify_input($_POST["prix_produit"]);
  $tabcl["desc"] = $db->verify_input($_POST["desc"]);
  $tabcl["ensuccess"] = true;


  if(empty($tabcl["nom"]))
  {
    $tabcl["ensuccess"] = false;
    $tabcl["nomError"] = "Veuillez remplir le nom de ce produit !";
  }

  if(empty($tabcl["qte"]))
  {
    $tabcl["ensuccess"] = false;
    $tabcl["qteError"] = "Veuillez remplir la quantité de ce produit !";
  }

  if(empty($tabcl["price"]))
  {
    $tabcl["ensuccess"] = false;
    $tabcl["priceError"] = "Veuillez remplir le prix de ce produit !";
  }

  if($tabcl["ensuccess"])
  {
    $db ->enreg_produit($tabcl["nom"],$tabcl["qte"],$tabcl["price"],$tabcl["desc"]); 
  }

  echo json_encode($tabcl);


}


if (isset($_POST['action']) && $_POST['action'] ==='fetch')
{
  $bills = $db->read_produit();
  $output = '';

  if($db->countbillprod()>0){
    $output .= '
    <table class="table table-stripred">
    <thead>
     <tr>
      <th scope="col">Numéro</th>
      <th scope="col">Nom du produit</th>
      <th scope="col">Quantité du produit</th>
      <th scope="col">prix du produit</th>
      <th scope="col">Description</th>
      <th scope="col">Action</th>
     </tr>
    </thead>
    <tbody>';
    foreach($bills as $bill)
    {
        $output .= "
        <tr>
        <th scope=\"row\">$bill->id_produit</th>
        <td>$bill->nom_produit</td>
        <td>$bill->quantity_stock</td>
        <td>$bill->prix_unitaire</td>
        <td>$bill->Description_produit</td>
        <td class=\"action-cell\">
          <button class=\"action-button\">Opérations</button>
          <div class=\"action-list\">
            <a href=\"#\" class =\"text-info me-2 infoBtn\" title=\"voir infos\" data-id=\"$bill->id_produit\">
            <span class=\"material-symbols-outlined\">info</span>
            <p>Informations</p>
            <span>></span>
            </a>

            <a href=\"#\" class =\"text-primary me-2 editBtn\" title=\"modifier\" data-id=\"$bill->id_produit\"
            data-bs-toggle='modal' data-bs-target='#updateModal'><span class=\"material-symbols-outlined\">update</span>
            <p>Modifier</p>
            <span>></span>
            </a>";

            $position = $db->getposition($_SESSION['unique_id']);
            if($position->position == "Admin")
            {
              $output .= "  <a href=\"#\" class =\"text-danger me-2 deleteBtn\" title=\"supprimer\" data-id=\"$bill->id_produit\">
              <span class=\"material-symbols-outlined\">delete</span>
              <p>Supprimer</p>
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
    echo "<h3>Aucun produit  pour le moment </h3>";
  }
} 


if(isset($_POST['workingId']))
{
  $workingId = (int)$_POST['workingId'];
  echo json_encode($db->getproduit($workingId));
}


//update des produits
$tab = array("prod"=>"","nomUP"=>"","qteUP"=>"","priceUP"=>"","descUP"=>"",
"nomUPerror"=>"","qteUPerror"=>"","priceUPerror"=>"","success"=>false);

if(isset($_POST['action']) && $_POST['action'] === 'update')
{ 

  $tab["prod"] = $db->verify_input($_POST["prod"]);
  $tab["nomUP"] = $db->verify_input($_POST["nom_produitUP"]);
  $tab["qteUP"] = $db->verify_input($_POST["qte_produitUP"]);
  $tab["priceUP"] = $db->verify_input($_POST["prix_produitUP"]);
  $tab["descUP"] = $db->verify_input($_POST["descup"]);
  $tab["success"] = true;


  if(empty($tab["nomUP"]))
  {
    $tab["success"] = false;
    $tab["nomUPerror"] = "Veuillez remplir le nom de ce produit !";
  }

  if(empty($tab["qteUP"]))
  {
    $tab["success"] = false;
    $tab["qteUPerror"] = "Veuillez remplir la quantité de ce produit !";
  }

  if(empty($tab["priceUP"]))
  {
    $tab["success"] = false;
    $tab["priceUPerror"] = "Veuillez remplir le prix de ce produit !";
  }

  if($tab["success"])
  {
    $db ->update_produit($tab["prod"],$tab["nomUP"],$tab["qteUP"],$tab["priceUP"],$tab["descUP"]); 
  }

  echo json_encode($tab);


}

if(isset($_POST['informationId']))
{
  $infoId = (int)$_POST['informationId'];
  echo json_encode($db->getproduit($infoId));
}

//suppression d'une categorie

if(isset($_POST['deleteId']))
{
  $deleteId = (int)$_POST['deleteId'];
  echo $db->delete_produit($deleteId);
}


//exporter une categorie en liste

if(isset($_GET['action']) && $_GET['action'] === 'export')
{
    $excelFileName = "produits".date('YmdHis').'.xls';
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$excelFileName");

    $columnName = ['identifiant produit', 'nom du produit',  'Quantité du produit',  'Prix du produit',
    'Description du produit'];

    $data = implode("\t", array_values($columnName))."\n";
    if($db->countbillprod()>0)
    {
      $bills = $db->read_produit();
      foreach($bills as $bill){
        $excelData = [$bill->id_produit, $bill->nom_produit, $bill->quantity_stock, $bill->prix_unitaire
        ,$bill->Description_produit];
        $data .= implode("\t", $excelData). "\n";
      }

    }else{
      $data = "Aucun produit .... " . "\n";
    }

    echo $data;
    die();
}




?>