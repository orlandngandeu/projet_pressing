<?php 



    if($db->nombre_resposable()>0)
    {
        $tab["respo"] = $db->nombre_resposable();
    }else{
        $tab["respo"] = 0;
    }

    echo json_encode($tab);


?>