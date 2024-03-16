<?php
  session_start();
  require_once '../php/model.php';

  $db = new Database();

  $tab = array("nomUser"=>"","passwd"=>"","nomUserError"=>"","passwdError"=>"","ensuccess" => false);

  if($_SERVER["REQUEST_METHOD"] == "POST")
  {
    $tab["nomUser"] = $db->verify_input($_POST["nomUser"]);
    $tab["passwd"] = $db->verify_input($_POST["passwd"]);
    $tab["ensuccess"] = true;
  
    if($tab["nomUser"] != ''){
      $reponse = $db->select_employee($tab["nomUser"]);

      if($reponse)
      {
        if(password_verify($tab["passwd"],$reponse["mot_passe_hash"])){
          $status = "Connecté";
          $db->update_status_employee($status,$reponse["unique_id"]);
          $_SESSION['unique_id'] = $reponse['unique_id'];
          $action = "Bienvenue ".$tab["nomUser"]. " dans l'application, n'oubliez pas de vous deconnecter avant de partir !";
          $db->enregistrerJournal($_SESSION["unique_id"],$action);
          $tab["ensuccess"]= true;
        }else{
          $tab["passwdError"] = "Mot de passe incorrect";
          $tab["ensuccess"]= false;
        }
      }
      else{
        $tab["nomUserError"] = "Nom d'utilisateur incorrect";
        $tab["passwdError"] = "Mot de passe incorrect";
        $tab["ensuccess"] = false;
  
      }
    }else{
      $tab["nomUserError"] = "Nom d'utilisateur non definie";
      $tab["ensuccess"] = false;
    }
    
    // renvoie de la reponse au format JSON
    
    echo json_encode($tab);

  }



?>