<?php 
class Database
{

    private $host = "mysql:host=localhost;dbname=u846259582_pressingdb";
    private $user = "u846259582_orlandngandeu";
    private $pswd = "Exp(E=mc2)!";

    private function getconnexion()
    {
        try
        {
          $conn =  new PDO($this->host ,$this->user, $this->pswd);
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          return $conn;
        }catch(PDOException $e)  
        {
            die('Erreur :'.$e->getMessage());
        }
    }

    public function create(int $unique_id, string $nomComplet_employé, string $userName_employé, string $telephone_employé, int $Age, 
    string $Email, string $mot_passe, string $image, string $status)
    {

        $mot_passe_hash = password_hash($mot_passe, PASSWORD_DEFAULT);

        $q = $this->getconnexion()->prepare("INSERT INTO employé (unique_id,nomComplet_employé, userName_employé, telephone_employé,
         Age, Email, mot_passe_hash,image,status) VALUES (:uniq, :nomComplet, :userName, :telephone, :age, :email, :mot_passe_hash,:img,
         :statusq)");
        return $q->execute([
            'nomComplet' => $nomComplet_employé,
            'userName' => $userName_employé,
            'telephone' => $telephone_employé,
            'age' => $Age,
            'email' => $Email,
            'mot_passe_hash' => $mot_passe_hash,
            'uniq' => $unique_id,
            'img' => $image,
            'statusq' => $status
        ]);
    }

    public function select_employee(string $username)
    {
       $query = $this->getconnexion()->prepare("SELECT * FROM employé WHERE userName_employé = :username");
       $query -> bindParam(':username', $username);
       $query->execute();

       return $query->fetch(PDO::FETCH_ASSOC);
    }


    public function select_nom_employee(int $username)
    {
       $query = $this->getconnexion()->prepare("SELECT nomComplet_employé FROM employé WHERE unique_id = :uniq");
       $query -> bindParam(':uniq', $username);
       $query->execute();

       return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function update_status_employee(string $statut, int $uniq_id)
    {
        $query = $this->getconnexion()->prepare("UPDATE employé SET status = :statut WHERE unique_id = :uniq");
        $query -> bindParam(':statut', $statut);
        $query -> bindParam(':uniq', $uniq_id);
        $query->execute();
    }

    public function update_status_commande(int $id)
    {
        $query = $this->getconnexion()->prepare("UPDATE commandes SET statut =\"Repassage\" WHERE id_commande = :id");
        $query -> bindParam(':id', $id);
        $query->execute();
    }



    public function select_email(string $email)
    {
        $query = $this->getconnexion()->prepare("SELECT * FROM employé WHERE Email = :email");
        $query -> bindParam(':email',$email);
        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function select_phone(string $phone)
    {
        $query = $this->getconnexion()->prepare("SELECT * FROM employé WHERE telephone_employé = :phone");
        $query -> bindParam(':phone',$phone);
        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function update_codeMail(string $mail, int $code)
    {
        $query = $this->getconnexion()->prepare("UPDATE employé SET code_verify = :code WHERE Email = :email");
        $query -> bindParam(':code', $code);
        $query -> bindParam(':email', $mail);
        $query->execute();
    }

    public function update_codeTel(string $phone, int $code)
    {
        $query = $this->getconnexion()->prepare("UPDATE employé SET code_verify = :code WHERE telephone_employé = :phone");
        $query -> bindParam(':code',$code);
        $query -> bindParam(':phone',$phone);
        $query->execute();
    }


    public function update_repassage_code(int $rep,int $code)
    {
        $query = $this->getconnexion()->prepare("UPDATE repassage SET pieces = :code WHERE id_repassage = :id");
        $query -> bindParam(':code',$code);
        $query -> bindParam(':id',$rep);
        $query->execute();
    }

    public function select_code(string $reference)
    {
        $q = $this->getconnexion()->prepare("SELECT code_verify FROM employé WHERE Email = :ref OR telephone_employé = :ref");
        $q -> bindParam(':ref',$reference);
        $q -> execute();

        return $q->fetch(PDO::FETCH_ASSOC);
    }

    public function update_code(string $reference)
    {
        $q = $this->getconnexion()->prepare("UPDATE employé  SET code_verify = 0 WHERE telephone_employé = :ref OR Email = :ref");
        $q -> bindParam(':ref',$reference);
        $q -> execute();
    }

    public function update_password(string $ref, string $element)
    {
        $mot_passe_hash = password_hash($ref, PASSWORD_DEFAULT);
        $q = $this->getconnexion()->prepare("UPDATE employé SET mot_passe_hash = :mot_passe_hash WHERE telephone_employé = :element OR Email = :element");
        $q -> bindParam(':mot_passe_hash',$mot_passe_hash);
        $q ->bindParam(':element',$element);
        $q -> execute();
    }


    public function enregistrer(string $nom_client, string $prenom_client, string $adresse, string $telephone, string $email, string $sexe, int $uniq_id)
    {
        $q = $this->getconnexion()->prepare("INSERT INTO clients (nom_client, surname_client, sexe, Adresse, Telephone, Email, unique_id) VALUES 
        (:nom_client, :prenom_client, :sexe, :adresse, :telephone, :email, :uniq)");
        return $q->Execute([
            'nom_client' => $nom_client,
            'prenom_client' => $prenom_client,
            'sexe' => $sexe,
            'adresse' => $adresse,
            'telephone' => $telephone,
            'email' => $email,
            'uniq' => $uniq_id
        ]);
    }

    public function enregistrerwithoutemail(string $nom_client, string $prenom_client, string $adresse, string $telephone, string $sexe, int $uniq_id)
    {
        $q = $this->getconnexion()->prepare("INSERT INTO clients (nom_client, surname_client, sexe, Adresse, Telephone, uniq_id) VALUES
        (:nom_client, :prenom_client, :sexe, :adresse, :telephone, :uniq)");
        return $q->Execute([
            'nom_client' => $nom_client,
            'prenom_client' => $prenom_client,
            'sexe' => $sexe,
            'adresse' => $adresse,
            'telephone' => $telephone,
            'uniq' => $uniq_id
        ]);
    }
    
    public function getSinglEemballage(int $id){
        $q = $this->getconnexion()->prepare("SELECT * FROM emballage WHERE id_emballage=:id_emballage");
        $q->execute(['id_emballage' => $id]);
        return $q->fetch(PDO::FETCH_OBJ);
    }

    public function getposition(int $position)
    {
        $stmt = $this->getconnexion()->prepare('SELECT position FROM employé WHERE unique_id = :uniq');
        $stmt->execute(['uniq' => $position]);
        return  $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function avoir_commande(int $id_client){
        $q = $this->getconnexion()->prepare("SELECT * FROM commandes WHERE id_client=:id_cl");
        $q->execute(['id_cl' => $id_client]);
        return $q->fetch(PDO::FETCH_OBJ);
    }

    
    public function avoir_listing(int $id_commande){
        $q = $this->getconnexion()->prepare("SELECT * FROM designation_listing WHERE id_commande=:id_comm");
        $q->execute(['id_comm' => $id_commande]);
        return $q->fetch(PDO::FETCH_OBJ);
    }


    public function read_client()
    {
        return $this->getconnexion()->query('SELECT * FROM clients ORDER BY id_client')->fetchAll(PDO::FETCH_OBJ);
    }

    public function read_respo()
    {
        return $this->getconnexion()->query('SELECT * FROM responsable ORDER BY id_responsable')->fetchAll(PDO::FETCH_OBJ);
    }

    public function countBills(): int
    {
        return (int)$this->getconnexion()->query("SELECT COUNT(id_client) AS count FROM clients")->fetch()[0];
    }
    public function countBillsr(): int
    {
        return (int)$this->getconnexion()->query("SELECT COUNT(id_responsable) AS count FROM responsable")->fetch()[0];
    }

    public function getSingleBill(int $id){
        $q = $this->getconnexion()->prepare("SELECT * FROM clients WHERE id_client=:id_client");
        $q->execute(['id_client' => $id]);
        return $q->fetch(PDO::FETCH_OBJ);
    }

    public function getSingleempq(int $id){
        $q = $this->getconnexion()->prepare("SELECT * FROM emballage WHERE id_emballage=:id_client");
        $q->execute(['id_client' => $id]);
        return $q->fetch(PDO::FETCH_OBJ);
    }

    public function getSingleBills(int $id){
        $q = $this->getconnexion()->prepare("SELECT * FROM responsable WHERE id_responsable=:id_responsable");
        $q->execute(['id_responsable' => $id]);
        return $q->fetch(PDO::FETCH_OBJ);
    }


    public function update_client(int $id,string $nom_client, string $prenom_client, string $adresse, string $telephone, string $email, string $sexe)
    {
        $q = $this->getconnexion()->prepare("UPDATE clients SET nom_client = :nom_client, surname_client = :prenom_client, sexe = :sexe, Adresse = :adresse, Telephone = :telephone, Email = :email  WHERE id_client = :id");
        return $q->Execute([
            'nom_client' => $nom_client,
            'prenom_client' => $prenom_client,
            'sexe' => $sexe,
            'adresse' => $adresse,
            'telephone' => $telephone,
            'email' => $email,
            'id' => $id
        ]);
    }
    
    public function update_emballage(int $id_emball,int $class_pet, int $class_moy, int $prest_petit, int $prest_moyen, 
    int $emb_petit, int $emb_moyen, int $veste)
    {
        $q = $this->getconnexion()->prepare("UPDATE emballage SET classique_petit = :class_pet, classique_moyen = :class_moy, prestige_petit = :prest_petit
        ,prestige_moyen = :prest_moyen, emballage_petit = :emb_petit, emballage_grand = :emb_moyen,
        veste = :veste  WHERE id_emballage = :id");
        return $q->Execute([
            'class_pet' => $class_pet,
            'class_moy' => $class_moy,
            'prest_petit' => $prest_petit,
            'prest_moyen' => $prest_moyen,
            'emb_petit' => $emb_petit,
            'emb_moyen' => $emb_moyen,
            'veste' => $veste,
            'id' => $id_emball
        ]);
    }
    
    

    public function update_respo(int $id,string $nom_respo, string $prenom_respo,string $telephone, float $pourcentage)
    {
        $q = $this->getconnexion()->prepare("UPDATE responsable SET nom_respo = :nom_respo, surname_respo = :prenom_respo, telephone = :telephone, pourcentage = :pourcentage  WHERE id_responsable = :id");
        return $q->Execute([
            'nom_respo' => $nom_respo,
            'prenom_respo' => $prenom_respo,
            'telephone' => $telephone,
            'pourcentage' => $pourcentage,
            'id' => $id
        ]);
    }

    public function delete_client(int $id): bool
    {
        $q = $this->getconnexion()->prepare("DELETE FROM clients WHERE id_client = :id");
        return $q->execute(['id' => $id]);
    }

    public function delete_respo(int $id): bool
    {
        $q = $this->getconnexion()->prepare("DELETE FROM responsable WHERE id_responsable = :id");
        return $q->execute(['id' => $id]);
    }




    public function save_resp(string $nom_resp, string $prenom_resp, string $telephone, float $pourc)
    {
        $q = $this->getconnexion()->prepare("INSERT INTO responsable (nom_respo, surname_respo, telephone, pourcentage) VALUES (:nom_resp, :prenom_resp, :telephone, :pourc)");
        return $q->Execute([
            'nom_resp' => $nom_resp,
            'prenom_resp' => $prenom_resp,
            'telephone' => $telephone,
            'pourc' => $pourc
        ]);
    }

    public function select_client($name)
    {
        $stmt = $this->getconnexion()->prepare("SELECT id_client, nom_client, surname_client FROM clients WHERE 
        CONCAT(nom_client,' ',surname_client) LIKE :nome");
        $stmt->execute(['nome' => "%$name%"]);
        return  $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function nom_prenom(int $id_client)
    {
        $stmt = $this->getconnexion()->prepare("SELECT nom_client, surname_client FROM clients WHERE 
        id_client = :nome");
        $stmt->execute(['nome' => $id_client]);
        return  $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function select_produit($prod)
    {
        $stmt = $this->getconnexion()->prepare("SELECT id_produit, nom_produit FROM produits WHERE nom_produit LIKE :nome");
        $stmt->execute(['nome' => "%$prod%"]);
        return  $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function select_respo($responsable)
    {
        $stmt =$this->getconnexion()->prepare("SELECT id_responsable, nom_respo, surname_respo FROM responsable WHERE CONCAT(nom_respo,' ',surname_respo) LIKE :nome");
        $stmt->execute(['nome'=>"%$responsable%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

  
    public function enreg_commande(int $id_client, int $id_respo, int $nbpcs, string $status, string $comments, int $uniq_id)
    {
        $currentDate = date('Y-m-d');
        $datelivraison = date('Y-m-d', strtotime('+3 days', strtotime($currentDate)));
        $q = $this->getconnexion()->prepare("INSERT INTO commandes (id_client, id_responsable, nombre_pieces, statut, date_livraison, 
        commentaire_employee, unique_id) VALUES (:id_client, :id_respo, :nbpcs, :statuss, :date_livraison, :comments, :uniq)");
        return $q->Execute([
            'id_client' => $id_client,
            'id_respo' => $id_respo,
            'nbpcs' => $nbpcs,
            'statuss' => $status,
            'date_livraison' => $datelivraison,
            'comments' => $comments,
            'uniq' => $uniq_id
        ]);

        
    }

    public function read_commande()
    {
        return $this->getconnexion()->query('SELECT id_commande, nom_client, surname_client, nom_respo, nombre_pieces, Entry_date,
        Entry_hour, date_livraison, commentaire_employee, statut
        FROM commandes
        INNER JOIN clients ON commandes.id_client = clients.id_client
        INNER JOIN responsable ON commandes.id_responsable = responsable.id_responsable
        ')->fetchAll(PDO::FETCH_OBJ);
    }

    public function countBillcom(): int
    {
        return (int)$this->getconnexion()->query("SELECT COUNT(id_commande) AS count FROM commandes")->fetch()[0];
    }


    public function getsinglecomm(int $id)
   {
        $q = $this->getconnexion()->prepare('SELECT commandes.id_commande, clients.nom_client, 
        clients.surname_client, commandes.id_client, responsable.nom_respo, commandes.id_responsable, 
        commandes.nombre_pieces, commandes.Entry_date, commandes.Entry_hour, commandes.date_livraison, 
        commandes.commentaire_employee, commandes.statut, commandes.unique_id, commandes.pieces_lavee
        FROM commandes
        INNER JOIN clients ON commandes.id_client = clients.id_client
        INNER JOIN responsable ON commandes.id_responsable = responsable.id_responsable
        WHERE commandes.id_commande = :id
        ');
        $q->execute(['id' => $id]);
        return $q->fetch(PDO::FETCH_OBJ);
   }


   public function update_commande(int $id,int $id_client, int $id_respo,int $pieces, string $entry_date, string $entryheure, string $statut, string $livraison, string $comments)
   {
       $q = $this->getconnexion()->prepare("UPDATE commandes SET id_client = :id_client, id_responsable = :id_respo, nombre_pieces = :pieces, Entry_date = :entry_date,  Entry_hour = :entryheure, statut = :statut, date_livraison = :livraison, commentaire_employee=:comments WHERE id_commande = :id");
       return $q->Execute([
           'id_client' => $id_client,
           'id_respo' => $id_respo,
           'pieces' => $pieces,
           'entry_date' => $entry_date,
           'entryheure' => $entryheure,
           'statut' => $statut,
           'livraison' => $livraison,
           'comments' => $comments,
           'id' => $id
       ]);
   }

    public function update_pieces_lavee(int $id,int $pieces_lavee)
   {
       $q = $this->getconnexion()->prepare("UPDATE commandes SET pieces_lavee = :pieces WHERE id_commande = :id");
       return $q->Execute([
           'pieces' => $pieces_lavee,
           'id' => $id
       ]);
   }

   public function remove_produit(int $id_prod)
   {
       $q = $this->getconnexion()->prepare("UPDATE produits SET quantity_stock =(quantity_stock-1) WHERE id_produit = :id");
       return $q->Execute(['id' => $id_prod]);
   }


   public function delete_commande(int $id): bool
   {
       $q = $this->getconnexion()->prepare("DELETE FROM commandes WHERE id_commande = :id");
       return $q->execute(['id' => $id]);
   }

   public function delete_listing(int $id): bool
   {
       $q = $this->getconnexion()->prepare("DELETE FROM designation_listing WHERE id_listing = :id");
       return $q->execute(['id' => $id]);
   }


   public function valididcomm(int $id)
   {
       $query = $this->getconnexion()->prepare("SELECT * FROM commandes WHERE id_commande = :id");
       $query -> bindParam(':id',$id);
       $query->execute();

       return $query->fetch(PDO::FETCH_ASSOC);
   }

   public function valididrep(int $id)
   {
       $query = $this->getconnexion()->prepare("SELECT * FROM repassage WHERE id_repassage = :id");
       $query -> bindParam(':id',$id);
       $query->execute();

       return $query->fetch(PDO::FETCH_ASSOC);
   }

   public function areAllLavagesPropre(int $id)
   {
        $query = $this->getconnexion()->prepare("
            SELECT commande_lavage.id_lavage
            FROM commande_lavage
            INNER JOIN planifier_lavage ON commande_lavage.id_lavage = planifier_lavage.id_lavage
            WHERE commande_lavage.id_commande = :id AND (planifier_lavage.proprete IS NULL OR planifier_lavage.proprete <> 'propre')
        ");

        $query->bindParam(':id', $id);
        $query->execute();

        return $query->rowCount() === 0;
   }


   public function areAllsechageParfait(int $id)
   {
        $query = $this->getconnexion()->prepare("
            SELECT commande_lavage.id_lavage
            FROM commande_lavage
            INNER JOIN sechage ON commande_lavage.id_lavage = sechage.id_lavage
            WHERE commande_lavage.id_commande = :id AND (sechage.etat_sechement IS NULL OR sechage.etat_sechement <> 'parfait')
        ");
        $query->bindParam(':id', $id);
        $query->execute();

        return $query->rowCount() === 0;
   }


   public function verifLavage(int $idLav)
   {
       $query = $this->getconnexion()->prepare("SELECT * FROM planifier_lavage WHERE id_lavage = :id
       AND statut = 'Terminée' AND proprete != \"\"");
       $query -> bindParam(':id',$idLav);
       $query->execute();

       return $query->fetch(PDO::FETCH_ASSOC);
   }


   
   public function save_list(int $id_Comm, string $list, float $quantify, float $unit, string $instruct, int $uniq)
   {
    $amount = $quantify * $unit;
       $q = $this->getconnexion()->prepare("INSERT INTO designation_listing (id_commande, Listing, 
       quantity, prix_unitaire, montant, Instructions_speciales,unique_id) 
       VALUES (:id_Comm, :list, :quantify, :unit, :amount, :instruct,:uniq)");
       return $q->Execute([
           'id_Comm' => $id_Comm,
           'list' => $list,
           'quantify' => $quantify,
           'unit' => $unit,
           'amount' => $amount,
           'instruct' => $instruct,
           'uniq' => $uniq
       ]);
   }

   public function insert_planifier_lavage($timestart, ?string $comments, string $etat, string $type, 
   float $masse, int $uniq)
   {
    $q = $this->getconnexion()->prepare("INSERT INTO planifier_lavage (temps_debut, commentaire_lavage, 
    statut, type_machine, masse, unique_id) VALUES (:tempsdebut, :comments, :etat, :machine, :kilo, :uniq)");
     return $q->Execute([
       'tempsdebut' => $timestart,
        'comments' => $comments,
        'etat' => $etat,
        'machine' => $type,
        ':uniq' => $uniq,
        'kilo' => $masse
       ]);
   }

   public function insert_sechage(int $idLav, $timestart, ?string $comments, string $statut, string $type, 
   int $uniq)
   {
    $q = $this->getconnexion()->prepare("INSERT INTO sechage (id_lavage, temps_debut, commentaires,
    statut, type_sechage, unique_id) VALUES (:id_lavage, :temps, :comments, :statut, :type_sec, :uniq)");
     return $q->Execute([
       'id_lavage' => $idLav,
        'temps' => $timestart,
        'statut' => $statut,
        'type_sec' => $type,
        ':uniq' => $uniq,
        'comments' => $comments
       ]);
   }

   public function insProdLav(int $id_lav, int $id_prod, string $usage)
   {
    $q = $this->getconnexion()->prepare("INSERT INTO produit_lavage (id_lavage, id_produit, utilisation_produit) 
    VALUES (:idLav, :id_prod, :utilisation)");
     return $q->Execute([
       'idLav' => $id_lav,
        'id_prod' => $id_prod,
        'utilisation' => $usage
       ]);
   }

   public function select_id_lavage($temps)
   {
    $q = $this->getconnexion()->prepare("SELECT id_lavage FROM planifier_lavage WHERE temps_debut=:temps");
    $q->execute(['temps' => $temps]);
    return $q->fetch(PDO::FETCH_OBJ);
   }

   public function insert_commande_lavage(int $id_lavage,int $id_comm, int $pieces)
   {
     $q = $this->getconnexion()->prepare("INSERT INTO commande_lavage (id_lavage, id_commande, nombre_pieces)
     VALUES (:lavage, :commande, :pieces)");
     return $q->Execute([
        'lavage' => $id_lavage,
        'commande' => $id_comm,
        'pieces' => $pieces
       ]);
   }


   public function update_commande_lavage(int $id_comm)
   {
       $q = $this->getconnexion()->prepare("UPDATE commandes SET statut =\"Lavage\" WHERE id_commande = :id");
       return $q->Execute(['id' => $id_comm]);
   }

   public function update_commande_emballage(int $id_rep)
   {
       $q = $this->getconnexion()->prepare("UPDATE commandes SET statut =\"Emballage\" WHERE id_commande = :id");
       return $q->Execute(['id' => $id_rep]);
   }



   public function sechage_statut(int $id_lav)
   {
       $q = $this->getconnexion()->prepare("SELECT id_commande FROM commande_lavage WHERE id_lavage=:id_lav");
       $q->execute(['id_lav' => $id_lav]);
       $result = $q->fetchAll(PDO::FETCH_ASSOC);
   
       if ($result) {
           foreach ($result as $comm) {
               $q = $this->getconnexion()->prepare("UPDATE commandes SET statut = 'sechage' WHERE id_commande = :id");
               $q->execute(['id' => $comm['id_commande']]);
           }
       }
   }
   


   public function insert_produit_lavage(int $id_lavage,int $id_produit, string $utilisation)
   {
     $q = $this->getconnexion()->prepare("INSERT INTO produit_lavage (id_lavage, id_produit, utilisation_produit)
     VALUES (:lavage, :produit, :usage)");
     return $q->Execute([
        'lavage' => $id_lavage,
        'produit' => $id_produit,
        'usage' => $utilisation
       ]);
   }

   public function read_list()
   {
       return $this->getconnexion()->query('SELECT * FROM designation_listing ORDER BY id_listing')->fetchAll(PDO::FETCH_OBJ);
   }

   public function read_lavage()
   {
       return $this->getconnexion()->query('SELECT * FROM planifier_lavage ORDER BY id_lavage')->fetchAll(PDO::FETCH_OBJ);
   }

   public function read_sechage()
   {
       return $this->getconnexion()->query('SELECT * FROM sechage ORDER BY id_sechage')->fetchAll(PDO::FETCH_OBJ);
   }

   public function read_repassage()
   {
       return $this->getconnexion()->query('SELECT * FROM repassage ORDER BY id_repassage')->fetchAll(PDO::FETCH_OBJ);
   }


   public function read_emballage()
   {
       return $this->getconnexion()->query('SELECT * FROM emballage ORDER BY id_emballage')->fetchAll(PDO::FETCH_OBJ);
   }



   public function getsinglelav(int $id)
   {
     $q = $this->getconnexion()->prepare("SELECT * FROM planifier_lavage WHERE id_lavage=:identifiant");
     $q->execute(['identifiant' => $id]);
     return $q->fetch(PDO::FETCH_OBJ);
   }


   
   public function getcommandeempaquettage(int $id)
   {
     $q = $this->getconnexion()->prepare("SELECT id_commande FROM repassage WHERE 
     id_repassage =
     (SELECT id_repassage FROM emballage WHERE id_emballage = :identifiant)
     ");
     $q->execute(['identifiant' => $id]);
     return $q->fetch(PDO::FETCH_ASSOC);
   }

   public function getsinglesec(int $id)
   {
     $q = $this->getconnexion()->prepare("SELECT * FROM sechage WHERE id_sechage=:identifiant");
     $q->execute(['identifiant' => $id]);
     return $q->fetch(PDO::FETCH_OBJ);
   }

   
   public function getsinglerep(int $id)
   {
        $q = $this->getconnexion()->prepare("SELECT repassage.id_repassage, repassage.temps_debut, repassage.temps_fin, 
        repassage.commentaires, repassage.statut, repassage.pieces, repassage.id_commande,
        client_info.nom_client, client_info.surname_client
        FROM repassage
        INNER JOIN commandes ON repassage.id_commande = commandes.id_commande
        INNER JOIN clients AS client_info ON commandes.id_client = client_info.id_client
        WHERE repassage.id_repassage = :identifiant
        ");
        $q->execute(['identifiant' => $id]);
        return $q->fetch(PDO::FETCH_OBJ);
   }

   public function getrepassage()
   {
        $q = $this->getconnexion()->prepare("SELECT repassage.id_repassage, repassage.temps_debut, repassage.temps_fin, 
        repassage.commentaires, repassage.statut, repassage.pieces, repassage.id_commande,
        client_info.nom_client, client_info.surname_client
        FROM repassage
        INNER JOIN commandes ON repassage.id_commande = commandes.id_commande
        INNER JOIN clients AS client_info ON commandes.id_client = client_info.id_client
        ORDER BY repassage.id_repassage");
        return $q->fetchAll(PDO::FETCH_OBJ);
   }

   
   public function commande_lav(int $id)
   {
     $q = $this->getconnexion()->prepare("SELECT commande_lavage.id_commande, commande_lavage.nombre_pieces 
     FROM commande_lavage 
     INNER JOIN planifier_lavage ON planifier_lavage.id_lavage = commande_lavage.id_lavage
     WHERE planifier_lavage.id_lavage=:id");
     $q->execute(['id' => $id]);
     return $q->fetchAll(PDO::FETCH_OBJ);
   }

   public function produit_lav(int $id)
   {
     $q = $this->getconnexion()->prepare("SELECT produits.nom_produit, produit_lavage.utilisation_produit 
     FROM produit_lavage 
     INNER JOIN planifier_lavage ON planifier_lavage.id_lavage = produit_lavage.id_lavage
     INNER JOIN produits ON produits.id_produit = produit_lavage.id_produit
     WHERE planifier_lavage.id_lavage=:id");
     $q->execute(['id' => $id]);
     return $q->fetchAll(PDO::FETCH_OBJ);
   }


   public function countBilllist(): int
   {
     return (int)$this->getconnexion()->query("SELECT COUNT(id_listing) AS count FROM designation_listing")->fetch()[0];
   }

   public function countBillemballage(): int
   {
     return (int)$this->getconnexion()->query("SELECT COUNT(id_emballage) AS count FROM emballage")->fetch()[0];
   }

   public function countBilllav(): int
   {
     return (int)$this->getconnexion()->query("SELECT COUNT(id_lavage) AS count FROM planifier_lavage")->fetch()[0];
   }

   public function countBillsec(): int
   {
     return (int)$this->getconnexion()->query("SELECT COUNT(id_sechage) AS count FROM sechage")->fetch()[0];
   }

   public function countbillrep(): int
   {
     return (int)$this->getconnexion()->query("SELECT COUNT(id_repassage) AS count FROM repassage")->fetch()[0];
   }

   
   public function getlistings(int $id)
   {
    $q = $this->getconnexion()->prepare("SELECT * FROM designation_listing WHERE id_listing=:id_listing");
    $q->execute(['id_listing' => $id]);
    return $q->fetch(PDO::FETCH_OBJ);
   }

   public function update_list(int $id_list ,int $id_comm, string $list ,float $quantity, float $unit, string $instuct)
   {
    $amount = $quantity * $unit;
       $q = $this->getconnexion()->prepare("UPDATE designation_listing SET id_commande = :id_comm, 
       Listing = :list, quantity = :quantity, prix_unitaire = :unit,  
       montant = :amount, Instructions_speciales = :instuct WHERE id_listing = :id");

       return $q->Execute([
           'id_comm' => $id_comm,
           'list' => $list,
           'quantity' => $quantity,
           'unit' => $unit,
           'amount' => $amount,
           'instuct' => $instuct,
           'id' => $id_list
       ]);
   }

   public function stop_lavage(int $id_lavage ,$temps_fin)
   {
       $q = $this->getconnexion()->prepare("UPDATE planifier_lavage SET temps_fin = :temps , statut = \"Terminée\"  
       WHERE id_lavage = :id");

       return $q->Execute([
           'temps' => $temps_fin,
           'id' => $id_lavage
       ]);
   }

   public function stop_livraison(int $id_livraison, $temps, $heure)
   {
        $stmt = $this->getconnexion()->prepare("UPDATE commandes SET statut = \"Livré\"  
        WHERE id_commande = 
        (SELECT id_commande FROM livraisons WHERE id_livraison = :id_liv)
        ");
        $stmt->Execute(['id_liv' => $id_livraison]);

       $q = $this->getconnexion()->prepare("UPDATE livraisons SET date_livraison = :temps, heure_livraison = :heure
        ,statut_livraison = \"Livré\"  
       WHERE id_livraison = :id");

       return $q->Execute([
           'temps' => $temps,
           'heure' => $heure,
           'id' => $id_livraison
       ]);
   }


   public function stop_repassage(int $id_rep ,$temps_fin)
   {
       $q = $this->getconnexion()->prepare("UPDATE repassage SET temps_fin = :temps , statut = \"Terminée\"  
       WHERE id_repassage = :id");

       return $q->Execute([
           'temps' => $temps_fin,
           'id' => $id_rep
       ]);
   }


   public function stop_emballage(int $id_emb ,$temps_fin)
   {
       $q = $this->getconnexion()->prepare("UPDATE emballage SET date_fin = :temps , statut = \"Terminée\"  
       WHERE id_emballage = :id");

       return $q->Execute([
           'temps' => $temps_fin,
           'id' => $id_emb
       ]);
   }



   public function stop_sechage(int $id_sechage ,$temps_fin)
   {
       $q = $this->getconnexion()->prepare("UPDATE sechage SET temps_fin = :temps , statut = \"Terminée\"  
       WHERE id_sechage = :id");

       return $q->Execute([
           'temps' => $temps_fin,
           'id' => $id_sechage
       ]);
   }


   public function enreg_produit(string $nom, int $qte, int $price, ?string $desc)
   {
       $q = $this->getconnexion()->prepare("INSERT INTO produits (nom_produit, quantity_stock,
       prix_unitaire, Description_produit) VALUES (:nom, :qte, :price, :descrip)");
       return $q->Execute([
           'nom' => $nom,
           'qte' => $qte,
           'price' => $price,
           'descrip' => $desc
       ]);
   }

   public function read_produit()
   {
       return $this->getconnexion()->query('SELECT * FROM produits ORDER BY id_produit')
       ->fetchAll(PDO::FETCH_OBJ);
   }

   public function countbillprod(): int
   {
       return (int)$this->getconnexion()->query("SELECT COUNT(id_produit) AS count 
       FROM produits")->fetch()[0];
   }

   public function getproduit(int $id)
   {
    $q = $this->getconnexion()->prepare("SELECT * FROM produits WHERE id_produit=:prod");
    $q->execute(['prod' => $id]);
    return $q->fetch(PDO::FETCH_OBJ);
   }


   public function update_produit(int $id,string $nom, int $quantity, int $price, ?string $desc)
   {
       $q = $this->getconnexion()->prepare("UPDATE produits SET nom_produit = :nom,
       quantity_stock = :qte, prix_unitaire =:price, Description_produit =:descrip  
       WHERE id_produit = :id");
       return $q->Execute([
           'nom' => $nom,
           'qte' => $quantity,
           'price' => $price,
           'descrip' => $desc,
           'id' => $id
       ]);
   }

   public function delete_produit(int $id): bool
   {
       $q = $this->getconnexion()->prepare("DELETE FROM produits WHERE id_produit = :id");
       return $q->execute(['id' => $id]);
   }



   public function search_employee($name)
   {
       $stmt = $this->getconnexion()->prepare("SELECT id_employé, nomComplet_employé 
       FROM employé WHERE nomComplet_employé LIKE :nome");
       $stmt->execute(['nome' => "%$name%"]);
       return  $stmt->fetchAll(PDO::FETCH_ASSOC);
   }


   public function search_categ($name)
   {
       $stmt = $this->getconnexion()->prepare("SELECT id_categorie, categorie
       FROM categorie WHERE categorie LIKE :nome");
       $stmt->execute(['nome' => "%$name%"]);
       return  $stmt->fetchAll(PDO::FETCH_ASSOC);
   }



   public function enreg_dep(int $idempl, string $desc, float $amount)
   {
       $q = $this->getconnexion()->prepare("INSERT INTO depenses (id_employé, descriptionss, 
       montant_depense) VALUES (:idempl, :desca, :amount)");
       return $q->Execute([
           'idempl' => $idempl,
           'desca' => $desc,
           'amount' => $amount
       ]);
   }


   public function read_dep()
   {
       return $this->getconnexion()->query('SELECT id_depense, nomComplet_employé, 
       descriptionss, montant_depense, date_depense, heure_depense
       FROM depenses
       INNER JOIN employé ON depenses.id_employé = employé.id_employé')->fetchAll(PDO::FETCH_OBJ);
   }

   public function countBilldep(): int
   {
       return (int)$this->getconnexion()->query("SELECT COUNT(id_depense) AS count FROM depenses")->fetch()[0];
   }


   public function getsingledep(int $id)
   {
       $q = $this->getconnexion()->prepare('SELECT depenses.id_depense, depenses.id_employé,
        employé.nomComplet_employé,depenses.descriptionss, depenses.montant_depense, depenses.date_depense, 
       depenses.heure_depense
       FROM depenses
       INNER JOIN employé ON depenses.id_employé = employé.id_employé
       WHERE depenses.id_depense = :id
       ');
       $q->execute(['id' => $id]);
       return $q->fetch(PDO::FETCH_OBJ);
    }


    public function nombre_pieces_repasser(int $id)
    {
        $q = $this->getconnexion()->prepare('SELECT nombre_pieces FROM commandes
        WHERE id_commande = (SELECT id_commande FROM repassage WHERE id_repassage = :id)
        ');
        $q->execute(['id' => $id]);
        return $q->fetch(PDO::FETCH_ASSOC);
     }


    public function update_dep(int $id,int $id_empl, string $descp, float $amount,
    string $date, string $temps)
   {
       $q = $this->getconnexion()->prepare("UPDATE depenses SET id_employé = :id_empl,  descriptionss = :descp, 
       montant_depense = :amount ,date_depense = :datap, heure_depense = :heure WHERE id_depense = :id");
       return $q->Execute([
           'id_empl' => $id_empl,
           'descp' => $descp,
           'amount' => $amount,
           'datap' => $date,
           'heure' => $temps,
           'id' => $id
       ]);
   }


   public function delele_dep(int $id): bool
   {
       $q = $this->getconnexion()->prepare("DELETE FROM depenses WHERE id_depense = :id");
       return $q->execute(['id' => $id]);
   }


   public function SELECT_EMPL(string $reference)
   {
       $q = $this->getconnexion()->prepare("SELECT * FROM employé WHERE nomComplet_employé = :ref");
       $q -> bindParam(':ref',$reference);
       $q -> execute();

       return $q->fetch(PDO::FETCH_ASSOC);
   }


   public function SELECT_RESP(string $reference)
   {
       $q = $this->getconnexion()->prepare("SELECT * FROM responsable WHERE CONCAT(nom_respo,' ',surname_respo) = :ref
       OR nom_respo = :ref");
       $q -> bindParam(':ref',$reference);
       $q -> execute();

       return $q->fetch(PDO::FETCH_ASSOC);
   }

   public function SELECT_CL(string $reference)
   {
       $q = $this->getconnexion()->prepare("SELECT * FROM clients WHERE CONCAT(nom_client,' ',surname_client) = :ref
       OR nom_client = :ref");
       $q -> bindParam(':ref',$reference);
       $q -> execute();

       return $q->fetch(PDO::FETCH_ASSOC);
   }


   public function save_liv(int $id_Comm, string $dateLiv, string $heureLiv, string $statut)
   {
       $q = $this->getconnexion()->prepare("INSERT INTO livraisons (id_commande, date_livraison, 
       heure_livraison, statut_livraison) 
       VALUES (:id_Comm, :dateLiv, :heureLiv, :statut)");
       return $q->Execute([
           'id_Comm' => $id_Comm,
           'dateLiv' => $dateLiv,
           'heureLiv' => $heureLiv,
           'statut' => $statut,
       ]);
   }


   public function read_liv()
   {
       return $this->getconnexion()->query('SELECT * FROM livraisons ORDER BY id_livraison')->fetchAll(PDO::FETCH_OBJ);
   }

   public function countBilliv(): int
   {
       return (int)$this->getconnexion()->query("SELECT COUNT(id_livraison) AS count FROM livraisons")->fetch()[0];
   }

   public function getliv(int $id)
   {
    $q = $this->getconnexion()->prepare("SELECT * FROM livraisons WHERE id_livraison=:id_livraison");
    $q->execute(['id_livraison' => $id]);
    return $q->fetch(PDO::FETCH_OBJ);
   }


   public function update_liv(int $id_liv ,int $id_comm, string $dateliv , string $heureliv, string $statut)
   {
       $q = $this->getconnexion()->prepare("UPDATE livraisons SET id_commande = :id_comm, 
       date_livraison = :dateliv, heure_livraison = :heureliv, statut_livraison = :statut
       WHERE id_livraison = :id");

       return $q->Execute([
           'id_comm' => $id_comm,
           'dateliv' => $dateliv,
           'heureliv' => $heureliv,
           'statut' => $statut,
           'id' => $id_liv
       ]);
   }

   public function update_choix(int $id_lavage ,string $choix)
   {
       $q = $this->getconnexion()->prepare("UPDATE planifier_lavage SET proprete = :choix
       WHERE id_lavage = :id");

       return $q->Execute([
           'choix' => $choix,
           'id' => $id_lavage
       ]);
   }

   public function update_choixsec(int $id_sechage, string $choix)
   {
       if ($choix == "parfait") {
           $q = $this->getconnexion()->prepare("SELECT id_commande FROM commande_lavage WHERE id_lavage = 
               (SELECT id_lavage FROM sechage WHERE id_sechage = :id_sec)");
           $q->execute(['id_sec' => $id_sechage]);
           $result = $q->fetchAll(PDO::FETCH_ASSOC);
   
           if ($result) {
               foreach ($result as $comm) {
                   $q = $this->getconnexion()->prepare("UPDATE commandes SET statut = 'repassage' WHERE id_commande = :id");
                   $q->execute(['id' => $comm['id_commande']]);
               }
           }
       }
   
       $stmt = $this->getconnexion()->prepare("UPDATE sechage SET etat_sechement = :choix WHERE id_sechage = :id_sec");
   
       return $stmt->execute([
           'choix' => $choix,
           'id_sec' => $id_sechage
       ]);
   }
   



   public function delete_liv(int $id): bool
    {
        $q = $this->getconnexion()->prepare("DELETE FROM livraisons WHERE id_livraison = :id");
        return $q->execute(['id' => $id]);
    }


    public function montant_total(int $id)
    {
        $stmt = $this->getconnexion()->prepare("SELECT SUM(montant) AS somme FROM designation_listing
        WHERE designation_listing.id_commande = :id");
        $stmt -> bindParam(':id', $id);
        $stmt->execute();
    
        return $stmt->fetch()[0];

    }

    public function part_respo(int $id)
    {
        $stmt = $this->getconnexion()->prepare("SELECT pourcentage  FROM responsable
        INNER JOIN commandes ON responsable.id_responsable = commandes.id_responsable
        WHERE commandes.id_commande = :id");
        $stmt -> bindParam(':id', $id);
        $stmt->execute();
    
        return $stmt->fetch()[0];

    }

    public function save_facture(int $id_Comm, float $montant, ?float $remise, float $net_payer, 
    float $montant_caisse, string $statut, string $echeance, int $uniq)
    {
     $reste = $net_payer;
        $q = $this->getconnexion()->prepare("INSERT INTO factures (id_commande, montant_total, 
        remise, net_payer, montant_caisse, statut, date_echeance, reste_payer, unique_id) 
        VALUES (:id_Comm, :montant, :remise, :net_payer, :amount, :statut, :echeance, :reste,:uniq)");
        return $q->Execute([
            'id_Comm' => $id_Comm,
            'montant' => $montant,
            'remise' => $remise,
            'net_payer' => $net_payer,
            'amount' => $montant_caisse,
            'statut' => $statut,
            'echeance' => $echeance,
            'reste' => $reste,
            'uniq'=>$uniq
        ]);
    }


    public function save_repassage(int $id_comm, $temps_debut, ?string $comments, int $uniq)
    {
        $etat = "En cours";
        $q = $this->getconnexion()->prepare("INSERT INTO repassage (id_commande, temps_debut, 
        commentaires, statut, unique_id) 
        VALUES (:id_Comm, :temps, :comments, :etat,:uniq)");
        return $q->Execute([
            'id_Comm' => $id_comm,
            'temps' => $temps_debut,
            'comments' => $comments,
            'etat' => $etat,
            'uniq'=>$uniq
        ]);
    }


    public function save_emballage(int $id_repassage, $temps_debut, ?string $comments, int $uniq)
    {
        $etat = "En cours";
        $q = $this->getconnexion()->prepare("INSERT INTO emballage (id_repassage, date_debut, 
        commentaires, statut, unique_id) 
        VALUES (:id_rep, :temps, :comments, :etat,:uniq)");
        return $q->Execute([
            'id_rep' => $id_repassage,
            'temps' => $temps_debut,
            'comments' => $comments,
            'etat' => $etat,
            'uniq'=>$uniq
        ]);
    }



    public function read_fact()
    {
        return $this->getconnexion()->query('SELECT * FROM factures ORDER BY id_facture')->fetchAll(PDO::FETCH_OBJ);
    }
 
 
    public function countbillfact(): int
    {
        return (int)$this->getconnexion()->query("SELECT COUNT(id_facture) AS count FROM factures")->fetch()[0];
    }
 
    
    public function getfactures(int $id)
    {
     $q = $this->getconnexion()->prepare("SELECT * FROM factures WHERE id_facture=:id_commande");
     $q->execute(['id_commande' => $id]);
     return $q->fetch(PDO::FETCH_OBJ);
    }


    public function avoirfactures(int $id)
    {
     $q = $this->getconnexion()->prepare("SELECT * FROM factures WHERE id_commande=:id_comm");
     $q->execute(['id_comm' => $id]);
     return $q->fetch(PDO::FETCH_OBJ);
    }


    public function update_fact(int $id_fact ,int $id_comm, float $montant , ?float $remise, float $net_payer, float $montantCaisse, string $date, String $heure, string $echeance)
    {
        $reste = $net_payer;
        $q = $this->getconnexion()->prepare("UPDATE factures SET id_commande = :id_comm, montant_total = :montant,
        remise = :remise, net_payer = :net_payer, montant_caisse = :montantCaisse, date_facture = :datef, heure_facture = :heureF, 
        date_echeance = :echeance, reste_payer = :reste
        WHERE id_facture = :id");
 
        return $q->Execute([
            'id_comm' => $id_comm,
            'montant' => $montant,
            'remise' => $remise,
            'net_payer' => $net_payer,
            'montantCaisse' => $montantCaisse,
            'datef' => $date,
            'reste'=>$reste,
            'heureF' => $heure,
            'echeance' => $echeance,
            'id' => $id_fact
        ]);
    }


    public function delete_fact(int $id): bool
    {
        $q = $this->getconnexion()->prepare("DELETE FROM factures WHERE id_facture = :id");
        return $q->execute(['id' => $id]);
    }


    public function save_recu(int $id_fact, int $id_empl, float $montant, string $mode)
    {
        $q = $this->getconnexion()->prepare("INSERT INTO recu_paiement (id_facture, id_employé, 
        montant_paye, mode_paiement) 
        VALUES (:idfact, :idempl, :montant, :mode)");
        return $q->Execute([
            'idfact' => $id_fact,
            'idempl' => $id_empl,
            'montant' => $montant,
            'mode' => $mode
        ]);
    }


    public function read_recu()
    {
        return $this->getconnexion()->query('SELECT id_recu, nomComplet_employé, id_facture, 
        date_paiement, heure_paiement, montant_paye, mode_paiement
        FROM recu_paiement
        INNER JOIN employé ON recu_paiement.id_employé = employé.id_employé
        ')->fetchAll(PDO::FETCH_OBJ);
    }
 
    public function countbillrecu(): int
    {
        return (int)$this->getconnexion()->query("SELECT COUNT(id_recu) AS count FROM recu_paiement")->fetch()[0];
    }
 
 
    public function getrecu(int $id)
    {
        $q = $this->getconnexion()->prepare('SELECT recu_paiement.id_recu, recu_paiement.id_facture,
        employé.nomComplet_employé, recu_paiement.date_paiement, employé.id_employé,
        recu_paiement.heure_paiement, recu_paiement.montant_paye, recu_paiement.mode_paiement
        FROM recu_paiement
        INNER JOIN employé ON recu_paiement.id_employé = employé.id_employé
        WHERE recu_paiement.id_recu = :id
        ');
        $q->execute(['id' => $id]);
        return $q->fetch(PDO::FETCH_OBJ);
    }


    public function update_recu(int $id_recu ,int $id_fact, int $id_empl , string $datepay, string $heurpay,
    float $montant, string $mode)
    {
        $q = $this->getconnexion()->prepare("UPDATE recu_paiement SET id_facture = :id_fact, id_employé = :id_empl,
        date_paiement = :datepay, heure_paiement = :heurpay, montant_paye = :montant, mode_paiement = :mode
        WHERE id_recu = :id");
 
        return $q->Execute([
            'id_fact' => $id_fact,
            'id_empl' => $id_empl,
            'datepay' => $datepay,
            'heurpay' => $heurpay,
            'montant' => $montant,
            'mode' => $mode,
            'id' => $id_recu
        ]);
    }


    public function delete_pay(int $id): bool
    {
        $q = $this->getconnexion()->prepare("DELETE FROM recu_paiement WHERE id_recu = :id");
        return $q->execute(['id' => $id]);
    }

    public function verify_input($var)
    {
    $var = trim($var);
    $var = stripslashes($var); 
    $var = htmlspecialchars($var); 
    $var= htmlspecialchars_decode($var, ENT_QUOTES);

    return $var;
    }

    public function nombre_clients(): int
    {
        return (int)$this->getconnexion()->query("SELECT COUNT(id_client) AS count FROM clients")->fetch()[0];
    } 


    public function nombre_clientsparemployee(): int
    {
        if (!isset($_SESSION["unique_id"])) {
            return 0;
        }
        $unique_id = $_SESSION["unique_id"];
        $query = "SELECT COUNT(id_client) AS count FROM clients WHERE unique_id = :unique_id";
        $stmt = $this->getconnexion()->prepare($query);
        $stmt->bindParam(":unique_id", $unique_id, PDO::PARAM_STR);
        $stmt->execute();
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($result && isset($result['count'])) {
            return (int)$result['count'];
        } else {
            return 0;
        }
    }
    

    public function nombre_commandes(): int
    {
        return (int)$this->getconnexion()->query("SELECT COUNT(id_commande) AS count FROM commandes")->fetch()[0];
    } 

    public function nombre_commande_employee(): int
    {
        if (!isset($_SESSION["unique_id"])) {
            return 0;
        }
        $unique_id = $_SESSION["unique_id"];
        $query = "SELECT COUNT(id_commande) AS count FROM commandes WHERE unique_id = :unique_id";
        $stmt = $this->getconnexion()->prepare($query);
        $stmt->bindParam(":unique_id", $unique_id, PDO::PARAM_STR);
        $stmt->execute();
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($result && isset($result['count'])) {
            return (int)$result['count'];
        } else {
            return 0;
        }
    }

    public function nombre_resposable(): int
    {
        return (int)$this->getconnexion()->query("SELECT COUNT(id_responsable) AS count FROM responsable")->fetch()[0];
    } 


    public function montant_total_work(): float
    {
        return (int)$this->getconnexion()->query("SELECT SUM(montant_total) AS count FROM factures")->fetch()[0];
    } 


    public function possedefact(int $id_fact)
    {
       $stmt =  $this->getconnexion()->prepare("SELECT * FROM factures WHERE id_commande = :comm");
       $stmt -> bindParam(':comm', $id_fact);
       $stmt->execute();

       return $stmt->fetch(PDO::FETCH_ASSOC);

    } 


    public function update_facture(int $id_comm, float $montant)
    {
        $stmt = $this->getconnexion()->prepare("SELECT montant_total, remise, date_facture,
        date_echeance FROM factures WHERE id_commande =:id_comm");
        $stmt -> bindParam(':id_comm', $id_comm);
        $stmt->execute();

    
        $amount =  $stmt->fetchAll(PDO::FETCH_OBJ);
        $dateday = date('Y-m-d');

       foreach($amount as $item)
       {
        $pourc = $this->part_respo($id_comm);
        $new_amount = $item->montant_total + $montant;
        $net_paye = ($new_amount/100)*(100-$item->remise);
        $caisseMount = ($net_paye/100)*(100-$pourc);

        $diffJours = floor((strtotime($item->date_echeance) - strtotime($item->date_facture)) / (60 * 60 * 24));

        $newecheance = date('Y-m-d', strtotime($dateday . ' +' . $diffJours . ' days'));
       }



        $q = $this->getconnexion()->prepare("UPDATE factures SET montant_total = :montant,
         net_payer = :net_payer, montant_caisse = :montantCaisse, date_facture = :datef, heure_facture = :heureF, 
        date_echeance = :echeance, reste_payer = :reste
        WHERE id_commande = :id");
 
        return $q->Execute([
            'montant' => $new_amount,
            'net_payer' => $net_paye,
            'montantCaisse' => $caisseMount,
            'datef' => $dateday,
            'heureF' => date('H:i:s'),
            'echeance' => $newecheance,
            'reste' => $caisseMount,
            'id' => $id_comm
        ]);
    }


    public function montant_work_employee(): float
    {
        if (!isset($_SESSION["unique_id"])) {
            return 0.0;
        }
    
        $unique_id = $_SESSION["unique_id"];
    
        $query = "SELECT id_responsable FROM responsable 
                  WHERE CONCAT(surname_respo,' ', nom_respo) LIKE 
                  CONCAT('%', (SELECT nomComplet_employé FROM employé WHERE unique_id = :unique_id), '%')";
    
        $stmt = $this->getconnexion()->prepare($query);
        $stmt->bindParam(":unique_id", $unique_id, PDO::PARAM_STR);
        $stmt->execute();
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($result && isset($result['id_responsable'])) {
    
            $req = "SELECT SUM(factures.montant_total) AS total_montant
                    FROM factures 
                    INNER JOIN commandes ON factures.id_commande = commandes.id_commande
                    WHERE commandes.id_responsable = :resp";
    
            $q = $this->getconnexion()->prepare($req);
            $q->bindParam(":resp", $result['id_responsable'], PDO::PARAM_INT);
            $q->execute();
    
            $resultat_total = $q->fetch(PDO::FETCH_ASSOC);
    
            // Vérifier si le résultat n'est pas vide
            if ($resultat_total && isset($resultat_total['total_montant'])) {
                return (float)$resultat_total['total_montant'];
            } else {
                return 0.0; // Retourne 0.0 en cas de résultat vide
            }
        } else {
            return 0.0; // Retourne 0.0 en cas de résultat vide
        }
    }
    


    public function verifsupplist(int $id)
    {

        $q = $this->getconnexion()->prepare('SELECT id_commande FROM designation_listing WHERE id_listing = :list');
        $q -> bindParam(':list', $id);
        $q->execute();
    
        $comm =  $q->fetch()[0];

        $stmt = $this->getconnexion()->query('SELECT * FROM factures WHERE id_commande ='.$comm);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function verifpayement(int $id_facture)
    {
        $q = $this->getconnexion()->prepare('SELECT id_commande FROM factures WHERE id_facture = :list');
        $q->bindParam(':list', $id_facture);
        $q->execute();
    
        $comm =  $q->fetch()[0];
    
        $stmt = $this->getconnexion()->query('SELECT * FROM recu_paiement WHERE id_facture =' . $comm);
    
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getuniqueEmpl(int $id_recu)
    {
        $q = $this->getconnexion()->prepare('SELECT id_employé FROM recu_paiement WHERE id_recu = :list');
        $q->bindParam(':list', $id_recu);
        $q->execute();
    
        $comm =  $q->fetch()[0];
    
        $stmt = $this->getconnexion()->query('SELECT unique_id FROM employé WHERE id_employé =' . $comm);
    
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getuniqueEmpldep(int $id_depense)
    {
        $q = $this->getconnexion()->prepare('SELECT id_employé FROM depenses WHERE id_depense = :dep');
        $q->bindParam(':dep', $id_depense);
        $q->execute();
    
        $comm =  $q->fetch()[0];
    
        $stmt = $this->getconnexion()->query('SELECT unique_id FROM employé WHERE id_employé =' . $comm);
    
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    

    public function misejourfact(int $id_comm, float $montantup, float $montantlast)
    {
        $stmt = $this->getconnexion()->prepare("SELECT montant_total, remise, date_facture,
        date_echeance FROM factures WHERE id_commande =:id_comm");
        $stmt -> bindParam(':id_comm', $id_comm);
        $stmt->execute();

    
        $amount =  $stmt->fetchAll(PDO::FETCH_OBJ);
        $dateday = date('Y-m-d');

       foreach($amount as $item)
       {
        $pourc = $this->part_respo($id_comm);
        $new_amount = $item->montant_total + $montantup - $montantlast;
        $net_paye = ($new_amount/100)*(100-$item->remise);
        $caisseMount = ($net_paye/100)*(100-$pourc);

        $diffJours = floor((strtotime($item->date_echeance) - strtotime($item->date_facture)) / (60 * 60 * 24));

        $newecheance = date('Y-m-d', strtotime($dateday . ' +' . $diffJours . ' days'));
       }



        $q = $this->getconnexion()->prepare("UPDATE factures SET montant_total = :montant,
        net_payer = :net_payer, montant_caisse = :montantCaisse, date_facture = :datef, heure_facture = :heureF, 
        date_echeance = :echeance, reste_payer = :reste
        WHERE id_commande = :id");
        return $q->Execute([
            'montant' => $new_amount,
            'net_payer' => $net_paye,
            'montantCaisse' => $caisseMount,
            'datef' => $dateday,
            'heureF' => date('H:i:s'),
            'echeance' => $newecheance,
            'reste' => $caisseMount,
            'id' => $id_comm
        ]);
    }


    public function paye_fact(int $idcomm, float $montant)
    {
        $q = $this->getconnexion()->prepare("SELECT reste_payer FROM factures WHERE id_commande=:id_commande");
        $q->execute(['id_commande' => $idcomm]);
        $result = $q->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
        $resteAPayer = $result['reste_payer'];
        if ($montant < $resteAPayer) {
            $reste = $resteAPayer - $montant;
            $q = $this->getconnexion()->prepare("UPDATE factures SET reste_payer = :reste, statut = 'Avancé'
                WHERE id_commande = :id");
            return $q->Execute([
                'reste' => $reste,
                'id' => $idcomm
            ]);
        }

        if ($montant >= $resteAPayer) {
            $reste = $montant - $resteAPayer;
            $q = $this->getconnexion()->prepare("UPDATE factures SET reste_payer = :reste, statut = 'Payé'
                WHERE id_commande = :id");
            return $q->Execute([
                'reste' => $reste,
                'id' => $idcomm
            ]);
        }
       } 
    }


    public function miseJour_fact(int $id_comm, float $newAmount, float $lastAmount)
    {
        $q = $this->getconnexion()->prepare("SELECT reste_payer FROM factures WHERE id_commande=:id_commande");
        $q->execute(['id_commande' => $id_comm]);
        $result = $q->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
        $resteAPayer = $result['reste_payer'];
        if ($newAmount < ($resteAPayer + $lastAmount)) {
            $reste = $resteAPayer + $lastAmount - $newAmount;
            $q = $this->getconnexion()->prepare("UPDATE factures SET reste_payer = :reste, statut = 'Avancé'
                WHERE id_commande = :id");
            return $q->Execute([
                'reste' => $reste,
                'id' => $id_comm
            ]);
        }

        if ($newAmount >= ($resteAPayer + $lastAmount)) {
            $reste = $newAmount - $resteAPayer + $lastAmount;
            $q = $this->getconnexion()->prepare("UPDATE factures SET reste_payer = :reste, statut = 'Payé'
                WHERE id_commande = :id");
            return $q->Execute([
                'reste' => $reste,
                'id' => $id_comm
            ]);
        }

       } 
    }



    public function infoclient(int $id_fact)
    {
        $q = $this->getconnexion()->prepare('SELECT id_commande FROM factures WHERE id_facture = :fact');
        $q -> bindParam(':fact', $id_fact);
        $q->execute();
    
        $comm =  $q->fetch()[0];

        $stmt =  $this->getconnexion()->prepare("SELECT * FROM clients
        INNER JOIN commandes  ON clients.id_client = commandes.id_client
        INNER JOIN factures ON commandes.id_commande = factures.id_commande
        WHERE factures.id_commande = $comm");
        $stmt->execute();
 
        return $stmt->fetch(PDO::FETCH_OBJ);
 
    }

    public function retrieveinfos(int $paramd)
    {
        $q = $this->getconnexion()->prepare('SELECT id_commande FROM factures WHERE id_facture = :fact');
        $q -> bindParam(':fact', $paramd);
        $q->execute();
    
        $comm =  $q->fetch()[0];

        $stmt =  $this->getconnexion()->prepare("SELECT * FROM designation_listing
        WHERE id_commande = $comm");
        $stmt->execute();
 
        return $stmt->fetchAll(PDO::FETCH_OBJ);
 
    }

    public function countlistings(int $paramd)
    {
        $q = $this->getconnexion()->prepare('SELECT id_commande FROM factures WHERE id_facture = :fact');
        $q -> bindParam(':fact', $paramd);
        $q->execute();
    
        $comm =  $q->fetch()[0];

        $stmt =  $this->getconnexion()->prepare("SELECT COUNT(id_listing) FROM designation_listing
        WHERE id_commande = $comm");
        $stmt->execute();
 
        return $stmt->fetch()[0];
 
    }

    public function numberpieces(int $number)
    {

        $q = $this->getconnexion()->prepare('SELECT id_commande FROM factures WHERE id_facture = :fact');
        $q -> bindParam(':fact', $number);
        $q->execute();
    
        $comm =  $q->fetch()[0];

        $num = $this->getconnexion()->prepare("SELECT nombre_pieces FROM commandes 
        INNER JOIN factures ON commandes.id_commande = factures.id_commande
        WHERE factures.id_commande = $comm ");
        $num->execute();
 
        return $num->fetch()[0];
    }

    public function getremise(int $number)
    {

        $q = $this->getconnexion()->prepare('SELECT remise FROM factures WHERE id_facture = :fact');
        $q -> bindParam(':fact', $number);
        $q->execute();
    
        return  $q->fetch()[0];
    }

    public function nom_prenom_client(int $id_client)
    {
       $query = $this->getconnexion()->prepare("SELECT nom_client, surname_client FROM clients WHERE id_client = :identifiant");
       $query -> bindParam(':identifiant', $id_client);
       $query->execute();

       return $query->fetch(PDO::FETCH_ASSOC);
   }


   public function read_dashboard()
   {
       $requete = "SELECT commandes.id_commande, clients.nom_client, clients.surname_client,factures.date_echeance,
       commandes.nombre_pieces, commandes.statut AS statut_commande, factures.statut  AS statut_facture
       FROM commandes
       INNER JOIN clients ON commandes.id_client = clients.id_client
       INNER JOIN factures ON commandes.id_commande = factures.id_commande
       ORDER BY commandes.id_commande DESC 
       LIMIT 8";

       return $this->getconnexion()->query($requete)->fetchAll(PDO::FETCH_OBJ);
   }



   function enregistrerJournal(int $user_id, string $action) 
   {
        $timestamp = date("Y-m-d H:i:s");
        $query = "INSERT INTO journal (unique_id, action, timestamp) VALUES (:user_id, :action, :timestamp)";
        $stmt = $this->getconnexion()->prepare($query);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindParam(":action", $action, PDO::PARAM_STR);
        $stmt->bindParam(":timestamp", $timestamp, PDO::PARAM_STR);
        $stmt->execute();
   }

   function getAction()
   {
    $stmt = $this->getconnexion()->prepare("SELECT * FROM journal  ORDER BY timestamp DESC LIMIT 3");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
   }


   public function select_image(int $uniq)
   {
      $query = $this->getconnexion()->prepare("SELECT image FROM employé WHERE unique_id = :uniq");
      $query -> bindParam(':uniq', $uniq);
      $query->execute();

      return $query->fetch(PDO::FETCH_OBJ);
   }


}
 
