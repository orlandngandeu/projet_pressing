<?php 
session_start();
require_once '../php/model.php';
require_once '../json/vendor/autoload.php';


$db = new Database();

$tab = array("mailForgot"=>"","phoneForgot"=>"","mailForgotError"=>"","phoneForgotError"=>"","ensuccess"=>false);

if($_SERVER["REQUEST_METHOD"] == "POST")
  {
    $tab["mailForgot"] = $db->verify_input($_POST["mailForgot"]);
    $tab["phoneForgot"] = $db->verify_input($_POST["phoneForgot"]);
    $tab["ensuccess"] = true;

    if($tab["mailForgot"] != '' && $tab["phoneForgot"] != '')
    {
      $tab["phoneForgotError"] = "Tu ne sais  pas lire 'ou' !!";
      $tab["mailForgotError"] = "Tu ne sais  pas lire 'ou' !!";
      $tab["ensuccess"] = false;
    }else{
      if($tab["mailForgot"] != ''){
        $reponse = $db->select_email($tab["mailForgot"]);
      
        if($reponse["unique_id"])
        {
          $stock_code = mt_rand(0,10000000);//nombre aleatoire
          //envoie du mail
          $destinataire = $tab["mailForgot"];
          $subject = "code Orlxio";
          $message = "votre code de vérification ORLXIO est : " . $stock_code;
          $headers = "From: orlanngandeu17@gmail.com";
          mail($destinataire, $subject, $message, $headers);
          $db -> update_codeMail($tab["mailForgot"],$stock_code);
          setcookie('maVariable', $tab["mailForgot"], time() + 600);

          $tab["ensuccess"] = true;
        }else{
          $tab["mailForgotError"] = "Je ne connais pas cet email";
          $tab["ensuccess"] = false;
        }
      }
      
      if($tab["phoneForgot"] != '')
      {
        $answer = $db->select_phone($tab["phoneForgot"]);
        
        if($answer["unique_id"])
        {
          $stock_code = mt_rand(0,10000000);
          //gestion de la verification

          $db->update_codeTel($tab["phoneForgot"],$stock_code);
          setcookie('maVariable',$tab["phoneForgot"], time() + 600);

          $tab["ensuccess"] = true;
        }else{
          $tab["phoneForgotError"] = "numéro de téléphone invalide !!";
          $tab["ensuccess"] = false;
        }
      }
      
      if($tab["mailForgot"] == '' && $tab["phoneForgot"] == '')
      {
        $tab["phoneForgotError"] = "Au moins une valeur à remplir";
        $tab["mailForgotError"] = "Au moins une valeur à remplir";
        $tab["ensuccess"] = false;
      }
    }
    
    
    // renvoie de la reponse au format JSON
    
    echo json_encode($tab);

  }

?>