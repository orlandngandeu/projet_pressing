<?php 
require_once '../php/model.php'; 

$db =  new Database();

function is_phone($val)
{
    return preg_match('/^[0-9\+\-]+$/', $val);
}


//creation des clients

$tabresp = array("nameresp"=>"","surnameresp"=>"","pourc"=>"","telresp"=>"",
"nameresperror"=>"","surnameresperror"=>"","pourcerror"=>"","telresperror"=>"","ensuccess"=>false);
$tab = array("id"=>"","nameUPresp"=>"","surnameUPresp"=>"","telUPresp"=>"","pourcUP"=>"","success"=>false,
"nameUPresperror"=>"","surnameUPresperror"=>"","telUPresperror"=>"","pourcUPerror"=>"");


if(isset($_POST['action']) && $_POST['action'] === 'create')
{

    $tabresp["nameresp"] = $db->verify_input($_POST["nameresp"]);
    $tabresp["surnameresp"] = $db->verify_input($_POST["surnameresp"]);
    $tabresp["pourc"] = $db->verify_input($_POST["pourc"]);
    $tabresp["telresp"] = $db->verify_input($_POST["telresp"]);
    $tabresp["ensuccess"] = true;

    if(empty($tabresp["surnameresp"]))
    {
      $tabresp["ensuccess"] = false;
      $tabresp["surnameresperror"] = "Veuillez remplir Votre prénom !!";
    }

    if(empty($tabresp["nameresp"]))
    {
      $tabresp["ensuccess"] = false;
      $tabresp["nameresperror"] = "Veuillez remplir Votre Nom !!";
    }

    if(empty($tabresp["pourc"]) || ($tabresp["pourc"])<0 || ($tabresp["pourc"])>100)
    {
      $tabresp["ensuccess"] = false;
      $tabresp["pourcerror"] = "Le pourcentage doit etre positif !!";
    }


    if(!is_phone($tabresp["telresp"]))
    {
      $tabresp["ensuccess"] = false;
      $tabresp["telresperror"] = "Veuillez remplir ce champ !!";
    }

    if($tabresp["ensuccess"])
    {
      $db ->save_resp($tabresp["nameresp"],$tabresp["surnameresp"],$tabresp["telresp"],$tabresp["pourc"]);
    }

    echo json_encode($tabresp);


}



//recuperation des responsables



if (isset($_POST['action']) && $_POST['action'] ==='fetch')
{
  $bills = $db->read_respo();
  $output = '';

   if($db->countBillsr()>0)
   {
    $output .= '
    <table class="table table-stripred">
    <thead>
     <tr>
      <th scope="col">Numéro</th>
      <th scope="col">Nom</th>
      <th scope="col">Prénom</th>
      <th scope="col">Téléphone</th>
      <th scope="col">Pourcentage</th>
      <th scope="col">Action</th>
     </tr>
    </thead>
    <tbody>';
    foreach($bills as $bill)
    {
        $output .= "
        <tr>
        <th scope=\"row\">$bill->id_responsable</th>
        <td>$bill->nom_respo</td>
        <td>$bill->surname_respo</td>
        <td>$bill->telephone</td>
        <td>$bill->pourcentage</td>
        <td class=\"action-cell\">
          <button class=\"action-button\">Opérations</button>
          <div class=\"action-list\">
            <a href=\"#\" class =\"text-info me-2 infoBtn\" title=\"voir infos\" data-id=\"$bill->id_responsable\"><span class=\"material-symbols-outlined\">info</span>
            <p>Informations</p>
            <span>></span>
            </a>

            <a href=\"#\" class =\"text-primary me-2 editBtn\" title=\"modifier\" data-id=\"$bill->id_responsable\" data-bs-toggle='modal' data-bs-target='#updateModal'><span class=\"material-symbols-outlined\">update</span>
            <p>Modifier</p>
            <span>></span>
            </a>

            <a href=\"#\" class =\"text-danger me-2 deleteBtn\" title=\"supprimer\" data-id=\"$bill->id_responsable\"><span class=\"material-symbols-outlined\">delete</span>
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
      echo "<h3>Aucun responsable  pour le moment </h3>";
  }
} 



//infos pour details du responsable 


if(isset($_POST['workingId']))
{
  $workingId = (int)$_POST['workingId'];
  echo json_encode($db->getSingleBills($workingId));
}



//update des responsables

if(isset($_POST['action']) && $_POST['action'] === 'update')
{

    $tab["nameUPresp"] = $db->verify_input($_POST["nameUPresp"]);
    $tab["surnameUPresp"] = $db->verify_input($_POST["surnameUPresp"]);
    $tab["telUPresp"] = $db->verify_input($_POST["telUPresp"]);
    $tab["pourcUP"] = $db->verify_input($_POST["pourcUP"]);
    $tab["id"] = $db->verify_input($_POST["id"]);
    $tab["success"] = true;


    if(empty($tab["surnameUPresp"]))
    {
      $tab["success"] = false;
      $tab["surnameUPresperror"] = "Veuillez remplir Votre prénom !!";
    }

    if(empty($tab["nameUPresp"]))
    {
      $tab["success"] = false;
      $tab["nameUPresperror"] = "Veuillez remplir Votre Nom !!";
    }

    if(empty($tab["pourcUP"]) || $tab["pourcUP"]<0 || $tab["pourcUP"]>100)
    {
      $tab["success"] = false;
      $tab["pourcUPerror"] = "Le pourcentage doit etre positif !!";
    }


    if(!is_phone($tab["telUPresp"]))
    {
      $tab["success"] = false;
      $tab["telUPresperror"] = "Veuillez remplir ce champ !!";
    }

    if($tab["success"])
    {
      $db ->update_respo($tab["id"],$tab["nameUPresp"],$tab["surnameUPresp"],$tab["telUPresp"],$tab["pourcUP"]); 

    }

    echo json_encode($tab);


}


if(isset($_POST['informationId']))
{
  $infoId = (int)$_POST['informationId'];
  echo json_encode($db->getSingleBills($infoId));
}

//suppression d'un responsable

if(isset($_POST['deleteId']))
{
  $deleteId = (int)$_POST['deleteId'];
  echo $db->delete_respo($deleteId);
}

//exporter une liste EXCEL de responsable

if(isset($_GET['action']) && $_GET['action'] === 'export')
{
    $excelFileName = "responsable".date('YmdHis').'.xls';
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$excelFileName");

    $columnName = ['identifiant', 'nom du responsable', 'prenom ', 'telephone', 'pourcentage'];

    $data = implode("\t", array_values($columnName))."\n";
    if($db->countBillsr()>0)
    {
        $bills = $db->read_respo();
        foreach($bills as $bill){
            $excelData = [$bill->id_responsable, $bill->nom_respo, $bill->surname_respo, $bill->telephone, $bill->pourcentage];
            $data .= implode("\t", $excelData). "\n";
        }

    }else{
        $data = "Aucun responsable .... " . "\n";
    }

    echo $data;
    die();
}

?>
