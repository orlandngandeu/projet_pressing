<?php 
session_start();
if(isset($_SESSION['unique_id'])){
    header("location: ../dashboard/dashbord.php");
}
?>


<!DOCTYPE html>
<html lang="en"  dir="ltr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
        <link rel="stylesheet" href="../../css/codeControl.css">
        <title>ORLXIO Reset</title>
    </head>
    <body>
        <div class="container">

            <div class="title">ORLXIO Reset</div>
            <form id="reset-form" action="" role="form" method="POST" autocomplete="off">
                <div class="user-details">

                    <div class="input-box">
                        <span class="details">Nouveau mot de passe<span class="blue"> *</span></span>
                        <input type="password" id="newpasswd" name="newpasswd" placeholder=" votre nouveau mot de passe">
                        <p class="comments"></p>
                    </div>
                    
                    <div class="input-box">
                        <span class="details">confirmer mot de passe<span class="blue"> *</span></span>
                        <input type="password" id="confpasswd" name="confpasswd" placeholder=" confirmer votre mot de passe">
                        <p class="comments"></p>
                    </div>
         
                </div>
    
                <div class="remember-forgot">
                    <a href="contactMe.php">contactez-moi</a>
                </div>

                <div class="button">
                    <input type="submit" value="Modifier">
                </div>

                <div class="login-register">
                    <p>Rentrez à la connexion<a href="connexion.php" class="register-link">  Se connecter</a></p>
                </div>

            </form>
    
        </div>
    <script src="../../js/resetps.js"></script>
    </body>
</html>