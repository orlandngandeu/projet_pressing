<?php

session_start();

 require_once '../php/model.php'; 

 $db =  new Database();

  $tab = array("firstname"=>"","name"=>"","email"=>"","phone"=>"","message"=>"",
  "firstnameError"=>"","nameError"=>"","emailError"=>"","phoneError"=>"",
  "messageError"=>"","ensuccess" => false);

  $mailto = "orlanngandeu17@gmail.com";

  if($_SERVER["REQUEST_METHOD"] == "POST")  //des lors que l'utlisateur soumets les donnees.
  {
    $tab["firstname"] = $db->verify_input($_POST["firstname"]);
    $tab["name"] = $db->verify_input($_POST["name"]);
    $tab["email"] = $db->verify_input($_POST["email"]);
    $tab["phone"] = $db->verify_input($_POST["phone"]);
    $tab["message"] =$db->verify_input($_POST["message"]);
    $tab["ensuccess"] = true;
    $mail_message = ""; 
   

    if(empty($tab["firstname"]))
    {
        $tab["firstnameError"] = "Votre prénom !";
        $tab["ensuccess"] = false;
    }
    else{
        $mail_message .= "My name is ".$tab["firstname"]; 
    }
    if(empty($tab["name"]))
    {
        $tab["nameError"] = "Et oui, Aussi ton nom !";
        $tab["ensuccess"]= false;
    }
    else{
        $mail_message .=  $tab["name"]."\n";
    }
    if(!is_email($tab["email"]))
    {
        $tab["emailError"] = "Easy,c'est pas un email ça !";
        $tab["ensuccess"] = false;
    }
    else{
        $mail_message .= "my mail is : " .$tab["email"]."\n";
    }
    if(!is_phone($tab["phone"]))
    {
        $tab["phoneError"] = "Que des chiffres s'il te plait.....";
        $tab["ensuccess"] = false;
    }
    else{
        $mail_message .= "My phone is : ". $tab["phone"]. "\n\n";
    }
    if(empty($tab["message"]))
    {
        $tab["messageError"] = "Qu'est ce que tu veux me dire !";
        $tab["ensuccess"] = false;
    }
    else{
        $mail_message .= "My message is :". $tab["message"] ."\n";
    }

    if($tab["ensuccess"])
    {
        $hearders = "From: ".$tab["firstname"]." ". $tab["name"] ."<".$tab["email"].">\r\nReply-to:". $tab["email"];
        mail($mailto, "un message de votre site", $mail_message, $hearders);
    }

    echo json_encode($tab);

  }

  
  function is_phone($val)
  {
    return preg_match("/^[0-9 ]+$/", $val);
  }

  function is_email($val)
  {
    return filter_var($val, FILTER_VALIDATE_EMAIL);
  }


?>