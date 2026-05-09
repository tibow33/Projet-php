-- Base de données pour le projet PHP

-- Table Compte
CREATE TABLE Compte (
    idCompte BIGINT PRIMARY KEY AUTO_INCREMENT,
    pseudo VARCHAR(32) NOT NULL,
    eMail VARCHAR(255),
    age TINYINT,
    mdp VARCHAR(64) NOT NULL,
    idTeam BIGINT,
    typeCompte VARCHAR(20)
);

-- Table Team
CREATE TABLE Team (
    idTeam BIGINT PRIMARY KEY AUTO_INCREMENT,
    nomTeam VARCHAR(64) NOT NULL,
    chef BIGINT,
    FOREIGN KEY (chef) REFERENCES Compte(idCompte)
);

-- Ajout de la clé étrangère de joeur vers team
ALTER TABLE Compte
	ADD CONSTRAINT fk_compte_team
	FOREIGN KEY (idTeam) REFERENCES Team(idTeam);


-- Table Jeu
CREATE TABLE Jeu (
    idJeu BIGINT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    typeJeu VARCHAR(255),
    age TINYINT,
    descJeu VARCHAR(1024)
);

-- --Table EloJoueur
-- CREATE TABLE EloJoueur (
--     idCompte BIGINT,
--     idJeu BIGINT,
--     elo INT,
--     PRIMARY KEY (idCompte, idJeu),
--     FOREIGN KEY (idCompte) REFERENCES Compte(idCompte),
--     FOREIGN KEY (idJeu) REFERENCES Jeu(idJeu)
-- );

-- Table Tournoi
CREATE TABLE Tournoi(
    idTournoi BIGINT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    dateDebut DATE,
    dateFin DATE,
    idJeu BIGINT,
    descTournoi VARCHAR(1024),
    nbTeam TINYINT,
    eloMin INT,
    FOREIGN KEY (idJeu) REFERENCES Jeu(idJeu)
);

-- Table associative Team\Tournoi
CREATE TABLE Joue (
    idTournoi BIGINT,
    idTeam BIGINT,
    PRIMARY KEY (idTournoi, idTeam),
    FOREIGN KEY (idTournoi) REFERENCES Tournoi(idTournoi),
    FOREIGN KEY (idTeam) REFERENCES Team(idTeam)
);

-- Table Sponsor 
CREATE TABLE Sponsor (
    idSponsor BIGINT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(64),
    eMailSponsor VARCHAR(255),
    mdpSponsor VARCHAR(64)
);

-- Association porteuse de Sponsor vers Team
CREATE TABLE Sponsorise (
    idSponsor BIGINT,
    idTeam BIGINT,
    PRIMARY KEY (idSponsor, idTeam),
    FOREIGN KEY (idSponsor) REFERENCES Sponsor(idSponsor),
    FOREIGN KEY (idTeam) REFERENCES Team(idTeam)
);

-- Association porteuse de Sponsor vers Tournoi
CREATE TABLE Parraine (
    idSponsor BIGINT,
    idTournoi BIGINT,
    PRIMARY KEY (idSponsor, idTournoi),
    FOREIGN KEY (idSponsor) REFERENCES Sponsor(idSponsor),
    FOREIGN KEY (idTournoi) REFERENCES Tournoi(idTournoi)
);

-- Table Match
CREATE TABLE Match (
    idMatch BIGINT PRIMARY KEY AUTO_INCREMENT,
    idTournoi BIGINT,
    idPhase BIGINT,
    dateMatch DATE,
    FOREIGN KEY (idTournoi) REFERENCES Tournoi(idTournoi),
    FOREIGN KEY (idPhase) REFERENCES Phase(idPhase)
);

-- Table Phase
CREATE TABLE Phase (
    idPhase BIGINT PRIMARY KEY AUTO_INCREMENT,
    nomPhase VARCHAR(255)
);