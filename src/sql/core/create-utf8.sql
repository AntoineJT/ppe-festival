GRANT ALL ON festival . * TO 'festival'@'localhost' IDENTIFIED BY 'secret';

DROP TABLE IF EXISTS `Attribution`;
DROP TABLE IF EXISTS `Groupe`;
DROP TABLE IF EXISTS `Etablissement`;

CREATE TABLE Etablissement 
(
    id CHAR(8) NOT NULL,
    nom VARCHAR(45) NOT NULL,
    adresseRue VARCHAR(45) NOT NULL, 
    codePostal CHAR(5) NOT NULL, 
    ville VARCHAR(35) NOT NULL,
    tel VARCHAR(13) NOT NULL,
    adresseElectronique VARCHAR(70),
    type tinyint NOT NULL,
    civiliteResponsable VARCHAR(12) NOT NULL,
    nomResponsable VARCHAR(25) NOT NULL,
    prenomResponsable VARCHAR(25),
    nombreChambresOffertes INTEGER,
    CONSTRAINT pk_Etablissement PRIMARY KEY(id)
) ENGINE=InnoDB;

CREATE TABLE Groupe
(
    id CHAR(4) NOT NULL,
    nom VARCHAR(40) NOT NULL,
    identiteResponsable VARCHAR(40) DEFAULT NULL,
    adressePostale VARCHAR(120) DEFAULT NULL,
    nombrePersonnes INTEGER NOT NULL,
    nomPays VARCHAR(40) NOT NULL,
    hebergement CHAR(1) NOT NULL,
    CONSTRAINT pk_Groupe PRIMARY KEY(id)
) ENGINE=InnoDB;

CREATE TABLE Attribution
(
    idEtab CHAR(8) NOT NULL,
    idGroupe CHAR(4) NOT NULL,
    nombreChambres INTEGER NOT NULL,
    CONSTRAINT pk_Attribution PRIMARY KEY(idEtab, idGroupe),
    CONSTRAINT fk1_Attribution FOREIGN KEY(idEtab) REFERENCES Etablissement(id),
    CONSTRAINT fk2_Attribution FOREIGN KEY(idGroupe) REFERENCES Groupe(id)
) ENGINE=InnoDB;

CREATE TABLE Administrateur
(
    idAdmin INT AUTO_INCREMENT,
    nomAdmin VARCHAR(12) NOT NULL,
    mdp CHAR(60) NOT NULL,
    CONSTRAINT pk_Admin PRIMARY KEY(idAdmin)
) ENGINE=InnoDB;
