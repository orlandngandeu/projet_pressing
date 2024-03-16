<?php

session_start();
require_once '../php/model.php';

$db = new Database();

if(isset($_POST["query"]))
{
    $query = $_POST['query'];
    $produits = $db->select_produit($query);
    if ($produits){
        foreach ($produits as $prod){
            echo '<li data-id="' . $prod['id_produit'].'">' . $prod['nom_produit'].'</li>';
        }
    } else {
        echo '<li class="invalid-name">Aucun produit trouvé</li>';
    }
}

// creation des commandes_lavages
if(isset($_POST['action']) && $_POST['action'] === 'commande_lavage')
{
    $lav = array("id_comm"=>"","nbrepcs"=>"","id_commerror"=>"","nbrepcserror"=>"","success"=>false);

    $lav["id_comm"] = $db->verify_input($_POST["id_comm"]);
    $lav["nbrepcs"] = $db->verify_input($_POST["nbrepcs"]);
    $lav["success"] = true;


    if(empty($lav["nbrepcs"]) || $lav["nbrepcs"]<=0)
    {
        $lav["success"] = false;
        $lav["nbrepcserror"] = "Entrer un Nombre strictement positif!";
    }

    if(empty($lav["id_comm"]))
    {
        $lav["success"] = false;
        $lav["id_commerror"] = "Veuillez entrer un Nombre !";
    }else{
        $result = $db->valididcomm($lav['id_comm']);
        if(!$result)
        {
            $lav["success"] = false;
            $lav["id_commerror"] = "Cette commande n'existe pas !";
        }
    }

    if($lav["success"])
    {
        $nombrepieces = $result["nombre_pieces"] - $result["pieces_lavee"];
        if($nombrepieces == 0)
        {
            $lav["success"] = false;
            $lav["id_commerror"] = "Cette commande est entièrement lavée.";
        }
        else if($lav['nbrepcs']>$nombrepieces)
        {
            $lav["success"] = false;
            $lav["nbrepcserror"] = $nombrepieces. " pièces sont non lavées";
        }
        else{
            if (!isset($_SESSION["data_array"])) {
                $_SESSION["data_array"] = array();
            }
            $dataRow = array($lav["id_comm"], $lav["nbrepcs"]);
            $_SESSION["data_array"][] = $dataRow;
            $db->update_pieces_lavee($lav['id_comm'],($lav["nbrepcs"]+$result["pieces_lavee"]));
        }
    }

    echo json_encode($lav);
}

// creation des produit_lavages
if(isset($_POST['action']) && $_POST['action'] === 'produit_lavage')
{
    $prod = array("prodId"=>"","Utilisation"=>"","prodIderror"=>"","success"=>false,"usage"=>false);

    $prod['prodId'] = $db->verify_input($_POST["id_produit"]);
    $prod['Utilisation'] = $db->verify_input($_POST["Utilisation"]);
    $prod['success'] = true;

    if(empty($prod['prodId']))
    {
        $prod['success'] = false;
        $prod["prodIderror"] = "Entrer un produit !";
    }
    else if($prod['Utilisation'] == "Dernière fois")
    {
        $db->remove_produit($prod["prodId"]);
        $prod['usage'] = true;
    }

    if($prod['success'])
    {
        if (!isset($_SESSION["prod_array"])) {
            $_SESSION["prod_array"] = array();
        }
        $prodRow = array($prod['prodId'],$prod['Utilisation']);
        $_SESSION["prod_array"][] = $prodRow;
    }
    echo json_encode($prod);
}

// planifier des lavages
if(isset($_POST['action']) && $_POST['action'] === 'planifier')
{
    $plan = array("masse"=>"","machine"=>"","masseerror"=>"","machineerror"=>"","comlav"=>"","success"=>false,"valeur"=>false);

    $plan["masse"] = $db->verify_input($_POST['masse']);
    $plan["machine"] = $db->verify_input($_POST['machine']);
    $plan["comlav"] = $db->verify_input($_POST['comlav']);
    $plan["success"] = true;

    if(empty($plan["masse"]) || $plan["masse"]<0){
        $plan["success"] = false;
        $plan["masseerror"] = "Veuillez entrer une masse positive !";
    }

    if(empty($plan["machine"])){
        $plan["success"] = false;
        $plan["machineerror"] = "Veuillez entrer un type de machine !";
    }

    if(isset($_SESSION["data_array"]) && isset($_SESSION["prod_array"]) && isset($_SESSION["unique_id"]))
    {
        if($plan["success"])
        {
            $currentDateTime = date('Y-m-d H:i:s');
            $db->insert_planifier_lavage($currentDateTime,$plan["comlav"],"En cours",$plan["machine"],$plan["masse"],$_SESSION['unique_id']);
            $ident_lav = $db->select_id_lavage($currentDateTime);
            if($ident_lav)
            {
                foreach ($_SESSION["data_array"] as $row){
                    $db->insert_commande_lavage($ident_lav->id_lavage, $row[0], $row[1]);
                    $db->update_commande_lavage($row[0]);
                }

                foreach ($_SESSION["prod_array"] as $produi){
                    $db->insert_produit_lavage($ident_lav->id_lavage, $produi[0], $produi[1]);
                }
                if (isset($_SESSION["prod_array"])){unset($_SESSION["prod_array"]);}
                if (isset($_SESSION["data_array"])){unset($_SESSION["data_array"]);}
            }
        }
    }else{
        $plan["success"] = false;
        $plan["valeur"] = true;
    }

    echo json_encode($plan);

}


if (isset($_POST['action']) && $_POST['action'] ==='fetch')
{
  $bills = $db->read_lavage();
  $output = '';

   if($db->countBilllav()>0)
   {
    $output .= '
    <table class="table table-stripred">
    <thead>
     <tr>
      <th scope="col">Numéro</th>
      <th scope="col">Temps début</th>
      <th scope="col">Commentaire</th>
      <th scope="col">Type machine</th>
      <th scope="col">Masse</th>
      <th scope="col">Statut</th>
      <th scope="col">Action</th>
     </tr>
    </thead>
    <tbody>';
    foreach($bills as $bill)
    {
        $output .= "
        <tr>
        <th scope=\"row\">$bill->id_lavage</th>
        <td>$bill->temps_debut</td>
        <td>$bill->commentaire_lavage</td>
        <td>$bill->type_machine</td>
        <td>$bill->masse</td>
        <td>$bill->statut</td>
        <td class=\"action-cell\">
        <button class=\"action-button\">Opérations</button>
        <div class=\"action-list\">
          <a href=\"#\" class =\"text-info me-2 infoBtn\" title=\"infos lavage\" data-id=\"$bill->id_lavage\">
          <span class=\"material-symbols-outlined\">info</span>
          <p>Informations</p>
          <span>></span>
          </a>";

          if($bill->statut != "Terminée")
          {
            $output .="<a href=\"#\" class =\"text-primary me-2 augmenter\" title=\"augmenter\" data-id=\"$bill->id_lavage\"> 
            <span class=\"material-symbols-outlined\">hdr_strong</span>
            <p>Augmenter produit</p>
            <span>></span>
            </a>

            <a href=\"#\" class =\"text-warning me-2 stop\" title=\"Stopper\" data-id=\"$bill->id_lavage\"> 
            <span class=\"material-symbols-outlined\">check_small</span>
            <p>Fin lavage</p>
            <span>></span>
            </a>";
          }else{
            $output .= "<a href=\"#\" class =\"text-warning me-2 prop\" title=\"Propriété\" data-id=\"$bill->id_lavage\"> 
            <span class=\"material-symbols-outlined\">cleaning_bucket</span>
            <p>Propriété</p>
            <span>></span>
            </a>
            <a href=\"#\" class =\"text-success me-2 sec\" title=\"sécher\" data-id=\"$bill->id_lavage\"> 
            <span class=\"material-symbols-outlined\">wb_sunny</span>
            <p>sécher</p>
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
      echo "<h3>Aucune lavage pour le moment </h3>";
  }
} 



if(isset($_POST['stopId']))
{
  $stopId = (int)$_POST['stopId'];
  $stoptime = date('Y-m-d H:i:s');
  $reponse = $db->stop_lavage($stopId,$stoptime);

  echo json_encode($reponse);
}

if(isset($_POST['informationId']))
{
  $infoId = (int)$_POST['informationId'];
  $infotab = array("planifier"=>"","commlav"=>"","prodlav"=>"");

  $infotab["planifier"] = $db->getsinglelav($infoId);
  $infotab["commlav"] = $db->commande_lav($infoId);
  $infotab["prodlav"] = $db->produit_lav($infoId);

  echo json_encode($infotab);
}


//exporter un lavage

if(isset($_GET['action']) && $_GET['action'] === 'export')
{
    $excelFileName = "lavages".date('YmdHis').'.xls';
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$excelFileName");

    $columnName = ['identifiant', 'Heure debut', 'Heure fin', 'commentaire', 'statut', 'type machine', 'poids'];

    $data = implode("\t", array_values($columnName))."\n";
    if($db->countBilllav()>0)
    {
        $bills = $db->read_lavage();
        foreach($bills as $bill){
            $excelData = [$bill->id_lavage, $bill->temps_debut, $bill->temps_fin, $bill->commentaire_lavage, 
            $bill->statut ,$bill->type_machine, $bill->masse];

            $data .= implode("\t", $excelData). "\n";
        }

    }else{
        $data = "Aucun lavage planifier .... " . "\n";
    }

    echo $data;
    die();
}


if(isset($_POST['augmenterId']))
{
  $augmenterId = (int)$_POST['augmenterId'];
  $productId = $db->verify_input($_POST['productId']);
  $utilisation = $db->verify_input($_POST['productSelect']);

  $reponse = $db->insProdLav($augmenterId,$productId,$utilisation);
  if($utilisation = "Dernière fois")
  {
    $db->remove_produit($productId);
  }
  echo json_encode($reponse);

}

if(isset($_POST['choixId']))
{
  $choixId = (int)$_POST['choixId'];
  $propriete = $db->verify_input($_POST['userChoice']);

  $reponse = $db->update_choix($choixId,$propriete);
  echo json_encode($reponse);

}

if(isset($_POST['secId']))
{
  $secId = (int)$_POST['secId'];
  $reponse = $db->verifLavage($secId);
  if($reponse)
  {
    echo json_encode($reponse);
  }else{
    echo json_encode(false);
  }
 

}

?>