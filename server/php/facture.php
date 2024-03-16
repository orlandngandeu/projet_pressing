<?php 

session_start();
require_once '../php/model.php';

$db = new Database();


if(isset($_POST["query"]))
{
    $success = false;
    $query = $_POST['query'];
    $amount = $db->montant_total($query);
    $pour =$db->part_respo($query);
    
    if ($amount) {
        $success = true;
        echo json_encode(array('amount' => $amount, 'pourcent' => $pour));
    }
    else{
        $success = false;
        echo json_encode($success);
    }

}



// creation des factures
if(isset($_POST['action']) && $_POST['action'] === 'create')
{
    $comm = array("idcomm"=>"","idcommerror"=>"","montantTOT"=>"","remise"=>"","remiseerror"=>"",
    "netpayer"=>"","montantcaisse"=>"","statut"=>"","echeance"=>"","echeanceerror"=>"","ensuccess"=>false);

    $comm["idcomm"] = $db->verify_input($_POST["idcomm"]);
    $comm["montantTOT"] = $db->verify_input($_POST["montantTOT"]);
    $comm["remise"] = $db->verify_input($_POST["remise"]);
    $comm["netpayer"] = $db->verify_input($_POST["netpayer"]);
    $comm["montantcaisse"] = $db->verify_input($_POST["montantcaisse"]);
    $comm["statut"] = $db->verify_input($_POST["statut"]);
    $comm["echeance"] = $db->verify_input($_POST["echeance"]);
    $comm["ensuccess"] = true;

    if($comm["remise"]>100)
    {
        $comm["ensuccess"] = false;
        $comm["remiseerror"] = "Veuillez entrer une remise normale !!";
    }
    if(empty($comm["montantTOT"]) || empty($comm["montantcaisse"]) || empty($comm["netpayer"]))
    {
        $comm["ensuccess"] = false;
        $comm["idcommerror"] = "Une erreur est survenue!!";
    }

    if(empty($comm["echeance"]) || $comm["echeance"]<date("Y-m-d"))
    {
        $comm["ensuccess"] = false;
        $comm["echeanceerror"] = "Verifiez cette date !!";
    }
    if(empty($comm["idcomm"]))
    {
        $comm["ensuccess"] = false;
        $comm["idcommerror"] = "Veuillez remplir ce champ !!";
    }
    else{
        $test = $db->valididcomm($comm["idcomm"]);
        if(!$test || ($db->possedefact($comm["idcomm"])))
        {
         $comm["ensuccess"] = false;
         $comm["idcommerror"] = "Impossible de faire une facture sur cette commande !";
       }
    }
 
    if($comm["ensuccess"])
    {
        $db ->save_facture($comm["idcomm"],$comm["montantTOT"] , (float)$comm["remise"],
        $comm["netpayer"],$comm["montantcaisse"],$comm["statut"],$comm["echeance"],$_SESSION['unique_id']);
    }
    
    echo json_encode($comm);
    
}



if (isset($_POST['action']) && $_POST['action'] ==='fetch')
{
  $bills = $db->read_fact();
  $output = '';

   if($db->countbillfact()>0)
   {
    $output .= '
    <table class="table table-stripred">
    <thead>
     <tr>
      <th scope="col">N_</th>
      <th scope="col">commande</th>
      <th scope="col">Facture</th>
      <th scope="col">remise</th>
      <th scope="col">Net payer</th>
      <th scope="col">Caisse</th>
      <th scope="col">statut</th>
      <th scope="col">date facture</th>
      <th scope="col">reste</th>
      <th scope="col">Date echeance</th>
      <th scope="col">Action</th>
     </tr>
    </thead>
    <tbody>';
    foreach($bills as $bill)
    {
        $output .= "
        <tr>
        <th scope=\"row\">$bill->id_facture</th>
        <td>$bill->id_commande</td>
        <td>".round($bill->montant_total, 2)."</td>
        <td>$bill->remise</td>
        <td>".round($bill->net_payer, 2)."</td>
        <td>".round($bill->montant_caisse,2)."</td>
        <td>$bill->statut</td>
        <td>$bill->date_facture</td>
        <td>".round($bill->reste_payer,2)."</td>
        <td>$bill->date_echeance</td>
        <td class=\"action-cell\">
          <button class=\"action-button\">Opérations</button>
          <div class=\"action-list\">
            <a href=\"#\" class =\"text-info me-2 infoBtn\" title=\"voir infos\" data-id=\"$bill->id_facture\">
            <span class=\"material-symbols-outlined\">info</span>
            <p>Informations</p>
            <span>></span>
            </a>";

            $champ = $db->getfactures($bill->id_facture);
            $position = $db->getposition($_SESSION['unique_id']);
            if(($_SESSION['unique_id'] == $champ->unique_id) || ($position->position == 'Admin'))
            {
                $output .= "<a href=\"#\" class =\"text-primary me-2 editBtn\" title=\"modifier\" data-id=\"$bill->id_facture\" 
                data-bs-toggle='modal' data-bs-target='#updateModal'><span class=\"material-symbols-outlined\">update</span>
                <p>Modifier</p>
                <span>></span>
                </a>
    
                <a href=\"#\" class =\"text-danger me-2 deleteBtn\" title=\"supprimer\" data-id=\"$bill->id_facture\">
                <span class=\"material-symbols-outlined\">delete</span>
                <p>Supprimer</p>
                <span>></span>
                </a>";
            }

            $output .="
            <a href=\"#\" class =\"text-warning  me-2 printBtn\" title=\"Imprimer\" data-id=\"$bill->id_facture\">
            <span class=\"material-symbols-outlined\">print</span>
            <p>Imprimer</p>
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
      echo "<h3>Aucune facture  pour le moment </h3>";
  }
} 



if(isset($_POST['workingId']))
{
  $workingId = (int)$_POST['workingId'];
  echo json_encode($db->getfactures($workingId));
}

if(isset($_POST['action']) && $_POST['action'] === 'update')
{

    $tab = array("id_fact"=>"","idcommUP"=>"","montantTOTUP"=>"","remiseUP"=>"","remiseUPerror"=>"","netpayerUP"=>"",
    "montantcaisseUP"=>"","dateUPfacture"=>"","heureUPfacture"=>"","echeanceUP"=>"","success"=>false,"echeanceUPerror"=>"");

    $tab["id_fact"] = $db->verify_input($_POST["id_fact"]);
    $tab["idcommUP"] = $db->verify_input($_POST["idcommUP"]);
    $tab["montantTOTUP"] = $db->verify_input($_POST["montantTOTUP"]);
    $tab["remiseUP"] = $db->verify_input($_POST["remiseUP"]);
    $tab["netpayerUP"] = $db->verify_input($_POST["netpayerUP"]);
    $tab["montantcaisseUP"] = $db->verify_input($_POST["montantcaisseUP"]);
    $tab["dateUPfacture"] = $db->verify_input($_POST["dateUPfacture"]);
    $tab["heureUPfacture"] = $db->verify_input($_POST["heureUPfacture"]);
    $tab["echeanceUP"] = $db->verify_input($_POST["echeanceUP"]);
    $tab["success"] = true;


    if($tab["remiseUP"]>100)
    {
      $tab["success"] = false;
      $tab["remiseUPerror"] = "Veuillez entrer une remise normale !! ";
    }

    if( empty($tab["echeanceUP"]) || $tab["echeanceUP"]<$tab["dateUPfacture"])
    {
        $tab["success"] = false;
        $tab["echeanceUPerror"] = "Impossible de faire une facture sur cette commande !";
    }
    if($tab["success"])
    {
        $db -> update_fact($tab["id_fact"],$tab["idcommUP"],$tab["montantTOTUP"],(float)$tab["remiseUP"],$tab["netpayerUP"],$tab["montantcaisseUP"]
        ,$tab["dateUPfacture"],$tab["heureUPfacture"],$tab["echeanceUP"]);
    }

    echo json_encode($tab);


}


if(isset($_POST['informationId']))
{
  $infoId = (int)$_POST['informationId'];
  echo json_encode($db->getfactures($infoId));
}



if(isset($_POST['deleteId']))
{
  $deleteId = (int)$_POST['deleteId'];
  $test = $db->verifpayement($deleteId);
  if($test)
  {  
    echo false;
  }else{
    echo $db->delete_fact($deleteId);
  }
}



if(isset($_GET['action']) && $_GET['action'] === 'export')
{
    $excelFileName = "factures".date('YmdHis').'.xls';
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$excelFileName");

    $columnName = ['identifiant', 'Identifiant commande', 'Montant', 'remise', 'Net payer', 
    'Montant caisse', 'date facture', 'Heure facture', 'statut', 'date_echeance', 'reste payer'];

    $data = implode("\t", array_values($columnName))."\n";
    if($db->countbillfact()>0)
    {
        $bills = $db->read_fact();
        foreach($bills as $bill){
            $excelData = [$bill->id_facture, $bill->id_commande, $bill->montant_total, $bill->remise, 
            $bill->net_payer ,$bill->montant_caisse, $bill->date_facture, $bill->heure_facture,
            $bill->statut, $bill->date_echeance, $bill->reste_payer];

            $data .= implode("\t", $excelData). "\n";
        }

    }else{
        $data = "Aucune facture .... " . "\n";
    }

    echo $data;
    die();
}

?>