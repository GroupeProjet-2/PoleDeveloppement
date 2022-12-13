drop table if exists ROLE;
drop table if exists UTILISATEUR;
drop table if exists TAG;
drop table if exists QCM;
drop table if exists niveau;
drop table if exists TYPE;
drop table if exists QUESTION;
drop table if exists REPONSE;
drop table if exists entrainer;
drop table if exists composer;
drop table if exists lier_tag_question;
drop table if exists contenir;
drop table if exists ROLE;



CREATE TABLE TAG
(
    LABEL    VARCHAR(50) PRIMARY KEY,
    CREATEUR VARCHAR(50) NOT NULL,
    CONSTRAINT FK_CREATEUR FOREIGN KEY (CREATEUR) REFERENCES UTILISATEUR (USER_LOGIN)
);

CREATE TABLE QCM
(
    ID          INT PRIMARY KEY,
    TITRE       VARCHAR(50)  NOT NULL,
    DESCRIPTION VARCHAR(255) NOT NULL,
    CREATEUR    VARCHAR(50)  NOT NULL,
    EST_PUBLIC  BOOLEAN      NOT NULL,
    CONSTRAINT FK_CREATEUR FOREIGN KEY (CREATEUR) REFERENCES UTILISATEUR (USER_LOGIN)
);

CREATE TABLE niveau
(
    LABEL_TAG VARCHAR(50) NOT NULL,
    ID_QCM    INT         NOT NULL,
    VALEUR    INT         NOT NULL,
    CONSTRAINT FK_TAG_NIVEAU FOREIGN KEY (LABEL_TAG) REFERENCES TAG (LABEL),
    CONSTRAINT FK_QCM_NIVEAU FOREIGN KEY (ID_QCM) REFERENCES QCM (ID),
    PRIMARY KEY (LABEL_TAG, ID_QCM)
);

CREATE TABLE TYPE
(
    LABEL VARCHAR(25) PRIMARY KEY
);

CREATE TABLE QUESTION
(
    ID             INTEGER PRIMARY KEY AUTOINCREMENT,
    LABEL          VARCHAR(255)                                                                NOT NULL,
    ETAT           VARCHAR(10) check ( ETAT in (''A_VERIFIER'', ''A_MODIFIER'', ''ACCEPTE'') ) NOT NULL,
    TYPE           VARCHAR(25)                                                                 NOT NULL,
    ID_UTILISATEUR NUMBER(10)                                                                  NOT NULL,
    CONSTRAINT FK_QUESTION_UTILISATEUR FOREIGN KEY (ID_UTILISATEUR) REFERENCES UTILISATEUR (USER_LOGIN),
    CONSTRAINT FK_QUESTION_TYPE FOREIGN KEY (TYPE) REFERENCES TYPE (LABEL)
);

CREATE TABLE REPONSE
(
    ID          INTEGER PRIMARY KEY AUTOINCREMENT,
    LABEL       VARCHAR(255) NOT NULL,
    ETAT_VERITE BOOLEAN      NOT NULL,
    QUESTION_ID INT          NOT NULL,
    CONSTRAINT FK_QUESTION_REPONSE FOREIGN KEY (QUESTION_ID) REFERENCES QUESTION (ID)
);

CREATE TABLE entrainer
(
    UTILISATEUR VARCHAR(50)  NOT NULL,
    QCM         INT          NOT NULL,
    TEMPS_PASSE INT          NOT NULL,
    SCORE       NUMBER(5, 2) NOT NULL,
    CONSTRAINT FK_UTILISATEUR_ENTRAINER FOREIGN KEY (UTILISATEUR) REFERENCES UTILISATEUR (USER_LOGIN),
    CONSTRAINT FK_QCM_ENTRAINER FOREIGN KEY (QCM) REFERENCES QCM (ID),
    PRIMARY KEY (UTILISATEUR, QCM)
);

CREATE TABLE composer
(
    ID_QCM                 INT NOT NULL,
    ID_QUESTION            INT NOT NULL,
    NB_TENTATIVES_TOTAL    INT NOT NULL,
    NB_TENTATIVES_REUSSIES INT NOT NULL,
    CONSTRAINT FK_QCM_COMPOSER FOREIGN KEY (ID_QCM) REFERENCES QCM (ID),
    CONSTRAINT FK_QUESTION_COMPOSER FOREIGN KEY (ID_QUESTION) REFERENCES QUESTION (ID),
    PRIMARY KEY (ID_QCM, ID_QUESTION)
);

CREATE TABLE lier_tag_question
(
    LABEL_TAG   VARCHAR(50) NOT NULL,
    ID_QUESTION INT         NOT NULL,
    CONSTRAINT FK_TAG_LIEE FOREIGN KEY (LABEL_TAG) REFERENCES TAG (LABEL),
    CONSTRAINT FK_QUESTION_LIEE FOREIGN KEY (ID_QUESTION) REFERENCES QUESTION (ID),
    PRIMARY KEY (LABEL_TAG, ID_QUESTION)
);


CREATE TABLE contenir
(
    ID_QUESTION INT NOT NULL,
    ID_DEPOT    INT NOT NULL,
    CONSTRAINT FK_QUESTION_CONTENIR FOREIGN KEY (ID_QUESTION) REFERENCES QUESTION (ID),
    CONSTRAINT FK_DEPOT_CONTENIR FOREIGN KEY (ID_DEPOT) REFERENCES DEPOT (ID)
);


create table ROLE
(
    ROLE_ID   integer primary key autoincrement,
    ROLE_NAME text not null
);

insert into ROLE (ROLE_NAME)
values (''ETUDIANT'');
insert into ROLE (ROLE_NAME)
values (''ENSEIGNANT'');
insert into ROLE (ROLE_NAME)
values (''ADMINISTRATEUR'');


create table if not exists UTILISATEUR
(
    USER_LOGIN      text primary key,
    USER_FIRST_NAME text    not null,
    USER_LAST_NAME  text    not null,
    USER_EMAIL      text    not null,
    USER_PASSWORD   text    not null,
    USER_ROLE_ID    integer not null references ROLE (ROLE_ID),
    USER_TD         integer default 0,
    USER_TP         integer default 0,

    unique (USER_EMAIL)
);

create table if not exists ETUDIANT
(
    ETUDIANT_LOGIN text primary key references UTILISATEUR (USER_LOGIN),
    TD             integer not null,
    TP             integer not null
);

-- ======
-- Ce trigger permet que dès qu''un utilisateur est inséré dans la table ''UTILISATEUR'', on
-- vérifie si c''est un étudiant, si c''est le cas, on l''ajoute alors dans la table ''ETUDIANT''.
-- ======
create trigger if not exists UTILISATEUR_AFTER_INSERT
    after insert
    on UTILISATEUR
    when new.USER_ROLE_ID = 1
begin
    insert into ETUDIANT (ETUDIANT_LOGIN, TD, TP) values (new.USER_LOGIN, new.USER_TD, new.USER_TP);
end;
