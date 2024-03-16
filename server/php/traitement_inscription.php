<?php
 session_start();
  require_once '../php/model.php';
  $db =  new Database();

  $tab = array("nomCompl"=>"","nomUser"=>"","email"=>"","phone"=>"","age"=>"","confpass"=>"","verif"=>"","pass"=>"","nomComplError"=>"",
  "nomUserError"=>"","emailError"=>"","phoneError"=>"","ageError"=>"","passError"=>"","confpassError"=>"","verifError"=>"",
  "imageerror"=>"","ensuccess" => false);

  $destinataire = $_POST["email"];
  $author = 'ORLXIO';
  $mailt = 'orlanngandeu17@gmail.com';

  if($_SERVER["REQUEST_METHOD"] == "POST")
  {
    $tab["nomCompl"] = $db->verify_input($_POST["nomCompl"]);
    $tab["nomUser"] = $db->verify_input($_POST["nomUser"]);
    $tab["email"] = $db->verify_input($_POST["email"]);
    $tab["phone"] = $db->verify_input($_POST["phone"]);
    $tab["age"] = $db->verify_input($_POST["age"]);
    $tab["confpass"] =$db->verify_input($_POST["confpass"]);
    $tab["verif"] =$db->verify_input($_POST["verif"]);
    $tab["pass"] =$db->verify_input($_POST["pass"]);

    $tab["ensuccess"] = true;
    $mail_message = "";
    $reponse = $db ->select_employee($tab["nomUser"]);
    

   

    if(empty($tab["nomCompl"]))
    {
        $tab["nomComplError"] = "Votre nom s'il vous plait !";
        $tab["ensuccess"] = false;
    }
    else{
        $mail_message .= "Salut  ".$tab["nomCompl"]." ! "; 
    }
    if(empty($tab["nomUser"]))
    {
        $tab["nomUserError"] = "Votre nom d'utilisateur abek";
        $tab["ensuccess"]= false;
    }else if($reponse){
        $tab["nomUserError"] = "Ce nom est déja utilisé";
        $tab["ensuccess"] = false;
    }
    else{
        $mail_message .=  "\nBienvenue chez la famille  ORLXIO !!!,\n";
    }


    if(!is_email($tab["email"]))
    {
        $tab["emailError"] = "Mon bro, c'est pas un email ça !";
        $tab["ensuccess"] = false;
    }else{
        $emailva = $db->select_email($tab["email"]);
        if($emailva){
            $tab["emailError"] = "Mon bro, cet email est deja use !";
            $tab["ensuccess"] = false;
        }
        else{
            $mail_message .= "j'espere que tu vas bien car moi je t'attendais.\n";
        }
    }




    if(!is_phone($tab["phone"]) || !(strpos($tab["phone"], '+') !== false))
    {
        $tab["phoneError"] = "Que des opérateurs et des chiffres";
        $tab["ensuccess"] = false;
    }else{
        $answer = $db->select_phone($tab["phone"]);
        if($answer){
            $tab["phoneError"] = "Ce numéro est déja utilisé";
            $tab["ensuccess"] = false;
        }else{
            $mail_message .= "Tu es au top pour manager j'espere alors vas-y -- > \n";
        }
    }
  

    if(empty($tab["age"]) || $tab["age"]<= 0 || $tab["age"] >= 100)
    {
        $tab["ageError"] = "Un age normal pardon";
        $tab["ensuccess"] = false;
    }
    else{
        $mail_message .= "Cliquez sur le lien ci-dessous :\n";
    }
    if(strlen($tab["pass"])<=6)
    {
        $tab["passError"] = "un mot de passe fort";
        $tab["ensuccess"] = false;
    }
    else{
        $mail_message .= "<p><a href=\"https://www.orlxio.com\"></a></p>\n";
    }
    if($tab["pass"] !== $tab["confpass"])
    {
        $tab["confpassError"] = "mot de passe non identique";
        $tab["ensuccess"] = false;
    }
    if($tab["verif"] !== 'pressing237orl')
    {
        $tab["verifError"] = "Tu n'es pas un membre !!!";
        $tab["ensuccess"] = false;
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $img_name = $_FILES["image"]["name"];
        $tmp_name = $_FILES["image"]["tmp_name"];

        $img_explode = explode('.',$img_name);
        $img_ext = end($img_explode);
        $extensions = ['png', 'jpeg', 'jpg'];

        if(in_array($img_ext, $extensions) !== true)
        {
            $tab["ensuccess"] = false;
            $tab['imageerror']  = "Abek une image de type -jpeg, jpg, png!";
        }
    }
    else
    {
        $tab["ensuccess"] = false;
        $tab['imageerror'] = "Abek selectionne une image !";
    }

    if($tab["ensuccess"])
    {
        $time = time();
        $new_img_name = $time.$img_name;
        if(move_uploaded_file($tmp_name,"../../client/images/pictures/".$new_img_name))
        { 
            $status = "Connecté";
            $random_id = time();  
        }
        else{
            $tab["ensuccess"] = false;
            $tab['imageerror'] = "Impossible d'enregistrer cet image !!";
        }
        $debut_session = $db -> create($random_id,$tab["nomCompl"],$tab["nomUser"],$tab["phone"],$tab["age"]
        ,$tab["email"],$tab["pass"],$new_img_name,$status);
        if($debut_session)
        {
            $row = $db->select_employee($tab["nomUser"]);
            if($row !== false){
                $_SESSION["unique_id"] = $row['unique_id'];
            }
        }
        $hearders = "From: ".$author."<".$mailt.">\r\nReply-to:". $mailt;
        mail($destinataire, "WELCOME to orlxio", $mail_message, $hearders);
        $action = "Bienvenue ".$tab["nomCompl"]. " dans cette application ";
        $db->enregistrerJournal($_SESSION["unique_id"],$action);
    }

    echo json_encode($tab);

  }

  
  function is_phone($val)
  {
    return preg_match('/^[0-9\+\-]+$/', $val);
  }

  function is_email($val)
  {
    return filter_var($val, FILTER_VALIDATE_EMAIL);
  }


?>