<?php 
session_start();
if(isset($_SESSION['unique_id'])){
    header("location: ../dashboard/dashbord.php");
}
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Conctactez-moi!</title>
        <meta charset = "utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
        <link href="http://font.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="../../css/contactMe.css">
        <script src="../../js/scriptContactMe.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="divider"></div>
            <div class="heading">
                <h2>Contactez-moi</h2>
            </div>
            <div class="row">
                <div class="col-lg-15 col-lg-offset-1">
                    <form id="contact-form" method="post" action="" role="form" autocomplete="off">
                      <div class="row">
                        <div class="col-md-6">
                            <label for="firstname">Prénom<span class="blue"> *</span></label>
                            <input type="text" id="firstname" name="firstname" class="form-control" placeholder="Votre prénom">
                            <p class="comments"></p>
                        </div>

                        <div class="col-md-6">
                            <label for="name">Nom<span class="blue"> *</span></label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Votre nom">
                            <p class="comments"></p>
                        </div>

                        <div class="col-md-6">
                            <label for="email">Email<span class="blue"> *</span></label>
                            <input type="text" id="email" name="email" class="form-control" placeholder="Votre email">
                            <p class="comments"></p>
                        </div>

                        <div class="col-md-6">
                            <label for="phone">Téléphone<span class="blue"> *</span></label>
                            <input type="tel" id="phone" name="phone" class="form-control" placeholder="Votre Téléphone">
                            <p class="comments"></p>
                        </div>

                        <div class="col-md-12">
                            <label for="message">Message<span class="blue"> *</span></label>
                            <textarea id="message" name="message" class="form-control" rows="5" placeholder="Votre message" rows="4"></textarea>
                            <p class="comments"></p>
                        </div>

                        <div class="col-md-12">
                            <p class="blue"><b>* Ces informations sont requises !</b></p>
                        </div>

                        <div class="col-md-12">
                            <input type="submit" class="button1" value="Envoyer"> 
                        </div>

                      </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>