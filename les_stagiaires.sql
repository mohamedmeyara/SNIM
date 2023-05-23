-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 23 mai 2023 à 15:06
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
-- Structure de la table `les_stagiaires`
--

CREATE TABLE `les_stagiaires` (
  `code` varchar(50) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `nni` varchar(255) NOT NULL,
  `ecole` varchar(255) NOT NULL,
  `niveau` varchar(255) NOT NULL,
  `direction` varchar(255) NOT NULL,
  `departement` varchar(255) NOT NULL,
  `service` varchar(255) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `sujet` varchar(255) DEFAULT NULL,
  `technologies` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `les_stagiaires`
--

INSERT INTO `les_stagiaires` (`code`, `nom`, `nni`, `ecole`, `niveau`, `direction`, `departement`, `service`, `date_debut`, `date_fin`, `sujet`, `technologies`) VALUES
('17', 'kader', '24262346512', 'ISCAE', 'MASTER-M1', '1', '1', '1', '2023-05-04', '2023-06-10', '', ''),
('24', 'Table zeroug', '87492524', 'ISCAE', 'LICENSE-L1', 'FTC', 'FST', 'FFS', '2023-04-15', '2023-05-14', '', ''),
('292', 'Sidi Cheikh', '54325452', 'Ecole des offices', 'LICENSE-L2', 'FTC', 'FST', 'FFS', '2023-04-30', '2023-06-09', '', ''),
('488', 'Fah Sayem', '49843298', 'ISCAE', 'LICENSE-L2', '1', '1', '1', '2023-04-30', '2023-06-10', '', ''),
('672', 'Nevisse Meyara', '49843298', 'LIP', 'MASTER-M1', '1', '1', '1', '2023-03-26', '2023-05-01', 'Pratique', ''),
('735', 'Abdulah Mohamed', '24262346512', 'ISCAE', 'LICENSE-L1', 'DTI', 'CTI', 'developpement', '2023-05-01', '2023-05-14', '', ''),
('764', 'Cheikh Kerime', '54325452', 'IP', 'MASTER-M2', 'DTI', 'CTI', 'developpement', '2023-04-30', '2023-06-10', '', ''),
('769', 'Mariem Ahmed Khalil', '54325452', 'Izet', 'LICENSE-L2', 'FTC', 'FST', 'FFS', '2023-05-03', '2023-06-10', '', ''),
('77', 'Mohamed Meyara Sidi Med', '2575910079', 'ISTIC', 'BTS', 'DTI', 'CTI', 'developpement', '2023-03-20', '2023-05-20', 'Gestion des stagiaires', 'Html css php mysql js ');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `les_stagiaires`
--
ALTER TABLE `les_stagiaires`
  ADD PRIMARY KEY (`code`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
