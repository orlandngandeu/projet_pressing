<?php
    session_start();
    require_once '../php/model.php';

    $db =  new Database();
    if(isset($_SESSION['unique_id'])){
        include_once "./config.php";
        $logout_id = mysqli_real_escape_string($conn, $_GET["logout_id"]);
        if(isset($logout_id))
        {
            $status = "Déconnecté";
            $sql = mysqli_query($conn, "UPDATE employé SET status = '{$status}' WHERE unique_id = {$logout_id}");
            if($sql)
            {
                $vnom = $db->select_nom_employee($_SESSION['unique_id']);
                $action = $vnom["nomComplet_employé"]. " vient de se deconnecter a l'application ";
                $db->enregistrerJournal($_SESSION["unique_id"],$action);
                session_unset();
                session_destroy();
                header("location: ../../client/pages/employee/connexion.php");
            }
        }else{
            header("location: ./users.php");
        }
    }else{
        header("location: ../../client/pages/employee/connexion.php");
    }

?>