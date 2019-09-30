GRANT ALL ON festival . * TO 'festival'@'localhost' IDENTIFIED BY 'secret';

DROP TABLE IF EXISTS `Attribution`;
DROP TABLE IF EXISTS `Groupe`;
DROP TABLE IF EXISTS `Etablissement`;

CREATE TABLE Etablissement 
(
    id char(8) NOT NULL,
    mdp CHAR(60) NOT NULL,
    nom varchar(45) NOT NULL,
    adresseRue varchar(45) NOT NULL, 
    codePostal char(5) NOT NULL, 
    ville varchar(35) NOT NULL,
    tel varchar(13) NOT NULL,
    adresseElectronique varchar(70),
    type tinyint NOT NULL,
    civiliteResponsable varchar(12) NOT NULL,
    nomResponsable varchar(25) NOT NULL,
    prenomResponsable varchar(25),
    nombreChambresOffertes integer,
    CONSTRAINT pk_Etablissement PRIMARY KEY(id)
) ENGINE=INNODB;

CREATE TABLE Groupe
(
    id char(4) NOT NULL,
    nom varchar(40) NOT NULL,
    identiteResponsable varchar(40) default null,
    adressePostale varchar(120) default null,
    nombrePersonnes integer NOT NULL,
    nomPays varchar(40) NOT NULL,
    hebergement char(1) NOT NULL,
    CONSTRAINT pk_Groupe PRIMARY KEY(id)
) ENGINE=INNODB;

CREATE TABLE Attribution
(
    idEtab char(8) NOT NULL,
    idGroupe char(4) NOT NULL,
    nombreChambres integer NOT NULL,
    CONSTRAINT pk_Attribution PRIMARY KEY(idEtab, idGroupe),
    CONSTRAINT fk1_Attribution FOREIGN KEY(idEtab) REFERENCES Etablissement(id),
    CONSTRAINT fk2_Attribution FOREIGN KEY(idGroupe) REFERENCES Groupe(id)
) ENGINE=INNODB;
