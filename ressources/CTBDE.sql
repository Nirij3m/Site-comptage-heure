DROP TABLE IF EXISTS Speciality CASCADE;
DROP TABLE IF EXISTS Users CASCADE;
DROP TABLE IF EXISTS Timeslot CASCADE;

/* Cette table se réfère à la fonction du User au sein du BDE */
CREATE TABLE Speciality(
	id     SERIAL NOT NULL , /* AUTO */
	type   VARCHAR (128) NOT NULL  , /* La fonction du User au sein du BDE. ex: 'Président', 'Vice-Président', 'Trésorier', 'Pôle Communication', 'Responsable Pôle Communication', etc... Libre à vous de nommer les fonctions comme vous le souhaitez */
	CONSTRAINT Speciality_PK PRIMARY KEY (id)
);

/* Cette table décrit un utlisateur, soit un membre du BDE */
CREATE TABLE Users(
	id              SERIAL NOT NULL , /* AUTO */
	name            VARCHAR (128) NOT NULL , /* Nom */
	surname         VARCHAR (128) NOT NULL , /* Prénom */
    cycle           VARCHAR (5), /* Le cycle du User. ex: 'CIR2', 'CEST1', etc... */
	mail            VARCHAR (128) NOT NULL UNIQUE, /* L'adresse mail du User, attention cette dernière est utilisée pour se connecter sur le site ! */
	password        VARCHAR (60) NOT NULL , /* Mot de passe de l'utilisateur. Attention! Il faut entrer ici le mot de passe hash et non en brut !. Se référer au README pour plus d'explication. */
	is_admin 		BOOLEAN NOT NULL, /* Défini si l'utilisateur est adminstrateur ou non. Un administrateur possède des droits particuliers qui lui permettent de valider/refuser et visuliaser les heures des autres */ 
	id_Speciality   INT    NOT NULL, /* L'ID de la Speciality du User, c'est à dire l'ID de sa fonction associée */

	CONSTRAINT User_PK PRIMARY KEY (id)

	,CONSTRAINT User_Speciality_FK FOREIGN KEY (id_Speciality) REFERENCES Speciality(id)
);

CREATE TABLE Timeslot(
	id          SERIAL NOT NULL , /* AUTO */
	date   DATE  NOT NULL , /* Date à laquelle a été réalisée le temps de travail au format YYYY-MM-DD*/
	duration DECIMAL NOT NULL, /* La durée du temps de travail en heure ! */
	id_User     INT  NOT NULL  , /* L'ID du user ayant réalisé l'heure de travail */
    id_User_Validated INT NULL, /* L'ID du user ayant validé le temps de travail (un admin typiquement) */
    is_validated SMALLINT NULL, /* 1 ou 0 pour indiquer si l'heure a été validée ou non par un administrateur. 0 -> refusée, 1 -> validée, pas de valeur -> en attente de validation. */
    description TEXT NOT NULL, /* Une description du temps de travail, pour savoir si c'est crédible ;) */
	CONSTRAINT Timeslot_PK PRIMARY KEY (id)

	,CONSTRAINT Timeslot_User_FK FOREIGN KEY (id_User) REFERENCES Users(id)
    ,CONSTRAINT Timeslot_User_Validated_FK FOREIGN KEY (id_User) REFERENCES Users(id)
);