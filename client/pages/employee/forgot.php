<?php 
session_start();
if(isset($_SESSION['unique_id'])){
    header("location: ../dashboard/dashbord.php");
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link rel="stylesheet" href="../../css/forgot.css">
    <title>Forgot password</title>
</head>
<body>
<div class="container">

<div class="title">ORLXIO Password</div>
<form id="forgot-form" action="" role="form" method="POST" autocomplete="off">
    <div class="user-details">
        <div class="input-box">
            <span class="details">Entrer votre adresse email</span>
            <input type="email" id="mailForgot" name="mailForgot" placeholder=" votre adresse email">
            <p class="comments"></p>
        </div>

        <div class="input-box">
          <span class="details">Ou</span>
        </div>
        
        <div class="input-box">
            <span class="details">Entrer votre numéro de téléphone</span>
            <input type="text" id="phoneForgot" name="phoneForgot" placeholder="votre numéro de téléphone">
            <p class="comments"></p>
        </div>
    </div>

    <div class="remember-forgot">
        <span>Vous allez recevoir un code de vérification</span>
        <a href="contactMe.php">contactez-moi</a>
    </div>

    <div class="button">
        <input type="submit" value="reçevoir">
    </div>

    <div class="login-register">
        <p>rentrez à la connexion  <a href="connexion.php" class="register-link">Se connecter</a></p>
    </div>

</form>

</div>
<script src="../../js/scriptCode.js"></script>
</body>
</html>