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
        <title>ORLXIO emballage</title>
    </head>
    <body>

        <div class="dashboard-container">
            <!-- Sidebar -->
            <?php include "./entete.php";?>

            <main class="main-container">
                <div class="sujet">
                    <h1 class="main-title">Gestion des emballages</h1>
                

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
                            <div><h5 class="fw-bold md-8">Listes des emballages</h5></div>
                            <div class="operation">
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-primary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#createModal" id="clicknew">
                                    <span class="material-symbols-outlined">add_circle</span> Planifier</button>
                                    <a href="../../../server/php/emballg.php?action=export" class="btn btn-success btn-sm" id="export">
                                    <span class="material-symbols-outlined">export_notes</span> Exporter</a>
                                </div>
                            </div>
                        </div>
                        <div class="ligne"></div>
                        <div class="row">
                            <div class="table-responsive" id="orderTable">
                                <h3 class="text-success text-center">Chargement des emballages...</h3>
                            </div>
                            </div>        
                    </section>
                            
                            
                    <!-- Modal -->
                    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header" id="titreform">
                                    <h5 class="modal-title" id="createModalLabel">Planifier un emballage</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <form id="formOrder" action="" method="post">

                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="id_rep" name="id_rep">
                                            <p class="comments"></p>
                                            <label for="id_rep">Identifiant du repassage</label>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <textarea  class="form-control" id="comEmb" name="comEmb" rows="10" cols="5"></textarea>
                                            <label for="comEmb">Commentaire sur l'emballage</label>
                                        </div>
                                    </form>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Annuler</button>
                                        <button type="button" class="btn btn-success" name="create" id="create"> Démarrer 
                                    </div>
                                </div>

                            </div> 
                        </div>    
                    </div>


                    <!-- updateModal -->
                    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header" id="titreform">
                            <h5 class="modal-title" id="updateModalLabel">Modifier les emballages</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                            <form id="formupdateOrder" action="" method="post">
            
                                <input type="hidden" name="id_emballage" id="id_emballage">
            
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="id_repass" name="id_repass" readonly>
                                    <label for="id_repass">Identifiant du repassage</label>
                                </div>
            
                                <div class="row g-2">
                                    <div class="col-md">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="classiq_petit" name="classiq_petit">                                      
                                            <label for="classiq_petit">classiques petits</label>
                                        </div>
                                    </div>
            
                                    <div class="col-md">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="classiq_moyen" name="classiq_moyen">                                      
                                            <label for="classiq_moyen">classiques moyens</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-md">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="prestige_petit" name="prestige_petit">                                      
                                            <label for="prestige_petit">prestiges petits</label>
                                        </div>
                                    </div>
            
                                    <div class="col-md">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="prestige_moyen" name="prestige_moyen">                                      
                                            <label for="prestige_moyen">prestiges moyens</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-md">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="emballage_petit" name="emballage_petit">                                      
                                            <label for="emballage_petit">Emballages petits</label>
                                        </div>
                                    </div>
            
                                    <div class="col-md">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="emballage_grand" name="emballage_grand">                                      
                                            <label for="emballage_grand">Emballages grands</label>
                                        </div>
                                    </div>
                                </div>
            
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="veste" name="veste">
                                    <label for="veste">Nombre de plastiques pour vestes </label>
                                </div>
            
                            </form>
            
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Annuler</button>
                            <button type="button" class="btn btn-primary" name="update" id="update"> Mettre à jour  <span class="material-symbols-outlined">browser_updated</span></button>
                            </div>
                        </div>
                        </div>
                    </div> 

                </div>
            <main>
        </div>

    </body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.4/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../js/emball.js"></script>
    <script src="../../js/board.js"></script>
</html>