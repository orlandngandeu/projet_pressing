<?php
 session_start();
 if(!isset($_SESSION['unique_id'])){
    header("location: ../employee/connexion.php");
 } 
?>



<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/v/bs5/dt-1.13.4/datatables.min.css" rel="stylesheet"/>
        <link rel="stylesheet" href="../../css/interieur.css">
        <link rel="stylesheet" href="../../css/respo.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet"/>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        <title>ORLXIO lavage</title>
    </head>
    <body>

   <div class="dashboard-container">
      <!-- Sidebar -->
      <?php include "./entete.php";?>

      <main class="main-container">
        <div class="sujet">
            <h1 class="main-title">Gestion des lavages</h1>
           

            <!-- Sidebar droite -->
            <aside class="extrabar">
                <header class="header-right">
                    <button class="toggle-menu-btn" id="openSidebar">
                        <span class="material-icons-sharp"> menu </span>
                    </button>

                    <div class="toggle-theme">
                        <span class="material-icons-sharp active">light_mode</span>
                        <span class="material-icons-sharp"> dark_mode </span>
                    </div>
                    <?php include "./profile.php"; ?>
                </header>
            </aside>
        </div>


        <div class="contenu">
            <section class="container py-5">
                <div class="ligne"></div>
                <div class="entreligne">
                    <div><h5 class="fw-bold md-8">Listes des lavages</h5></div>
                    <div class="operation">
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#createModal">
                            <span class="material-symbols-outlined">add_circle</span> Planifier</button>
                            <a href="../../../server/php/trait_lav.php?action=export" class="btn btn-success btn-sm" id="export">
                            <span class="material-symbols-outlined">export_notes</span> Exporter</a>
                        </div>
                    </div>
                </div>
                <div class="ligne"></div>
                <div class="row">
                    <div class="table-responsive" id="orderTable">
                        <h3 class="text-success text-center">Chargement des lavages...</h3>
                    </div>
                    </div>        
            </section>
                    
                    
            <!-- Modal -->
            <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
             <div class="modal-dialog">
               <div class="modal-content">
                <div class="modal-header" id="titreform">
                    <h5 class="modal-title" id="createModalLabel">Planifier un lavage</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form id="formOrder" action="" method="post">

                        <div class="row g-2 align-items-center">
                            <div class="col-md-5">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="id_comm">
                                    <p class="comments"></p>
                                    <label for="id_comm">Identifiant commande</label>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="nbrepcs">
                                    <p class="comments"></p>
                                    <label for="nbrepcs">Nombre de pièces</label>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-floating mb-3 ml-3 d-flex justify-content-center">
                                    <button class="btn btn-primary btn-sm me-3" id="commande_lavage">
                                        <span class="material-symbols-outlined">add_circle</span>
                                    </button>
                                </div>
                            </div>
                        </div>


                        <div class="row g-2 align-items-center">
                            <div class="col-md-5">
                                <div class="form-floating mb-3">
                                    <input type="search" class="form-control" id="searchProd">
                                    <p class="comments"></p>
                                    <div id="prodList"></div>
                                    <input type="hidden" id="prodId">
                                    <label for="searchProd">Nom Produit</label>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="form-floating mb-3">
                                <select class="form-select" id="Utilisation"aria-label="Utilisation">
                                    <option value="Première fois">Première fois</option>
                                    <option value="En cours">En cours</option>
                                    <option value="Dernière fois">Dernière fois</option>
                                </select>
                                <label for="Utilisation">Utilisation</label>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-floating mb-3 ml-3 d-flex justify-content-center">
                                    <button class="btn btn-primary btn-sm me-3" id="lavage_produit">
                                        <span class="material-symbols-outlined">add_circle</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="machine" name="machine">
                            <p class="comments"></p>
                            <label for="machine">Type de  machine</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="masse" name="masse">
                            <p class="comments"></p>
                            <label for="masse">Masse du lavage</label>
                        </div>

                        <div class="form-floating mb-3">
                            <textarea  class="form-control" id="comlav" name="comlav" rows="10" cols="5"></textarea>
                            <label for="comlav">Commentaire sur le lavage</label>
                        </div>
                    </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Annuler</button>
                        <button type="button" class="btn btn-success" name="create" id="create"> Démarrer 
                    </div>
                    </div>
                    </div>
                </div>

                </div> 
            </main>
        </div>


    </body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.4/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../js/lavage.js"></script>
    <script src="../../js/board.js"></script>
</html>