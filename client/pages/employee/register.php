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
    <link rel="stylesheet" href="../../css/register.css">
    <title>ORLXIO Inscription</title>
</head>
<body>
    <div class="container">

        <div class="title">ORLXIO Inscription</div>
        <form id="contact-form" action="" role="form" method="POST" autocomplete="off">
            <div class="user-details">
                <div class="input-box">
                    <span class="details">Nom complet<span class="blue"> *</span></span>
                    <input type="text" id="nomCompl" name="nomCompl" placeholder="votre nom complet">
                    <p class="comments"></p>
                </div>

                <div class="input-box">
                    <span class="details">Nom d'utilisateur<span class="blue"> *</span></span>
                    <input type="text" id="nomUser" name="nomUser" placeholder=" votre nom d'utilisateur">
                    <p class="comments"></p>
                </div>

                <div class="input-box">
                    <span class="details">Adresse Email<span class="blue"> *</span></span>
                    <input type="email" id="email" name="email" placeholder="votre Adresse Email">
                    <p class="comments"></p>
                </div>

                <div class="input-box">
                    <span class="details">Téléphone<span class="blue"> *</span></span>
                    <input type="text" id="phone" name="phone" placeholder="votre numéro de Téléphone">
                    <p class="comments"></p>
                </div>

                <div class="input-box">
                    <span class="details">Age<span class="blue"> *</span></span>
                    <input type="number" id="age" name="age" placeholder="votre Age ">
                    <p class="comments"></p>
                </div>

                <div class="input-box">
                    <span class="details">Mot de passe<span class="blue"> *</span></span>
                    <input type="password" id="pass" name="pass" placeholder="votre mot de passe">
                    <p class="comments"></p>
                </div>

                <div class="input-box">
                    <span class="details">confirmer mot  de passe<span class="blue"> *</span></span>
                    <input type="password" id="confpass" name="confpass" placeholder="Confirmer votre mot  de passe">
                    <p class="comments"></p>
                </div>

                <div class="input-box">
                    <span class="details">Sélectionne une image <span class="blue"> *</span></span>
                    <input type="file" id="image" name="image">
                    <p class="comments"></p>
                </div>

                <div class="input-box">
                    <span class="details">vérification <span class="blue"> *</span></span>
                    <input type="password" id="verif" name="verif" placeholder="Vérifions que vous etes employé">
                    <p class="comments"></p>
                </div>
            </div>

            <div class="button">
                <input type="submit" value="Inscription">
            </div>

            <div class="login-register">
                <p>Je suis déja inscris.<a href="connexion.php" class="register-link">   Se connecter</a></p>
            </div>
        </form>

    </div>
    
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="../../js/register.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</html>