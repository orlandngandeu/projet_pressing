<?php   
 session_start();

require_once '../php/model.php';


$db = new Database();

$tab = array("newpasswd"=>"","confpasswd"=>"","confpasswdError"=>"","newpasswdError"=>"","ensuccess"=>false);

if($_SERVER["REQUEST_METHOD"] == "POST")
{
   $tab["newpasswd"] = $db->verify_input($_POST["newpasswd"]);
   $tab["confpasswd"] = $db->verify_input($_POST["confpasswd"]);
   $tab["ensuccess"] = true;

   if(isset($_COOKIE['maVariable']))
   {
    if($tab["newpasswd"] == "" || $tab["confpasswd"] == "")
    {
         $tab["newpasswdError"] = "remplie toutes les informations";
         $tab["confpasswdError"] = "remplie toutes les informations";
         $tab["ensuccess"] = false;
     }else{
         if(strlen($tab["newpasswd"])<=7){
             $tab["newpasswdError"] = "Mot de passe faible";
             $tab["ensuccess"] = false;
         }else{
             if($tab["newpasswd"] == $tab["confpasswd"])
             {
                 $db -> update_password($tab["newpasswd"],$_COOKIE['maVariable']);
                 $tab["ensuccess"] = true;
             }else{
                 $tab["confpasswdError"] = "Mot de passe incorrect.";
                 $tab["ensuccess"] = false;
             }
         }
     }
   }else{
    $tab["newpasswdError"] = "Mon ami attention, tu sautes les etapes ??";
    $tab["confpasswdError"] = "Mon ami attention, tu sautes les etapes ??";
    $tab["ensuccess"] = false;
   }

   echo json_encode($tab);

  }


?>