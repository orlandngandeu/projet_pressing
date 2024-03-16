<?php
  session_start();
  include_once "./config.php";
  $outgoing_id = $_SESSION['unique_id'];
  $searchterm = mysqli_real_escape_string($conn, $_POST['searchTerm']);
  $output = "";
  $sql = mysqli_query($conn, "SELECT * FROM employé WHERE NOT unique_id = {$outgoing_id} AND 
  (userName_employé LIKE '%{$searchterm}%' OR nomComplet_employé LIKE '%{$searchterm}%')");
  if(mysqli_num_rows($sql) > 0)
  {
    include "./data.php";
  }else{
    $output .= "Aucun utilisateur trouvé";
  }
  echo $output;
?>