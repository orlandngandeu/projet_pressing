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





// creation des depenses
if(isset($_POST['action']) && $_POST['action'] === 'create')
{
    $comm = array("emplSearch"=>"","emplId"=>"","desc"=>"","amount"=>"",
    "descerror"=>"","amounterror"=>"","errorother"=>false,"ensuccess"=>false);

    $comm["emplId"] = $db->verify_input($_POST["emplId"]);
    $comm["emplSearch"]=$db->verify_input($_POST["emplSearch"]);
    $comm["desc"] = $db->verify_input($_POST["desc"]);
    $comm["amount"] = $db->verify_input($_POST["amount"]);
    $comm["ensuccess"] = true;
    $comm["errorother"] = false;
    

    $test = $db->SELECT_EMPL($comm["emplSearch"]);

    if(empty($comm["emplId"]) || !$test)
    {
        $comm["errorother"] = true;
        $comm["ensuccess"] = false;

    }


    if(empty($comm["desc"])){
        $comm["ensuccess"] = false;
        $comm["descerror"] = "Veuillez faire une description !";
    }

    if($comm["amount"]<0 || empty($comm["amount"]))
    {
        $comm["ensuccess"] = false;
        $comm["amounterror"] = "entrer un montant positif bro !!";
    }
    
 
    if($comm["ensuccess"])
    {
        $db ->enreg_dep($comm["emplId"], $comm["desc"], $comm["amount"]);
    }
    
    echo json_encode($comm);
    
}



if (isset($_POST['action']) && $_POST['action'] ==='fetch')
{
  $bills = $db->read_dep();
  $output = '';

   if($db->countBilldep()>0)
   {
    $output .= '
    <table class="table table-stripred">
    <thead>
     <tr>
      <th scope="col">Numéro</th>
      <th scope="col">Nom de l\'employé</th>
      <th scope="col">Description</th>
      <th scope="col">Montant</th>
      <th scope="col">date</th>
      <th scope="col">Heure</th>
      <th scope="col">Action</th>
     </tr>
    </thead>
    <tbody>';
    foreach($bills as $bill)
    {
        $output .= "
        <tr>
        <th scope=\"row\">$bill->id_depense</th>
        <td>$bill->nomComplet_employé</td>
        <td>$bill->descriptionss</td>
        <td>$bill->montant_depense</td>
        <td>$bill->date_depense</td>
        <td>$bill->heure_depense</td>
        <td class=\"action-cell\">
          <button class=\"action-button\">Opérations</button>
          <div class=\"action-list\">
            <a href=\"#\" class =\"text-info me-2 infoBtn\" title=\"voir infos\" data-id=\"$bill->id_depense\">
            <span class=\"material-symbols-outlined\">info</span>
            <p>Informations</p>
            <span>></span>
            </a>";

            $champ = $db->getuniqueEmpldep($bill->id_depense);
            $position = $db->getposition($_SESSION['unique_id']);
            if(($_SESSION['unique_id'] == $champ->unique_id) || ($position->position == 'Admin'))
            {
                $output .="<a href=\"#\" class =\"text-primary me-2 editBtn\" title=\"modifier\" data-id=\"$bill->id_depense\" data-bs-toggle='modal' 
                data-bs-target='#updateModal'><span class=\"material-symbols-outlined\">update</span>
                <p>Modifier</p>
                <span>></span>
                </a>
    
                <a href=\"#\" class =\"text-danger me-2 deleteBtn\" title=\"supprimer\" data-id=\"$bill->id_depense\">
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
      echo "<h3>Aucune depense  pour le moment </h3>";
  }
} 


if(isset($_POST['workingId']))
{
  $workingId = (int)$_POST['workingId'];
  echo json_encode($db->getsingledep($workingId));
}


//update de la depense



if(isset($_POST['action']) && $_POST['action'] === 'update')
{

    $tab = array("depUP"=>"","emplUPId"=>"","descUP"=>"","amountUP"=>"","descUPerror"=>"","amountUPerror"=>"",
    "otherUPerror"=>false,"dateUP"=>"","heureUP"=>"","success"=>false);

    $tab["depUP"] = $db->verify_input($_POST["depUP"]);
    $tab["emplUPId"] = $db->verify_input($_POST["emplUPId"]);
    $comm["emplUPSearch"]=$db->verify_input($_POST["emplUPSearch"]);
    $tab["descUP"] = $db->verify_input($_POST["descUP"]);
    $tab["amountUP"] = $db->verify_input($_POST["amountUP"]);
    $tab["dateUP"] = $db->verify_input($_POST["dateUP"]);
    $tab["heureUP"] = $db->verify_input($_POST["heureUP"]);
    $tab["success"] = true;
    $tab["otherUPerror"] = false;


    $testUP = $db->SELECT_EMPL($comm["emplUPSearch"]);


    if(empty($tab["emplUPId"]) ||  !$testUP)
    {
        $tab["otherUPerror"] = true;
        $tab["success"] = false;
    }

    if($tab["amountUP"]<0 || empty($tab["amountUP"]))
    {
        $tab["success"] = false;
        $tab["amountUPerror"] = "entrer un montant positif bro !!";
    }

    if(empty($tab["descUP"]))
    {
        $tab["success"] = false;
        $tab["descUPerror"] = "Veuillez faire une description !";
    }

    if($tab["success"])
    {
        $db -> update_dep($tab["depUP"],$tab["emplUPId"],$tab["descUP"],
        $tab["amountUP"],$tab["dateUP"], $tab["heureUP"]); 
    }

    echo json_encode($tab);


}

if(isset($_POST['informationId']))
{
  $infoId = (int)$_POST['informationId'];
  echo json_encode($db->getsingledep($infoId));
}


//suppression d'une depense

if(isset($_POST['deleteId']))
{
  $deleteId = (int)$_POST['deleteId'];
  echo $db->delele_dep($deleteId);
}

//exporter une depense

if(isset($_GET['action']) && $_GET['action'] === 'export')
{
    $excelFileName = "depenses".date('YmdHis').'.xls';
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$excelFileName");

    $columnName = ['Numéro', 'nom de l\'employé', 'Description', 'Montant', 'Date', 'Heure'];

    $data = implode("\t", array_values($columnName))."\n";
    if($db->countBilldep()>0)
    {
        $bills = $db->read_dep();
        foreach($bills as $bill){
            $excelData = [$bill->id_depense, $bill->nomComplet_employé, $bill->descriptionss, 
            $bill->montant_depense ,$bill->date_depense, $bill->heure_depense];

            $data .= implode("\t", $excelData). "\n";
        }

    }else{
        $data = "Aucune depense .... " . "\n";
    }

    echo $data;
    die();
}




?>