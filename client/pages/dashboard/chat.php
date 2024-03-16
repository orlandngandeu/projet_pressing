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
            <section class="chat-area">
                <header>
                    <?php 
                    include_once "../../../server/php/config.php";
                    $user_id = mysqli_real_escape_string($conn, $_GET["user_id"]);
                    $sql = mysqli_query($conn, "SELECT * FROM employé WHERE unique_id = {$user_id}");
                    if(mysqli_num_rows($sql)>0)
                    {
                        $row = mysqli_fetch_assoc($sql);
                    }
                    ?>
                    <a href="./users.php" class="back-icon"><span class="material-symbols-outlined">arrow_back</span></a>
                    <img src="../../images/pictures/<?php echo $row['image']?>" alt="">
                    <div class="details">
                        <span><?php echo $row['userName_employé']?></span>
                        <p><?php echo $row['status']?></p>
                    </div>
                </header>
                <div class="chat-box">
  
                </div>

                <form action="#" class="typing-area">
                    <input type="hidden" name="outgoing_id" value="<?php echo $_SESSION['unique_id'];?>">
                    <input type="hidden" name="incoming_id" value="<?php echo $user_id;?>">
                    <input type="text" name="message" class="input-field" placeholder="Ecrivez votre message...">
                    <button><span class="material-symbols-outlined">send</span></button>
                </form>
            </section>
            </div>
            </div>
        <main>
    <div>
    
    
</body>
<script src="../../js/chat.js"></script>
<script src="../../js/board.js"></script>
</html>