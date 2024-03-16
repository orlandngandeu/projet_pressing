<?php
 session_start();
 if(!isset($_SESSION['unique_id'])){
    header("location: ../employee/connexion.php");
 } 
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!-- Material icon -->
        <link
            href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp"
            rel="stylesheet"
        />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

        <!-- CSS -->
        <link rel="stylesheet" href="../../css/styless.css"/>
        <link rel="stylesheet" href="../../css/styles-responsive.css"/>
        <title>Orlxio Dashboard</title>
    </head>

    <body>
        <div class="dashboard-container">
            <!-- Sidebar -->
            <?php include "./entete.php";?>

            <!-- Main -->
            <main class="main-container">
                <h1 class="main-title">Dashboard</h1>

                <!-- CARD -->
                <div class="insights">
                    <div class="card">
                        <div class="card-container">
                            <div class="card-header">
                                <span class="material-icons-sharp">
                                    bar_chart
                                </span>
                            </div>
                            <div class="card-body">
                                <div class="card-info">
                                    <h3>Montant total</h3>
                                    <?php
                                        require_once '../../../server/php/model.php';
                                        $db = new Database(); 
                                        if($db->montant_total_work()>0)
                                        {
                                            $montantwork = $db->montant_total_work();
                                            $montantwork_employee = $db->montant_work_employee();
                                            $percente = $montantwork_employee/$montantwork *100;
                                        }else{
                                            $montantwork = 0;
                                            $percente = 0;
                                        }

                                        echo "<h1>$montantwork_employee F</h1>
                                        </div>
        
                                        <div class=\"card-progress\">
                                            <svg width=\"96\" height=\"96\">
                                                <circle
                                                    id=\"circle1\"
                                                    cx=\"38\"
                                                    cy=\"38\"
                                                    r=\"36\"
                                                    class=\"stroke-yellow\"
                                                ></circle>
                                            </svg>
                                            <div class=\"percentage\">
                                                <p>$percente%</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=\"card-footer\">
                                        <small>Mon taux de travail</small>
                                    </div>
                                </div>
                            </div>";
                        ?>
                                    

                    <div class="card">
                        <div class="card-container">
                            <div class="card-header">
                                <span class="material-icons-sharp">
                                    add_shopping_cart
                                </span>
                            </div>
                            <div class="card-body">
                                <div class="card-info">
                                    <h3>Commandes</h3>
                                    <?php
                                        require_once '../../../server/php/model.php';
                                        $db = new Database(); 
                                        if($db->nombre_clients()>0)
                                        {
                                            $nbre_commande = $db->nombre_commandes();
                                            $nbrecommnde_employee = $db->nombre_commande_employee();
                                            $percentage = $nbrecommnde_employee/$nbre_commande *100;
                                        }else{
                                            $nbre_commande = 0;
                                            $percentage = 0;
                                        }
                                        

                                        echo "<h1>$nbre_commande</h1>
                                        </div>
        
                                        <div class=\"card-progress\">
                                            <svg width=\"96\" height=\"96\">
                                                <circle
                                                    id=\"circle2\"
                                                    cx=\"38\"
                                                    cy=\"38\"
                                                    r=\"36\"
                                                    class=\"stroke-fuscha\"
                                                ></circle>
                                            </svg>
                                            <div class=\"percentage\">
                                                <p>$percentage%</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=\"card-footer\">
                                        <small>Mon taux de commande</small>
                                    </div>
                                </div>
                            </div>
        
                            <div class=\"card\">
                                <div class=\"card-container\">
                                    <div class=\"card-header\">
                                        <span class=\"material-icons-sharp\">
                                            group_add
                                        </span>
                                    </div>";
                                    ?>
                                    

                            <div class="card-body">
                                <div class="card-info">
                                    <h3>Clients</h3>
                                    <?php
                                        require_once '../../../server/php/model.php';
                                        $db = new Database(); 
                                        if($db->nombre_clients()>0)
                                        {
                                            $nbre_client = $db->nombre_clients();
                                            $nbre_clientparempl = $db->nombre_clientsparemployee();
                                            $percent = $nbre_clientparempl/$nbre_client *100;
                                        }else{
                                            $nbre_client = 0;
                                            $percent = 0;
                                        }

                                        

                                        echo " <h1>$nbre_client</h1>
                                        </div>

                                <div class=\"card-progress\">
                                    <svg
                                        width=\"96\"
                                        height=\"96\"
                                        class=\"stroke-cyan\"
                                    >
                                        <circle
                                            id=\"circle3\"
                                            cx=\"38\"
                                            cy=\"38\"
                                            r=\"36\"
                                        ></circle>
                                    </svg>
                                    <div class=\"percentage\">
                                        <p>$percent%</p>
                                    </div>
                                </div>
                            </div>
                            <div class=\"card-footer\">
                                <small>Mon taux de client</small>
                            </div>
                        </div>
                    </div>
                </div>";
                ?>
                                

                <!-- RECENT ORDERS -->
                <section class="recent-orders">
                    <div class="ro-title">
                        <h2 class="recent-orders-title">Commandes récentes</h2>
                        <a href="./commande.php" class="show-all">Tout afficher</a>
                    </div>
                    
                    <?php 
                    require_once '../../../server/php/model.php';
                    $db = new Database(); 
                    $bills = $db->read_dashboard();
                    $output = '';
                  
                    if($db->countBillcom()>0)
                    {
                      $output .= '
                      <table>
                      <thead>
                        <tr>
                        <th>ID_comm</th>
                        <th>Nom client</th>
                        <th>Pièces</th>
                        <th>Niveau</th>
                        <th>Statut</th>
                        <th>Date limite de paiement</th>
                        </tr>
                      </thead>
                      <tbody>';
                      foreach($bills as $bill)
                      {
                        $output .= "
                        <tr>
                        <td>$bill->id_commande</td>
                        <td>$bill->nom_client<span>  <span>$bill->surname_client</td>
                        <td>$bill->nombre_pieces</td>
                        <td>$bill->statut_commande</td>
                        <td class=\"text-fuscha\">$bill->statut_facture</td>
                        <td>$bill->date_echeance</td>
                        </tr>
                        ";
                      }
                      $output .="</tbody></table>";
                      echo $output;
                    }else{
                        echo "<h3>Aucune commande pour le moment </h3>";
                    }
                    ?>
                </section>
            </main>

            <!-- Sidebar droite -->
            <aside class="extrabar">
                <header class="header-right">
                    <button class="toggle-menu-btn" id="openSidebar">
                        <span class="material-icons-sharp"> menu </span>
                    </button>

                    <div class="toggle-theme">
                        <span class="material-icons-sharp active">
                            light_mode
                        </span>
                        <span class="material-icons-sharp"> dark_mode </span>
                    </div>
                    <?php include "./profile.php"; ?>
                </header>

                <!-- Info recentes -->
                <div class="recent-updates">
                    <h2>Infos récentes</h2>
                    <div class="updates-container">

                    <?php
                    require_once '../../../server/php/model.php';
                    $db = new Database(); 

                    $reponse = $db->getAction();

                    if ($reponse) 
                    {
                        foreach($reponse as $rep)
                        {
                            $img = $db->select_image($rep->unique_id);
                            echo "<div class=\"update\">
                                        <div class=\"profile-image\">
                                            <img
                                                src=\"../../images/pictures/$img->image\"
                                                alt=\"\"
                                                width=\"100%\"
                                            />
                                        </div>
                                        <div class=\"message\">
                                            <p>$rep->action</p>
                                            <small>Le $rep->timestamp</small>
                                        </div>
                                    </div>";
                        } 
                    }else{
                        echo "Aucune action enregistrée pour le moment.";
                    } 
                    ?>
                    </div>
                </div>

                <!-- Stat -->
                <div class="analytics">
                    <h2>Evolution des commandes</h2>
                    <div class="order direct">
                        <div class="order-icon">
                            <span class="material-icons-sharp">
                                shopping_cart
                            </span>
                        </div>
                        <div class="order-details">
                            <div class="info">
                                <h3>COMMANDES DIRECTES</h3>
                                <small>48H precedentes</small>
                            </div>
                            <h4 class="percent-evo text-cyan">+18%</h4>
                            <h3>2417</h3>
                        </div>
                    </div>
                    <div class="order online">
                        <div class="order-icon">
                            <span class="material-icons-sharp"> wifi </span>
                        </div>
                        <div class="order-details">
                            <div class="info">
                                <h3>COMMANDES DIRECTES</h3>
                                <small>48H precedentes</small>
                            </div>
                            <h4 class="percent-evo text-fuscha">-5%</h4>
                            <h3>619</h3>
                        </div>
                    </div>
                    <div class="order customers">
                        <div class="order-icon">
                            <span class="material-icons-sharp">
                                group_add
                            </span>
                        </div>
                        <div class="order-details">
                            <div class="info">
                                <h3>COMMANDES DIRECTES</h3>
                                <small>48H precedentes</small>
                            </div>
                            <h4 class="percent-evo text-cyan">+22%</h4>
                            <h3>411</h3>
                        </div>
                    </div>
                    <button class="new-product">
                        <span class="material-icons-sharp"> add </span>
                        <h3>Nouvelle commande</h3>
                    </button>
                </div>
            </aside>
        </div>

    </body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../js/board.js"></script>
</html>
