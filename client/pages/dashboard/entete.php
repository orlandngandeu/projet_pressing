<?php 
echo '
<aside class="main-sidebar">
<header class="aside-header">
                    <div class="brand">
                        <img src="../../images/orl.png" alt="Logo"/>
                        <h3>ORLXIO</h3>
                    </div>
                    <div class="close" id="closeSidebar">
                        <span class="material-icons-sharp"> close </span>
                    </div>
                </header>

                <div class="sidebar" id="sidebar">
                    <ul class="list-items">
                        <li class="item">
                            <a href="../dashboard/dashbord.php">
                                <span class="material-icons-sharp">
                                    dashboard
                                </span>
                                <span>Tableau de bord</span>
                            </a>
                        </li>
                        <li class="item">
                            <a href="../dashboard/client.php">
                                <span class="material-icons-sharp">
                                    people
                                </span>
                                <span>Clients</span>
                            </a>
                        </li>
                        <li class="item">
                            <a href="../dashboard/commande.php">
                                <span class="material-icons-sharp">
                                    shopping_cart
                                </span>
                                <span>Commandes</span>
                            </a>
                        </li>

                        <li class="item">
                            <div class="hamburger">
                                <a href="#">
                                    <span class="material-icons-sharp">
                                        inventory
                                    </span>
                                    <span>facturation</span>
                                </a>
                                <div class="menu-list">
                                    <a href="../dashboard/listing.php" title="Nouveau listing"><span class="material-symbols-outlined">contract</span>
                                    <p>Listing</p>
                                    <span>></span>
                                    </a>
    
                                    <a href="../dashboard/facture.php" title="Facture"><span class="material-symbols-outlined">scan</span>
                                        <p>Factures</p>
                                        <span>></span>
                                    </a>
                                </div>
                            </div>
                        </li>

                        <li class="item">
                            <a href="../dashboard/recu.php">
                                <span class="material-symbols-outlined">
                                    payments
                                </span>
                                <span>Payements</span>
                            </a>
                        </li>

                        <li class="item">
                            <div class="hamburger">
                                <a href="#">
                                 <span class="material-symbols-outlined">
                                  manage_history
                                  </span>
                                  <span>Traitement</span>
                                </a>
                                <div class="menu-list">
                                    <a href="../dashboard/lavage.php" title="Nouveau lavage">
                                    <span class="material-symbols-outlined">local_laundry_service</span>
                                    <p>Lavage</p>
                                    <span>></span>
                                    </a>
    
                                    <a href="../dashboard/sechage.php" title="nouveau sechage">
                                    <span class="material-symbols-outlined">wb_sunny</span>
                                        <p>séchage</p>
                                        <span>></span>
                                    </a>

                                    <a href="../dashboard/repassage.php" title="Nouveau repassage">
                                    <span class="material-symbols-outlined">iron</span>
                                        <p>Repassage</p>
                                        <span>></span>
                                    </a>

                                    <a href="../dashboard/emballage.php" title="Nouveau empaquettage">
                                    <span class="material-symbols-outlined">package</span>
                                        <p>Emballage</p>
                                        <span>></span>
                                    </a>
                                </div>

                            </div>
                        </li>

                        <li class="item">
                            <a href="./users.php">
                                <span class="material-icons-sharp">
                                    textsms
                                </span>
                                <span>Messagerie</span>
                                <span class="message-count">17</span>
                            </a>
                        </li>

                        <li class="item">
                            <a href="#">
                                <span class="material-icons-sharp">
                                    report
                                </span>
                                <span>Rapports</span>
                            </a>
                        </li>

                        <li class="item">
                            <a href="#" class="">
                                <span class="material-icons-sharp">
                                    insights
                                </span>
                                <span>Statistiques</span>
                            </a>
                        </li>
                        
                        
                        <li class="item">
                            <div class="hamburger">
                                <a href="#">
                                    <span class="material-icons-sharp"> add </span>
                                    <span>Nouveau</span>
                                </a>
                                <div class="menu-list">
                                    <a href="../dashboard/respon.php" title="Nouveau responsable"><span class="material-symbols-outlined">manage_accounts</span>
                                    <p>responsable</p>
                                    <span>></span>
                                    </a>
    
                                    <a href="../dashboard/depense.php" title="nouvelle depense"><span class="material-symbols-outlined">send_money</span>
                                        <p>dépenses</p>
                                        <span>></span>
                                    </a>

                                    <a href="../dashboard/produit.php" title="nouveau produit"><span class="material-symbols-outlined">production_quantity_limits</span>
                                        <p>Produits</p>
                                        <span>></span>
                                    </a>

                                    <a href="../dashboard/livraisons.php" title="depense"><span class="material-symbols-outlined">done_all</span>
                                        <p>Livraisons</p>
                                        <span>></span>
                                    </a>
                                </div>

                            </div>
                        </li>

                        <li class="item">
                            <a href="../../../server/php/logout.php?logout_id='. $_SESSION['unique_id'].'">
                                <span class="material-icons-sharp">
                                    logout
                                </span>
                                <span>Se Deconnecter</span>
                            </a>
                        </li>
                    </ul>
                </div>                
</aside>'
?>


<script>
document.addEventListener('DOMContentLoaded', function () {
  const links = document.querySelectorAll('.list-items li.item a');

  function getFileName(url) {
    let parts = url.split('/');
    return parts[parts.length - 1];
  }

  function setActiveLink() {
    const currentPage = getFileName(window.location.pathname);

    links.forEach(link => {
      if (getFileName(link.getAttribute('href')) === currentPage) {
        link.classList.add('active');
        const isSubMenuLink = link.closest('.menu-list');
        if(isSubMenuLink)
        {
            const linkAbove = isSubMenuLink.previousElementSibling;
            if (linkAbove) {
                linkAbove.classList.add('active');
            }
        }
      } else {
        link.classList.remove('active');
      }
    });
  }

  setActiveLink();

  links.forEach(link => {
    link.addEventListener('click', function (event) {
      
      links.forEach(link => link.classList.remove('active'));

      // Ajouter la classe active sur le lien cliqué
      this.classList.add('active');

      // Mettre à jour le lien actif après le clic
      setActiveLink();
    });
  });
});
</script>





