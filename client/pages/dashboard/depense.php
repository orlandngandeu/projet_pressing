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
        <title>ORLXIO Spend</title>
    </head>
    <body>


        <div class="dashboard-container">
            <!-- Sidebar -->
            <?php include "./entete.php";?>

            <main class="main-container">
                <div class="sujet">
                    <h1 class="main-title">Gestion des Dépenses</h1>
           

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
                                    <h5 class="fw-bold md-8">Listes des dépenses</h5>
                                </div>
                                <div class="operation">
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-primary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#createModal"><span class="material-symbols-outlined">
                                            add_circle
                                            </span> Nouveau</button>
                                        <a href="../../../server/php/depense.php?action=export" class="btn btn-success btn-sm" id="export"><span class="material-symbols-outlined">
                                            export_notes
                                            </span> Exporter</a>
                
                                    </div>
                                </div>
                            </div>
                            <div class="ligne"></div>
                            <div class="row">
                                <div class="table-responsive" id="orderTable">
                                    <h3 class="text-success text-center">Chargement des dépenses...</h3>
                                </div>
                            </div>
                            
                        </section>
                
                
                        <!-- Modal -->
                        <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="createModalLabel">Nouvelle dépense</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                
                
                                <form id="formOrder" action="" method="post" autocomplete="off">
                
                                    <div class="form-floating mb-3">
                                        <input type="search" class="form-control" id="emplSearch" name="emplSearch" autocomplete="off">
                                        <div id="emplList"></div>
                                    <input type="hidden" id="emplId" name="emplId">
                                        <label for="emplSearch">Nom du l'employée</label>
                                    </div>               
                
                
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" id="desc" name="desc" rows="10" cols="5"></textarea>
                                        <p class="comments"></p>  
                                        <label for="desc">Description</label> 
                                    </div>
                
                
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="amount" name="amount">
                                        <p class="comments"></p>
                                        <label for="amount">Montant</label>
                                    </div>
                
                                </form>
                
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Annuler</button>
                                <button type="button" class="btn btn-primary" name="create" id="create"> Ajouter <i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                            </div>
                        </div>
                
                
                
                        <!-- updateModal -->
                        <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="updateModalLabel">Modifier une dépense</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                
                                <form id="formupdateOrder" action="" method="post">
                
                                    <input type="hidden" id="depUP" name="depUP">
                
                                    <div class="form-floating mb-3">
                                        <input type="search" class="form-control" id="emplUPSearch" name="emplUPSearch" autocomplete="off" readonly>
                                        <div id="emplUPList"></div>
                                    <input type="hidden" id="emplUPId" name="emplUPId">
                                        <label for="emplUPSearch">Nom du l'employée</label>
                                    </div>
                
                
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" id="descUP" name="descUP" rows="10" cols="5"></textarea> 
                                        <p class="commentsup"></p> 
                                        <label for="descUP">Description</label> 
                                    </div>
                
                
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="amountUP" name="amountUP">
                                        <p class="commentsup"></p>
                                        <label for="amountUP">Montant</label>
                                    </div>
                
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control" id="dateUP" name="dateUP">
                                        <label for="dateUP">Date</label>
                                    </div>
                
                                    <div class="form-floating mb-3">
                                        <input type="time" class="form-control" id="heureUP" name="heureUP">
                                        <label for="heureUP">Heure</label>
                                    </div>
                            
                                </form>
                
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Annuler</button>
                                <button type="button" class="btn btn-primary" name="update" id="update"> Mettre à jour  <i class="fas fa-sync"></i></button>
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
    <script src="../../js/board.js"></script>
    <script src="../../js/depense.js"></script>
</html>