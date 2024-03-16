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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
        <link rel="stylesheet" href="../../css/connection.css">
        <title>ORLXIO Connexion</title>
    </head>
    <body>
        <div class="container">

            <div class="title">ORLXIO Connexion</div>
            <form id="connect-form" action="" role="form" method="POST" autocomplete="off">
                <div class="user-details">
                    <div class="input-box">
                        <span class="details">Nom d'utilisateur<span class="blue"> *</span></span>
                        <input type="text" id="nomUser" name="nomUser" placeholder=" votre nom d'utilisateur">
                        <p class="comments"></p>
                    </div>
    
                    <div class="input-box">
                        <span class="details">Mot de passe<span class="blue"> *</span></span>
                        <div class="pass">
                            <input type="password" id="passwd" name="passwd" placeholder="votre mot de passe">
                            <i class="fas fa-eye" id="eye-icon"></i>
                        </div>
                        <p class="comments"></p>
                    </div>
                </div>
    
                <div class="remember-forgot">
                    <label><input type="checkbox"> Rappelle - moi</label>
                    <a href="forgot.php">Mot de passe oublié ?</a>
                </div>

                <div class="button">
                    <input type="submit" value="Connexion">
                </div>

                <div class="login-register">
                    <p>es - tu inscris ?   <a href="register.php" class="register-link">S'incrire</a></p>
                </div>

            </form>
    
        </div>
  
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="../../js/scriptConnection.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</html>
