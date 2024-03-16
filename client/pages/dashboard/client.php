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
        <title>ORLXIO client</title>
    </head>
    <body>

        <div class="dashboard-container">
            <!-- Sidebar -->
            <?php include "./entete.php";?>

            <main class="main-container">
                <div class="sujet">
                    <h1 class="main-title">Gestion des clients</h1>
           

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
                    <section class="container py-5">
                        <div class="ligne"></div>
                        <div class="entreligne">
                            <div>
                                <h5 class="fw-bold md-8">Listes des clients</h5>
                            </div>
                            <div class="operation">
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-primary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#createModal"><span class="material-symbols-outlined">
                                        add_circle
                                        </span> Nouveau</button>
                                    <a href="../../../server/php/trait_client.php?action=export" class="btn btn-success btn-sm" id="export"><span class="material-symbols-outlined">
                                        export_notes
                                        </span> Exporter</a>
            
                                </div>
                            </div>
                        </div>
                        <div class="ligne"></div>
                        <div class="row">
                            <div class="table-responsive" id="orderTable">
                                <h3 class="text-success text-center">Chargement des clients...</h3>
                            </div>
                        </div>
                        
                    </section>
                    
                    
                    <!-- Modal -->
                    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header" id="titreform">
                            <h5 class="modal-title" id="createModalLabel">Ajouter un client</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                            <form id="formOrder" action="" method="post">
            
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="namecustomer" name="namecustomer">
                                    <p class="comments"></p>
                                    <label for="namecustomer">Nom du client</label>
                                </div>
            
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="surnamecustomer" name="surnamecustomer">
                                    <p class="comments"></p>
                                    <label for="surnamecustomer">Prénom du client</label>
                                </div>
            
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="adresse" name="adresse">
                                    <p class="comments"></p>
                                    <label for="adresse">Adresse du client</label>
                                </div>
            
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email" name="email">
                                    <p class="comments"></p>
                                    <label for="email">Email du client</label>
                                </div>
            
                                <div class="row g-2">
                                    <div class="col-md">
                                        <div class="form-floating mb-3">
                                            <input type="tel" class="form-control" id="telephone" name="telephone">
                                            <p class="comments"></p>
                                            <label for="telephone">Téléphone</label>
                                        </div>
                                    </div>
            
                                    <div class="col-md">
                                        <div class="form-floating">
                                            <select class="form-select" id="state" aria-label="state" name="sexe">
                                                <option value="Masculin">Masculin</option>
                                                <option value="Féminin">Féminin</option>
                                            </select>
                                            <label for="state">Sexe</label>
                                        </div>
                                    </div>
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
                            <div class="modal-header" id="titreform">
                            <h5 class="modal-title" id="updateModalLabel">Modifier un client</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                            <form id="formupdateOrder" action="" method="post">
            
                                <input type="hidden" name="id" id="bill_id">
            
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="nameUPcustomer" name="nameUPcustomer">
                                    <p class="commentsup"></p>
                                    <label for="nameUPcustomer">Nom du client</label>
                                </div>
            
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="surnameUPcustomer" name="surnameUPcustomer">
                                    <p class="commentsup"></p>
                                    <label for="surnameUPcustomer">Prénom du client</label>
                                </div>
            
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="adresseUP" name="adresseUP">
                                    <p class="commentsup"></p>
                                    <label for="adresseUP">Adresse du client</label>
                                </div>
            
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="emailUP" name="emailUP">
                                    <p class="commentsup"></p>
                                    <label for="emailUP">Email du client</label>
                                </div>
            
                                <div class="row g-2">
                                    <div class="col-md">
                                        <div class="form-floating mb-3">
                                            <input type="tel" class="form-control" id="telephoneUP" name="telephoneUP">
                                            <p class="commentsup"></p>
                                            <label for="telephoneUP">Téléphone</label>
                                        </div>
                                    </div>
            
                                    <div class="col-md">
                                        <div class="form-floating">
                                            <select class="form-select" id="stateUP" aria-label="stateUP" name="sexeUP">
                                                <option value="Masculin">Masculin</option>
                                                <option value="Féminin">Féminin</option>
                                            </select>
                                            <label for="stateUP">Sexe</label>
                                        </div>
                                    </div>
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
    <script src="../../js/scriptCL.js"></script>
    <script src="../../js/board.js"></script>
</html>