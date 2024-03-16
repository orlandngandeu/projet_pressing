<?php
 session_start();
 if(!isset($_SESSION['unique_id'])){
    header("location: ../employee/connexion.php");
 } 
?>

<?php include_once "./header.php";?>
<body>
    <div class="dashboard-container">
      <?php include "./entete.php";?>

        <main class="main-container">
            <div class="sujet">
                <h1 class="main-title">Orlxio Discussion</h1>


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


            <div class="conwrappw">
            <div class="wrapper">
                <section class="users">
                    <header>
                        <?php 
                        include_once "../../../server/php/config.php";
                        $sql = mysqli_query($conn, "SELECT * FROM employé WHERE unique_id = {$_SESSION['unique_id']}");
                        if(mysqli_num_rows($sql)>0)
                        {
                            $row = mysqli_fetch_assoc($sql);
                        }
                        ?>
                            <div class="content">
                                <img src="../../images/pictures/<?php echo $row['image']?>" alt="">
                                <div class="details">
                                    <span><?php echo $row['userName_employé']?></span>
                                    <p><?php echo $row['status']?></p>
                                </div>
                            </div>
                            <a href="../../../server/php/logout.php?logout_id=<?php echo $row['unique_id']?>" class="logout">Logout</a>
                    </header>
                    <div class="search">
                        <span class="text">Selectionner un utilisateur.</span>
                        <input type="text" placeholder="Entrer le nom a chercher...">
                        <button><span class="material-symbols-outlined">
                        search
                        </span></button>
                    </div>

                    <div class="users-list">
                    </div>
                </section>
            </div>
            </div>
        </main>
    </div>


   
    
</body>
<script src="../../js/users.js"></script>
<script src="../../js/board.js"></script>
</html>