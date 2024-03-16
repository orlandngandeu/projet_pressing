<?php
 session_start();
 if(!isset($_SESSION['unique_id'])){
    header("location: ../employee/connexion.php");
 } 
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="x-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orlxio chargement</title>
    <link rel="stylesheet"  href="../../css/chargement.css">
</head>
<body>
    <div class="container active">
        <span class="overlay" id="percent">0%</span>
    </div>
    
</body>
<script>
    const container = document.querySelector(".container"),
          percent = document.querySelector("#percent");
    
    let perVal = 0;

    let increment = setInterval(() => {
        perVal++;
        percent.textContent = `${perVal}%`;

        if(perVal == 100)
        {
            clearInterval(increment);
            container.classList.remove("active");
            window.location.href = '../dashboard/dashbord.php';
        }
    }, 30);
</script>
</html>