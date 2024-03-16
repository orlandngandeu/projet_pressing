<?php 
    while($row = mysqli_fetch_assoc($sql)){
        $sql2 = "SELECT * FROM messages WHERE (incoming_msg_id = {$row['unique_id']}
        OR outgoing_msg_id = {$row['unique_id']}) AND (outgoing_msg_id = {$outgoing_id}
        OR incoming_msg_id = {$outgoing_id}) ORDER BY msg_id DESC LIMIT 1";
        
        $query2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($query2);
        if(mysqli_num_rows($query2) > 0){   
            $result = $row2["mssg"];
        }else{
            $result = "Aucun message disponible";
        }

        // triming message if word are more than 28
        (strlen($result)>28) ? $msg = substr($result,0,28).'...' : $msg = $result;
        ($outgoing_id == $row2["outgoing_msg_id"]) ? $you ='Toi: ' : $you = "";
        // check user is online or offline
        ($row['status'] == "Déconnecté") ? $offline ="offline" : $offline = "";


        $output .='<a href="./chat.php?user_id='.$row['unique_id'].'">
        <div class="content">
            <img src="../../images/pictures/'.$row['image'].'" alt="">
            <div class="details">
                <span>'.$row['userName_employé'].'</span>
                <p>'.$you . $msg.'</p>
            </div>
        </div>
        <div class="status-dot '.$offline.'"><span class="material-symbols-outlined">
        radio_button_checked
        </span>
        </div>
    </a>';
    }
?>