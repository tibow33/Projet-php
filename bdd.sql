-- --------------------------------------------------------
-- Hôte:                         localhost
-- Version du serveur:           8.0.40 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour projetphp
CREATE DATABASE IF NOT EXISTS `projetphp` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `projetphp`;

-- Listage de la structure de table projetphp. compte
CREATE TABLE IF NOT EXISTS `compte` (
  `idCompte` bigint NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(32) NOT NULL,
  `eMail` varchar(255) DEFAULT NULL,
  `dateCreation` date DEFAULT NULL,
  `mdp` varchar(64) NOT NULL,
  `idTeam` bigint DEFAULT NULL,
  `typeCompte` varchar(20) DEFAULT NULL,
  `descCompte` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`idCompte`),
  KEY `fk_compte_team` (`idTeam`),
  CONSTRAINT `fk_compte_team` FOREIGN KEY (`idTeam`) REFERENCES `team` (`idTeam`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projetphp.compte : ~12 rows (environ)
INSERT IGNORE INTO `compte` (`idCompte`, `pseudo`, `eMail`, `dateCreation`, `mdp`, `idTeam`, `typeCompte`, `descCompte`) VALUES
	(1, 'Ilann.17', 'ilann.souchet1@orange.fr', NULL, '$2y$10$e8S5CtLlSo0ukHM3FG1adeaXigSO98Oh1q.RLKS5ECumHqtVfSm6u', NULL, NULL, NULL),
	(2, 'Test', 'oui', NULL, '$2y$10$3sCJ042RTJ0ovJ9rX8POxO/CwonAlnLK/mSnqXg52AlSaZPYx7mJu', NULL, 'Joueur', NULL),
	(3, 'GamerDu17', 'ilann.souchet1@orange.fr', NULL, '$2y$10$NGER6sZ0po4P9CRXI1Xq/.Sw8XuJcF/Jfua4VmZL/8rZihtD0oTji', NULL, NULL, NULL),
	(4, 'TheGamerDu17', 'test@orange.fr', NULL, '$2y$10$4MsIVGT3ERMxL5QNHDir1OHiNN6jBY/TRi016RlaTwsWUEIgS9TGu', NULL, NULL, 'Bonjour'),
	(5, 'ChefTeam1', 'test@gmail.com', '2026-05-19', '$2y$10$aGOikqs2cQyF6R3mpIhBruvD5CiIl4NAau7KWKZfkAXpYf2bpR/2a', NULL, NULL, ''),
	(6, 'ChefTeam2', 'test@gmail.com', '2026-05-19', '$2y$10$XQvu5Z0FuACThEcVTxxHUOny4XID6abd/jgISnz21wCfmKnkd8yCi', NULL, NULL, ''),
	(7, 'ChefTeam3', 'test@gmail.com', '2026-05-19', '$2y$10$93D6QjaQu0.iKYkJmWn.xOYijafPIXTfrhFdzpPDk24Pq1iA6tUVu', 11, NULL, ''),
	(8, 'ChefTeam4', 'test@gmail.com', '2026-05-19', '$2y$10$AXSiqtwkpvZ6QxDc5Mdyxul4LdDGzve1Ag78w646SPOtzJDl/.SkG', 12, NULL, ''),
	(9, 'ChefTeam5', 'test@orange.fr', '2026-05-19', '$2y$10$Iu.aahNlKWXqI6ar58beoOeuG06uS0yw7f6y1/zzwJ4hPaILrvMoa', 13, NULL, ''),
	(10, 'ChefTeam6', 'test@orange.fr', '2026-05-19', '$2y$10$4qf5NFuh3PNbbkZK5dZeo.j7AiBk0uoohdwqrz85I5KBlCCa1J4sC', 14, NULL, ''),
	(11, 'ChefTeam7', 'test@orange.fr', '2026-05-19', '$2y$10$Octak31l2xPoSws9RHePe.mIjGX5UlTa6Mh2L4kXVuxPrdJQ/V5Qu', 15, NULL, ''),
	(12, 'ChefTeam8', 'test@orange.fr', '2026-05-19', '$2y$10$VUdZvTdceqG7UqfBQju.keC0731zmCAlyVQLQMxMLHXs4Jr7tpew2', 16, NULL, '');


-- Listage de la structure de table projetphp. jeu
CREATE TABLE IF NOT EXISTS `jeu` (
  `idJeu` bigint NOT NULL AUTO_INCREMENT,
  `nomJeu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `typeJeu` varchar(255) DEFAULT NULL,
  `descJeu` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`idJeu`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projetphp.jeu : ~10 rows (environ)
INSERT IGNORE INTO `jeu` (`idJeu`, `nomJeu`, `typeJeu`, `descJeu`) VALUES
	(1, 'Counter-Strike 2', 'FPS', 'Jeu de tir mythique très compétitif sur PC'),
	(2, 'Hearts of Iron IV', 'Stratégie', 'Un jeu de stratégie de chez Paradox Interactive sur la Seconde Guerre Mondiale'),
	(3, 'League of Legends', 'MOBA', 'Rage et larmes au rendez-vous sur ce MOBA légendaire, notamment pour sa communauté'),
	(4, 'Rocket League', 'Course', 'Célèbre jeu de voitures volantes qui combine football et automobile'),
	(5, 'Tom Clancy\'s Rainbow Six Siege', 'FPS', 'Un FPS tactique multijoueur où les joueurs s\'affrontent dans un univers destructibles'),
	(6, 'Civilization VI', 'Stratégie', 'Un jeu de stratégie 4X où l\'on incarne une civilisation historique à travers plusieurs âges'),
	(7, 'DOTA', 'MOBA', 'LOL mais plus grand'),
	(8, 'Mario Kart World', 'Course', 'Aussi connu comme le briseurs d\'amitiés, Mario Kart est connu de partout'),
	(9, 'Age of Empires II', 'Stratégie', 'Le jeu RTS de référence, encore excellent aujourd\'hui, Wololo !'),
	(10, 'Super Smash Bros Ultimate', 'Combat', 'L\'autre briseur d\'amitiés, mais avec des poings et armes au lieu de carapaces');

-- Listage de la structure de table projetphp. matchs
CREATE TABLE IF NOT EXISTS `matchs` (
  `idMatch` bigint NOT NULL AUTO_INCREMENT,
  `idTournoi` bigint DEFAULT NULL,
  `idPhase` bigint DEFAULT NULL,
  `dateMatch` date DEFAULT NULL,
  `numeroMatch` int DEFAULT NULL,
  `matchSuivant` bigint DEFAULT NULL,
  PRIMARY KEY (`idMatch`),
  KEY `idTournoi` (`idTournoi`),
  KEY `idPhase` (`idPhase`),
  KEY `matchSuivant` (`matchSuivant`),
  CONSTRAINT `matchs_ibfk_1` FOREIGN KEY (`idTournoi`) REFERENCES `tournoi` (`idTournoi`),
  CONSTRAINT `matchs_ibfk_2` FOREIGN KEY (`idPhase`) REFERENCES `phase` (`idPhase`),
  CONSTRAINT `matchs_ibfk_3` FOREIGN KEY (`matchSuivant`) REFERENCES `matchs` (`idMatch`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projetphp.matchs : ~7 rows (environ)
INSERT IGNORE INTO `matchs` (`idMatch`, `idTournoi`, `idPhase`, `dateMatch`, `numeroMatch`, `matchSuivant`) VALUES
	(1, 3, 1, NULL, NULL, NULL),
	(2, 3, 1, NULL, NULL, NULL),
	(3, 3, 1, NULL, NULL, NULL),
	(4, 3, 1, NULL, NULL, NULL),
	(5, 3, 2, NULL, NULL, NULL),
	(6, 3, 2, NULL, NULL, NULL),
	(7, 3, 3, NULL, NULL, NULL);

-- Listage de la structure de table projetphp. match_compte
CREATE TABLE IF NOT EXISTS `match_compte` (
  `idMatch` bigint NOT NULL,
  `idCompte` bigint NOT NULL,
  PRIMARY KEY (`idMatch`,`idCompte`),
  KEY `idCompte` (`idCompte`),
  CONSTRAINT `match_compte_ibfk_1` FOREIGN KEY (`idMatch`) REFERENCES `matchs` (`idMatch`),
  CONSTRAINT `match_compte_ibfk_2` FOREIGN KEY (`idCompte`) REFERENCES `compte` (`idCompte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projetphp.match_compte : ~0 rows (environ)

-- Listage de la structure de table projetphp. match_team
CREATE TABLE IF NOT EXISTS `match_team` (
  `idMatch` bigint NOT NULL,
  `idTeam` bigint NOT NULL,
  `vainqueur` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`idMatch`,`idTeam`),
  KEY `idTeam` (`idTeam`),
  CONSTRAINT `match_team_ibfk_1` FOREIGN KEY (`idMatch`) REFERENCES `matchs` (`idMatch`),
  CONSTRAINT `match_team_ibfk_2` FOREIGN KEY (`idTeam`) REFERENCES `team` (`idTeam`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projetphp.match_team : ~0 rows (environ)
INSERT IGNORE INTO `match_team` (`idMatch`, `idTeam`, `vainqueur`) VALUES
	(1, 9, 0),
	(1, 11, 1);

-- Listage de la structure de table projetphp. parraine
CREATE TABLE IF NOT EXISTS `parraine` (
  `idSponsor` bigint NOT NULL,
  `idTournoi` bigint NOT NULL,
  PRIMARY KEY (`idSponsor`,`idTournoi`),
  KEY `idTournoi` (`idTournoi`),
  CONSTRAINT `parraine_ibfk_1` FOREIGN KEY (`idSponsor`) REFERENCES `sponsor` (`idSponsor`),
  CONSTRAINT `parraine_ibfk_2` FOREIGN KEY (`idTournoi`) REFERENCES `tournoi` (`idTournoi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projetphp.parraine : ~0 rows (environ)

-- Listage de la structure de table projetphp. participe
CREATE TABLE IF NOT EXISTS `participe` (
  `idTournoi` bigint NOT NULL,
  `idTeam` bigint NOT NULL,
  PRIMARY KEY (`idTournoi`,`idTeam`),
  KEY `idTeam` (`idTeam`),
  CONSTRAINT `participe_ibfk_1` FOREIGN KEY (`idTournoi`) REFERENCES `tournoi` (`idTournoi`),
  CONSTRAINT `participe_ibfk_2` FOREIGN KEY (`idTeam`) REFERENCES `team` (`idTeam`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projetphp.participe : ~8 rows (environ)
INSERT IGNORE INTO `participe` (`idTournoi`, `idTeam`) VALUES
	(3, 9),
	(3, 10),
	(3, 11),
	(3, 12),
	(3, 13),
	(3, 14),
	(3, 15),
	(3, 16);

-- Listage de la structure de table projetphp. phase
CREATE TABLE IF NOT EXISTS `phase` (
  `idPhase` bigint NOT NULL AUTO_INCREMENT,
  `nomPhase` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idPhase`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projetphp.phase : ~5 rows (environ)
INSERT IGNORE INTO `phase` (`idPhase`, `nomPhase`) VALUES
	(1, '16ème de finale'),
	(2, '8ème de finale'),
	(3, 'Quart de finale'),
	(4, 'Demi-finale'),
	(5, 'Finale');

-- Listage de la structure de table projetphp. sponsor
CREATE TABLE IF NOT EXISTS `sponsor` (
  `idSponsor` bigint NOT NULL AUTO_INCREMENT,
  `nom` varchar(64) DEFAULT NULL,
  `eMailSponsor` varchar(255) DEFAULT NULL,
  `mdpSponsor` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`idSponsor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projetphp.sponsor : ~0 rows (environ)

-- Listage de la structure de table projetphp. sponsorise
CREATE TABLE IF NOT EXISTS `sponsorise` (
  `idSponsor` bigint NOT NULL,
  `idTeam` bigint NOT NULL,
  PRIMARY KEY (`idSponsor`,`idTeam`),
  KEY `idTeam` (`idTeam`),
  CONSTRAINT `sponsorise_ibfk_1` FOREIGN KEY (`idSponsor`) REFERENCES `sponsor` (`idSponsor`),
  CONSTRAINT `sponsorise_ibfk_2` FOREIGN KEY (`idTeam`) REFERENCES `team` (`idTeam`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projetphp.sponsorise : ~0 rows (environ)

-- Listage de la structure de table projetphp. team
CREATE TABLE IF NOT EXISTS `team` (
  `idTeam` bigint NOT NULL AUTO_INCREMENT,
  `nomTeam` varchar(64) NOT NULL,
  `chef` bigint DEFAULT NULL,
  `tag` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`idTeam`),
  KEY `chef` (`chef`),
  CONSTRAINT `team_ibfk_1` FOREIGN KEY (`chef`) REFERENCES `compte` (`idCompte`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projetphp.team : ~8 rows (environ)
INSERT IGNORE INTO `team` (`idTeam`, `nomTeam`, `chef`, `tag`) VALUES
	(8, 'WoW', 1, 'WoW'),
	(9, 'Team1', 5, 'T1'),
	(10, 'Team2', 6, 'T2'),
	(11, 'Team3', 7, 'T3'),
	(12, 'Team4', 8, 'T4'),
	(13, 'Team5', 9, 'T5'),
	(14, 'Team6', 10, 'T6'),
	(15, 'Team7', 11, 'T7'),
	(16, 'Team8', 12, 'T8');

-- Listage de la structure de table projetphp. tournoi
CREATE TABLE IF NOT EXISTS `tournoi` (
  `idTournoi` bigint NOT NULL AUTO_INCREMENT,
  `nomTournoi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `dateDebut` date DEFAULT NULL,
  `dateFin` date DEFAULT NULL,
  `idJeu` bigint DEFAULT NULL,
  `descTournoi` varchar(1024) DEFAULT NULL,
  `nbTeam` tinyint DEFAULT NULL,
  `eloMin` int DEFAULT NULL,
  `createur` bigint DEFAULT NULL,
  `statut` enum('Ouvert','En cours','Terminé') DEFAULT 'Ouvert',
  PRIMARY KEY (`idTournoi`),
  KEY `idJeu` (`idJeu`),
  CONSTRAINT `tournoi_ibfk_1` FOREIGN KEY (`idJeu`) REFERENCES `jeu` (`idJeu`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table projetphp.tournoi : ~3 rows (environ)
INSERT IGNORE INTO `tournoi` (`idTournoi`, `nomTournoi`, `dateDebut`, `dateFin`, `idJeu`, `descTournoi`, `nbTeam`, `eloMin`, `createur`, `statut`) VALUES
	(1, 'Ouais Cheu', '2026-05-05', '2026-05-16', 6, 'Test de création de tournoi', 16, NULL, 2, 'Ouvert'),
	(2, 'Test', '2026-05-11', '2026-05-17', 9, 'Test de création de tournoi', 16, NULL, NULL, 'Ouvert'),
	(3, 'TheTournoi', '2026-04-30', '2026-05-29', 1, 'Test de création de tournoi', 8, NULL, 4, 'Ouvert');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
