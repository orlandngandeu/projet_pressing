<?php 

include_once "../../../server/php/config.php";
$sql = mysqli_query($conn, "SELECT * FROM employé WHERE unique_id = {$_SESSION['unique_id']}");
if(mysqli_num_rows($sql)>0)
{
    $row = mysqli_fetch_assoc($sql);
}


echo '<div class="profile">
<div class="profile-info">
    <p>Salut, <strong>'.$row["userName_employé"].'</strong></p>
    <small>'.$row["position"].'</small>
</div>
<div class="profile-image">
    <img src="../../images/pictures/'.$row["image"].'" alt="your picture" width="100%" class="user-pic" id="imgProfil"/>
    <div class="sub-menu-wrap" id="subMenu">
        <div class="sub-menu">
            <div class="user-info">
                <img src="../../images/pictures/'.$row["image"].'">
                <h4>'.$row["nomComplet_employé"].'</h4>
            </div>
            <hr>
            <a href="#" class="sub-menu-link">
                <img src="../../images/profile.png">
                <p>Modifier le profil</p>
                <span>></span>
            </a>

            <a href="#" class="sub-menu-link">
                <img src="../../images/setting.png">
                <p>Paramètres</p>
                <span>></span>
            </a>

            <a href="#" class="sub-menu-link">
                <img src="../../images/help.png">
                <p>Aide & Support</p>
                <span>></span>
            </a>

            <a href="../../../server/php/logout.php?logout_id='. $_SESSION['unique_id'].'" class="sub-menu-link">
                <img src="../../images/logout.png">
                <p>Déconnecter</p>
                <span>></span>
            </a>
        </div>
    </div>
</div>
</div>'
?>