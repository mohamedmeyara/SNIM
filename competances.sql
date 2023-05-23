-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 23 mai 2023 à 15:08
-- Version du serveur : 10.4.25-MariaDB
-- Version de PHP : 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `snim`
--

-- --------------------------------------------------------

--
-- Structure de la table `competances`
--

CREATE TABLE `competances` (
  `id` int(11) NOT NULL,
  `stagiaire_code` varchar(255) DEFAULT NULL,
  `competance` varchar(255) DEFAULT NULL,
  `niveau_de_competance` enum('Debutant','Intermediaire','Expert') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `competances`
--

INSERT INTO `competances` (`id`, `stagiaire_code`, `competance`, `niveau_de_competance`) VALUES
(1, '735', 'Gestion de stress', 'Intermediaire');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `competances`
--
ALTER TABLE `competances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stagiaire_code` (`stagiaire_code`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `competances`
--
ALTER TABLE `competances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `competances`
--
ALTER TABLE `competances`
  ADD CONSTRAINT `competances_ibfk_1` FOREIGN KEY (`stagiaire_code`) REFERENCES `les_stagiaires` (`code`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
