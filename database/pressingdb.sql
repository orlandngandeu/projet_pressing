-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mer. 02 août 2023 à 00:17
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `pressingdb`
--

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id_client` int(11) NOT NULL,
  `nom_client` varchar(200) NOT NULL,
  `surname_client` varchar(200) NOT NULL,
  `sexe` varchar(100) NOT NULL,
  `Adresse` varchar(200) NOT NULL,
  `Telephone` varchar(100) NOT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `unique_id` int(200) NOT NULL,
  `date_enreg` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id_commande` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `id_responsable` int(11) NOT NULL,
  `nombre_pieces` int(11) NOT NULL,
  `Entry_date` date NOT NULL DEFAULT current_timestamp(),
  `Entry_hour` time NOT NULL DEFAULT current_timestamp(),
  `statut` varchar(100) NOT NULL,
  `date_livraison` date NOT NULL,
  `commentaire_employee` tinytext NOT NULL,
  `unique_id` int(200) NOT NULL,
  `pieces_lavee` int(200) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commande_lavage`
--

CREATE TABLE `commande_lavage` (
  `identifiant` int(11) NOT NULL,
  `id_lavage` int(200) NOT NULL,
  `id_commande` int(200) NOT NULL,
  `nombre_pieces` int(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `depenses`
--

CREATE TABLE `depenses` (
  `id_depense` int(11) NOT NULL,
  `id_employé` int(11) NOT NULL,
  `descriptionss` text NOT NULL,
  `montant_depense` float NOT NULL,
  `date_depense` date NOT NULL DEFAULT current_timestamp(),
  `heure_depense` time NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `designation_listing`
--

CREATE TABLE `designation_listing` (
  `id_listing` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `Listing` text NOT NULL,
  `quantity` float NOT NULL,
  `prix_unitaire` float NOT NULL,
  `montant` float NOT NULL,
  `Instructions_speciales` varchar(256) NOT NULL,
  `unique_id` int(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `emballage`
--

CREATE TABLE `emballage` (
  `id_emballage` int(11) NOT NULL,
  `id_repassage` int(11) NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime DEFAULT NULL,
  `commentaires` tinytext DEFAULT NULL,
  `statut` varchar(200) NOT NULL,
  `unique_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `employé`
--

CREATE TABLE `employé` (
  `id_employé` int(11) NOT NULL,
  `unique_id` int(200) NOT NULL,
  `nomComplet_employé` varchar(255) NOT NULL,
  `userName_employé` varchar(200) NOT NULL,
  `telephone_employé` varchar(100) NOT NULL,
  `Age` int(11) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `mot_passe_hash` varchar(255) NOT NULL,
  `image` varchar(400) NOT NULL,
  `status` varchar(200) NOT NULL,
  `position` varchar(100) NOT NULL DEFAULT 'user',
  `code_verify` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `factures`
--

CREATE TABLE `factures` (
  `id_facture` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `date_facture` date NOT NULL DEFAULT current_timestamp(),
  `montant_total` double NOT NULL,
  `remise` float DEFAULT NULL,
  `net_payer` float NOT NULL,
  `montant_caisse` float NOT NULL,
  `heure_facture` time NOT NULL DEFAULT current_timestamp(),
  `statut` varchar(200) NOT NULL,
  `date_echeance` date NOT NULL,
  `reste_payer` float NOT NULL,
  `unique_id` int(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `journal`
--

CREATE TABLE `journal` (
  `id` int(11) NOT NULL,
  `unique_id` int(11) NOT NULL,
  `action` tinytext NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `livraisons`
--

CREATE TABLE `livraisons` (
  `id_livraison` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `date_livraison` date NOT NULL,
  `heure_livraison` time NOT NULL,
  `statut_livraison` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `msg_id` int(11) NOT NULL,
  `outgoing_msg_id` int(255) NOT NULL,
  `incoming_msg_id` int(255) NOT NULL,
  `mssg` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `planifier_lavage`
--

CREATE TABLE `planifier_lavage` (
  `id_lavage` int(11) NOT NULL,
  `temps_debut` datetime NOT NULL,
  `temps_fin` datetime DEFAULT NULL,
  `commentaire_lavage` varchar(200) DEFAULT NULL,
  `statut` varchar(200) NOT NULL,
  `type_machine` varchar(200) NOT NULL,
  `masse` float NOT NULL,
  `proprete` varchar(200) DEFAULT NULL,
  `unique_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id_produit` int(11) NOT NULL,
  `nom_produit` varchar(200) NOT NULL,
  `quantity_stock` int(200) NOT NULL,
  `prix_unitaire` int(200) NOT NULL,
  `Description_produit` tinytext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produit_lavage`
--

CREATE TABLE `produit_lavage` (
  `identifiant` int(11) NOT NULL,
  `id_lavage` int(200) NOT NULL,
  `id_produit` int(200) NOT NULL,
  `utilisation_produit` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `recu_paiement`
--

CREATE TABLE `recu_paiement` (
  `id_recu` int(11) NOT NULL,
  `id_facture` int(11) NOT NULL,
  `id_employé` int(11) NOT NULL,
  `date_paiement` date NOT NULL DEFAULT current_timestamp(),
  `heure_paiement` time NOT NULL DEFAULT current_timestamp(),
  `montant_paye` float NOT NULL,
  `mode_paiement` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `repassage`
--

CREATE TABLE `repassage` (
  `id_repassage` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `temps_debut` datetime NOT NULL,
  `temps_fin` datetime DEFAULT NULL,
  `commentaires` tinytext DEFAULT NULL,
  `statut` varchar(200) NOT NULL,
  `unique_id` int(11) NOT NULL,
  `pieces` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `responsable`
--

CREATE TABLE `responsable` (
  `id_responsable` int(11) NOT NULL,
  `nom_respo` varchar(200) NOT NULL,
  `surname_respo` varchar(200) NOT NULL,
  `telephone` varchar(100) NOT NULL,
  `pourcentage` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sechage`
--

CREATE TABLE `sechage` (
  `id_sechage` int(11) NOT NULL,
  `id_lavage` int(11) NOT NULL,
  `temps_debut` datetime NOT NULL,
  `temps_fin` datetime DEFAULT NULL,
  `commentaires` tinytext DEFAULT NULL,
  `statut` varchar(200) NOT NULL,
  `type_sechage` varchar(200) NOT NULL,
  `etat_sechement` varchar(200) DEFAULT NULL,
  `unique_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id_client`);

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `id_client` (`id_client`),
  ADD KEY `id_responsable` (`id_responsable`);

--
-- Index pour la table `commande_lavage`
--
ALTER TABLE `commande_lavage`
  ADD PRIMARY KEY (`identifiant`);

--
-- Index pour la table `depenses`
--
ALTER TABLE `depenses`
  ADD PRIMARY KEY (`id_depense`),
  ADD KEY `id_employé` (`id_employé`);

--
-- Index pour la table `designation_listing`
--
ALTER TABLE `designation_listing`
  ADD PRIMARY KEY (`id_listing`),
  ADD KEY `id_commande` (`id_commande`);

--
-- Index pour la table `emballage`
--
ALTER TABLE `emballage`
  ADD PRIMARY KEY (`id_emballage`);

--
-- Index pour la table `employé`
--
ALTER TABLE `employé`
  ADD PRIMARY KEY (`id_employé`);

--
-- Index pour la table `factures`
--
ALTER TABLE `factures`
  ADD PRIMARY KEY (`id_facture`),
  ADD KEY `id_commande` (`id_commande`);

--
-- Index pour la table `journal`
--
ALTER TABLE `journal`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `livraisons`
--
ALTER TABLE `livraisons`
  ADD PRIMARY KEY (`id_livraison`),
  ADD KEY `id_commande` (`id_commande`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`);

--
-- Index pour la table `planifier_lavage`
--
ALTER TABLE `planifier_lavage`
  ADD PRIMARY KEY (`id_lavage`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id_produit`);

--
-- Index pour la table `produit_lavage`
--
ALTER TABLE `produit_lavage`
  ADD PRIMARY KEY (`identifiant`);

--
-- Index pour la table `recu_paiement`
--
ALTER TABLE `recu_paiement`
  ADD PRIMARY KEY (`id_recu`),
  ADD KEY `id_facture` (`id_facture`),
  ADD KEY `id_employé` (`id_employé`);

--
-- Index pour la table `repassage`
--
ALTER TABLE `repassage`
  ADD PRIMARY KEY (`id_repassage`);

--
-- Index pour la table `responsable`
--
ALTER TABLE `responsable`
  ADD PRIMARY KEY (`id_responsable`);

--
-- Index pour la table `sechage`
--
ALTER TABLE `sechage`
  ADD PRIMARY KEY (`id_sechage`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `commande_lavage`
--
ALTER TABLE `commande_lavage`
  MODIFY `identifiant` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `depenses`
--
ALTER TABLE `depenses`
  MODIFY `id_depense` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `designation_listing`
--
ALTER TABLE `designation_listing`
  MODIFY `id_listing` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `emballage`
--
ALTER TABLE `emballage`
  MODIFY `id_emballage` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `employé`
--
ALTER TABLE `employé`
  MODIFY `id_employé` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `factures`
--
ALTER TABLE `factures`
  MODIFY `id_facture` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `journal`
--
ALTER TABLE `journal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `livraisons`
--
ALTER TABLE `livraisons`
  MODIFY `id_livraison` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `planifier_lavage`
--
ALTER TABLE `planifier_lavage`
  MODIFY `id_lavage` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id_produit` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `produit_lavage`
--
ALTER TABLE `produit_lavage`
  MODIFY `identifiant` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `recu_paiement`
--
ALTER TABLE `recu_paiement`
  MODIFY `id_recu` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `repassage`
--
ALTER TABLE `repassage`
  MODIFY `id_repassage` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `responsable`
--
ALTER TABLE `responsable`
  MODIFY `id_responsable` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sechage`
--
ALTER TABLE `sechage`
  MODIFY `id_sechage` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id_client`),
  ADD CONSTRAINT `commandes_ibfk_2` FOREIGN KEY (`id_responsable`) REFERENCES `responsable` (`id_responsable`);

--
-- Contraintes pour la table `depenses`
--
ALTER TABLE `depenses`
  ADD CONSTRAINT `depenses_ibfk_2` FOREIGN KEY (`id_employé`) REFERENCES `employé` (`id_employé`);

--
-- Contraintes pour la table `factures`
--
ALTER TABLE `factures`
  ADD CONSTRAINT `factures_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commandes` (`id_commande`);

--
-- Contraintes pour la table `livraisons`
--
ALTER TABLE `livraisons`
  ADD CONSTRAINT `livraisons_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commandes` (`id_commande`);

--
-- Contraintes pour la table `recu_paiement`
--
ALTER TABLE `recu_paiement`
  ADD CONSTRAINT `recu_paiement_ibfk_1` FOREIGN KEY (`id_employé`) REFERENCES `employé` (`id_employé`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
