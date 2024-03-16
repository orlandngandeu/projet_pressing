<?php 
session_start();
require_once '../php/model.php';


$db = new Database();

$tableau = array("codeverif"=>"","codeverifError"=>"", "ensuccess"=>false);

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $tableau["codeverif"] = $db->verify_input($_POST["codeVerif"]);
    $tableau["ensuccess"] = true;

    if($tableau["codeverif"] != '')
    {
        if (isset($_COOKIE['maVariable'])) {
            $stock_code = $db->select_code($_COOKIE['maVariable']);             
        } else {
            $tableau["codeverifError"] = "Trop tard re-envoyer le code  !!";
            $tableau["ensuccess"] = false;
        }

        if($tableau["codeverif"] == $stock_code["code_verify"])
        {
            $tableau["ensuccess"] == true;
            $db -> update_code($_COOKIE['maVariable']);
        }else{
            $tableau["codeverifError"] = "Code de vérification incorrect !!";
            $tableau["ensuccess"] = false;
        }

    }else{
       $tableau["codeverifError"] = "entrer un code mon bro !!";
       $tableau["ensuccess"] = false;
    }


    echo json_encode($tableau);
}



?>