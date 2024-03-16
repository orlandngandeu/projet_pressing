<?php 

session_start();
require_once '../php/model.php';

$db = new Database;


if(isset($_POST["query"]))
{
    $query = $_POST['query'];
    $employee = $db->search_employee($query);
    if ($employee) {
        foreach ($employee as $empl) {
            echo '<li data-id="' . $empl['id_employé'] . '">' . $empl['nomComplet_employé'] . '</li>';
        }
    } else {
        echo '<li class="invalid-name">Aucun employé trouvé</li>';
    }
}


if(isset($_POST['action']) && $_POST['action'] === 'create')
{
    $comm = array("idfact"=>"","idfacterror"=>"","emplSearch"=>"","emplId"=>"","montant"=>"","mode"=>"",
    "ensuccess"=>false,"emplSearcherror"=>"","montanterror"=>"","modeerror"=>"");

    $comm["idfact"] = $db->verify_input($_POST["idfact"]);
    $comm["emplSearch"]=$db->verify_input($_POST["emplSearch"]);
    $comm["emplId"]=$db->verify_input($_POST["emplId"]);
    $comm["montant"] = $db->verify_input($_POST["montant"]);
    $comm["mode"] = $db->verify_input($_POST["mode"]);
    $comm["ensuccess"] = true;


    if(empty($comm["emplSearch"]) || empty($comm["emplId"]))
    {
        $comm["ensuccess"] = false;
        $comm["emplSearcherror"] = "Une erreur est survenue !!";
    }

    if($comm["montant"]<=0 || empty($comm["montant"]))
    {
        $comm["ensuccess"] = false;
        $comm["montanterror"] = "Le montant est un nombre positif !!";
    }

    if(empty($comm["mode"]))
    {
        $comm["ensuccess"] = false;
        $comm["modeerror"] = "Veuillez remplir ce champ !!";
    }

    if(empty($comm["idfact"]))
    {
        $comm["ensuccess"] = false;
        $comm["idfacterror"] = "Veuillez remplir ce champ !";
    }
    else{
        $test = $db->SELECT_EMPL($comm["emplSearch"]);
        $val = $db->avoirfactures($comm["idfact"]);
        if(($test["id_employé"] != $comm["emplId"]) ||  !$val)
        {
            $comm["ensuccess"] = false;
            $comm["idfacterror"] = "cet Identifiant n'existe pas !";
        }
    
        if($comm["ensuccess"])
        {
            $db->paye_fact($comm["idfact"], $comm["montant"]);
            $db ->save_recu($comm["idfact"], $comm["emplId"], $comm["montant"], $comm["mode"]);
        }
    }

    echo json_encode($comm);
    
}


if (isset($_POST['action']) && $_POST['action'] ==='fetch')
{
  $bills = $db->read_recu();
  $output = '';

   if($db->countbillrecu()>0)
   {
    $output .= '
    <table class="table table-stripred">
    <thead>
     <tr>
      <th scope="col">Numéro</th>
      <th scope="col">Identifiant commande</th>
      <th scope="col">Nom de l\'employé</th>
      <th scope="col">Montant payé</th>
      <th scope="col">mode de paiement</th>
      <th scope="col">date de paiement</th>
      <th scope="col">Heure de paiement</th>
      <th scope="col">Action</th>
     </tr>
    </thead>
    <tbody>';
    foreach($bills as $bill)
    {
        $output .= "
        <tr>
        <th scope=\"row\">$bill->id_recu</th>
        <td>$bill->id_facture</td>
        <td>$bill->nomComplet_employé</td>
        <td>$bill->montant_paye</td>
        <td>$bill->mode_paiement</td>
        <td>$bill->date_paiement</td>
        <td>$bill->heure_paiement</td>
        <td class=\"action-cell\">
          <button class=\"action-button\">Opérations</button>
          <div class=\"action-list\">
            <a href=\"#\" class =\"text-info me-2 infoBtn\" title=\"voir infos\" data-id=\"$bill->id_recu\">
            <span class=\"material-symbols-outlined\">info</span>
            <p>Informations</p>
            <span>></span>
            </a>";

            $champ = $db->getuniqueEmpl($bill->id_recu);
            $position = $db->getposition($_SESSION['unique_id']);
            if(($_SESSION['unique_id'] == $champ->unique_id) || ($position->position == 'Admin'))
            {
                $output .= "<a href=\"#\" class =\"text-primary me-2 editBtn\" title=\"modifier\" data-id=\"$bill->id_recu\" data-bs-toggle='modal'
                data-bs-target='#updateModal'><span class=\"material-symbols-outlined\">update</span>
               <p>Modifier</p>
               <span>></span>
               </a>
   
             <!-- <a href=\"#\" class =\"text-danger me-2 deleteBtn\" title=\"supprimer\" data-id=\"$bill->id_recu\">
               <span class=\"material-symbols-outlined\">delete</span>
               <p>Supprimer</p>
               <span>></span>
               </a> -->";
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
      echo "<h3>Aucun payement  pour le moment </h3>";
  }
} 


if(isset($_POST['workingId']))
{
  $workingId = (int)$_POST['workingId'];
  echo json_encode($db->getrecu($workingId));
}

// mise a jour du payement 

if(isset($_POST['action']) && $_POST['action'] === 'update')
{

    $tab = array("idfactUP"=>"","emplUPSearch"=>"","emplUPId"=>"","montantUP"=>"",
    "modeUP"=>"","dateUP"=>"","heureUP"=>"","success"=>false,"modeUPerror"=>"","montantUPerror"=>"");

    $identifiant = $db->verify_input($_POST["id_recu"]);
    $tab["idfactUP"] = $db->verify_input($_POST["idfactUP"]);
    $tab["emplUPSearch"]=$db->verify_input($_POST["emplUPSearch"]);
    $tab["emplUPId"]=$db->verify_input($_POST["emplUPId"]);
    $tab["montantUP"] = $db->verify_input($_POST["montantUP"]);
    $save = $db->verify_input($_POST["montantsaveUP"]);
    $tab["modeUP"] = $db->verify_input($_POST["modeUP"]);
    $tab["dateUP"] = $db->verify_input($_POST["dateUP"]);
    $tab["heureUP"] = $db->verify_input($_POST["heureUP"]);
    $tab["success"] = true;


    if(empty($tab["modeUP"]))
    {
        $tab["success"] = false;
        $tab["modeUPerror"] = "Veuillez remplir cette valeur !";
    }

    if($tab["montantUP"]<0 || empty($tab["montantUP"]))
    {
        $tab["success"] = false;
        $tab["montantUPerror"] = "Ce valeur doit etre positive !";
    }
    
    if($tab["success"])
    {
        $db->miseJour_fact($tab["idfactUP"],$tab["montantUP"],$save);
        $db->update_recu($identifiant,$tab["idfactUP"],$tab["emplUPId"],
        $tab["dateUP"],$tab["heureUP"],$tab["montantUP"],$tab["modeUP"]);
    }

    echo json_encode($tab);


}



if(isset($_POST['informationId']))
{
  $infoId = (int)$_POST['informationId'];
  echo json_encode($db->getrecu($infoId));
}


if(isset($_POST['deleteId']))
{
  $deleteId = (int)$_POST['deleteId'];
  echo $db->delete_pay($deleteId);
}

//exporter les payements

if(isset($_GET['action']) && $_GET['action'] === 'export')
{
    $excelFileName = "payements".date('YmdHis').'.xls';
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$excelFileName");

    $columnName = ['Numéro', 'Identifiant facture','nom de l\'employé', 'Montant payé', 'Mode de paiement', 'date de paiement', 
    'Heure de paiement'];

    $data = implode("\t", array_values($columnName))."\n";
    if($db->countbillrecu()>0)
    {
        $bills = $db->read_recu();
        foreach($bills as $bill){
            $excelData = [$bill->id_recu, $bill->id_facture, $bill->nomComplet_employé, $bill->montant_paye, 
            $bill->mode_paiement ,$bill->date_paiement, $bill->heure_paiement];

            $data .= implode("\t", $excelData). "\n";
        }

    }else{
        $data = "Aucun payement .... " . "\n";
    }

    echo $data;
    die();
}

?>