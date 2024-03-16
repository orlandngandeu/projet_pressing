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
        <title>ORLXIO commande</title>
    </head>
    <body>

        <div class="dashboard-container">
            <!-- Sidebar -->
            <?php include "./entete.php";?>

            <main class="main-container">
                <div class="sujet">
                    <h1 class="main-title">Gestion des Commandes</h1>
           

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
                    </aside>
                </div>

                <div class="contenu">
                    <div class="contenu">
                        <section class="container py-5">
                            <div class="ligne"></div>
                            <div class="entreligne">
                                <div>
                                    <h5 class="fw-bold md-8">Listes des Commandes</h5>
                                </div>
                                <div class="operation">
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-primary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#createModal" id="clicknew"><span class="material-symbols-outlined">
                                            add_circle
                                            </span> Nouveau</button>
                                        <a href="../../../server/php/trait_com.php?action=export" class="btn btn-success btn-sm" id="export"><span class="material-symbols-outlined">
                                            export_notes
                                            </span> Exporter</a>
                
                                    </div>
                                </div>
                            </div>
                            <div class="ligne"></div>
                            <div class="row">
                                <div class="table-responsive" id="orderTable">
                                    <h3 class="text-success text-center">Chargement des commandes...</h3>
                                </div>
                            </div>
                            
                        </section>
                    
                    
                        <!-- Modal -->
                        <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="createModalLabel">Ajouter une commande</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                
                
                                <form id="formOrder" action="" method="post" autocomplete="off">
                
                                    <div class="form-floating mb-3">
                                        <input type="search" class="form-control" id="clientSearch" name="clientSearch" autocomplete="off">
                                        <p class="comments"></p>
                                        <div id="clientList"></div>
                                    <input type="hidden" id="clientId" name="clientId">
                                        <label for="clientSearch">Nom du client</label>
                                    </div>
                
                                    <div class="form-floating mb-3">
                                        <input type="search" class="form-control" id="respoSearch" name="respoSearch" autocomplete="off">
                                        <p class="comments"></p>
                                        <div id="respoList"></div>
                                    <input type="hidden" id="respoId" name="respoId">
                                        <label for="respoSearch">Nom du responsable</label>
                                    </div>
                
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="nbrepcs" name="nbrepcs">
                                        <p class="comments"></p>
                                        <label for="nbrepcs">Nombre de pièces</label>
                                    </div>
                
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" id="comments" name="comments" rows="10" cols="5"></textarea>
                                        <label for="comments">Observations</label> 
                                    </div>
                
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="status" aria-label="status" name="status">
                                            <option value="En attente">En attente</option>
                                            <option value="En cours" >En cours</option>
                                            <option value="Prêt">Pret</option>
                                            <option value="Prêt">livre</option>
                                        </select>
                                        <label for="status">Status</label>
                                    </div>
                
                                </form>
                
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Annuler</button>
                                <button type="button" class="btn btn-primary" name="create" id="create"> Ajouter <span class="material-symbols-outlined">add</span></button>
                                </div>
                            </div>
                            </div>
                        </div>
                
                
                
                        <!-- updateModal -->
                        <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="updateModalLabel">Modifier une commande</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                
                                <form id="formupdateOrder" action="" method="post">
                
                                    <input type="hidden" id="commUPId" name="commUPId">
                
                                    <div class="form-floating mb-3">
                                        <input type="search" class="form-control" id="clientUPSearch" name="clientUPSearch" autocomplete="off" readonly>
                                        <div id="clientUPList"></div>
                                    <input type="hidden" id="clientUPId" name="clientUPId">
                                        <label for="clientUPSearch">Nom du client</label>
                                    </div>
                
                                    <div class="form-floating mb-3">
                                        <input type="search" class="form-control" id="respoUPSearch" name="respoUPSearch" autocomplete="off" readonly>
                                        <div id="respoUPList"></div>
                                    <input type="hidden" id="respoUPId" name="respoUPId">
                                        <label for="respoUPSearch">Nom du responsable</label>
                                    </div>
                
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="nbreUPpcs" name="nbreUPpcs">
                                        <p class="commentsup"></p>
                                        <label for="nbreUPpcs">Nombre de pièces</label>
                                    </div>
                
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control" id="EntryUPdate" name="EntryUPdate">
                                        <p class="commentsup"></p>
                                        <label for="EntryUPdate">Date d'entrée</label>
                                    </div>
                
                                    <div class="form-floating mb-3">
                                        <input type="time" class="form-control" id="EntryUPhour" name="EntryUPhour">
                                        <p class="commentsup"></p>
                                        <label for="EntryUPhour">Heure d'entrée</label>
                                    </div>
                
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control" id="dateUPlivraison" name="dateUPlivraison">
                                        <p class="commentsup"></p>
                                        <label for="dateUPlivraison">Date de livraison</label>
                                    </div>
                
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" id="commentsUP" name="commentsUP" rows="10" cols="5"></textarea>  
                                        <label for="commentsUP">Observations</label> 
                                    </div>
                
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="statusUP" aria-label="statusUP" name="statusUP">
                                            <option value="En attente">En attente</option>
                                            <option value="En cours" >En cours</option>
                                            <option value="Prêt">Prèt</option>
                                            <option value="Prêt">Livre</option>
                                        </select>
                                        <label for="statusUP">Status</label>
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
            </main>
        </div> 


    </body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.4/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../js/script_com.js"></script>
    <script src="../../js/board.js"></script>
</html>