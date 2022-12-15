
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



create table TAG (
    LABEL    text primary key,
    CREATEUR text not null,
    constraint FK_CREATEUR foreign key (CREATEUR) references UTILISATEUR(USER_LOGIN)
);

create table QCM (
    ID          integer primary key autoincrement,
    TITRE       text not null,
    DESCRIPTION text not null,
    TYPE        text not null check (TYPE in ('statique', 'dynamique')),
    CREATEUR    text not null,
    EST_PUBLIC  BOOLEAN not null,
    constraint FK_CREATEUR foreign key (CREATEUR) references UTILISATEUR(USER_LOGIN)
);

create table niveau (
    LABEL_TAG text not null,
    ID_QCM    int not null,
    VALEUR    int not null,
    constraint FK_TAG_NIVEAU foreign key (LABEL_TAG) references TAG(LABEL),
    constraint FK_QCM_NIVEAU foreign key (ID_QCM)  references QCM(ID),
    primary key (LABEL_TAG, ID_QCM)
);

create table TYPE (
    LABEL text primary key
);

create table QUESTION (
    ID             integer primary key autoincrement,
    LABEL          text not null,
    ETAT           text check ( ETAT in ('A_VERIFIER', 'A_MODIFIER', 'ACCEPTE') ) not null,
    TYPE           text not null,
    ID_UTILISATEUR integer not null,
    constraint FK_QUESTION_UTILISATEUR foreign key (ID_UTILISATEUR) references UTILISATEUR(USER_LOGIN),
    constraint FK_QUESTION_TYPE foreign key (TYPE) references TYPE(LABEL)
);

create table REPONSE (
    ID integer primary key autoincrement,
    LABEL text not null,
    ETAT_VERITE BOOLEAN not null,
    QUESTION_ID int not null,
    constraint FK_QUESTION_REPONSE foreign key (QUESTION_ID) references QUESTION(ID)
);


create table DEPOT (
    ID             INTEGER not null primary key autoincrement,
    TITRE          TEXT not null,
    DESCRIPTION    TEXT not null,
    status         boolean not null,
    DATE_OUVERTURE datetime not null,
    DATE_FERMETURE datetime not null,
    CREATEUR       TEXT
    constraint DEPOT_UTILISATEUR_USER_LOGIN_fk
        references UTILISATEUR
);



create table entrainer (
    UTILISATEUR text not null,
    QCM int not null,
    TEMPS_PASSE int not null,
    SCORE NUMBER(5,2) not null,
    constraint FK_UTILISATEUR_ENTRAINER foreign key (UTILISATEUR) references UTILISATEUR(USER_LOGIN),
    constraint FK_QCM_ENTRAINER foreign key (QCM) references QCM(ID),
    primary key (UTILISATEUR, QCM)
);

create table composer (
    ID_QCM int not null,
    ID_QUESTION int not null,
    NB_TENTATIVES_TOTAL int not null,
    NB_TENTATIVES_REUSSIES int not null,
    constraint FK_QCM_COMPOSER foreign key (ID_QCM) references QCM(ID),
    constraint FK_QUESTION_COMPOSER foreign key (ID_QUESTION) references QUESTION(ID),
    primary key (ID_QCM, ID_QUESTION)
);

create table lier_tag_question (
    LABEL_TAG text not null,
    ID_QUESTION int not null,
    constraint FK_TAG_LIEE foreign key (LABEL_TAG) references TAG(LABEL),
    constraint FK_QUESTION_LIEE foreign key (ID_QUESTION) references QUESTION(ID),
    primary key (LABEL_TAG, ID_QUESTION)
);


create table contenir (
    ID_QUESTION int not null,
    ID_DEPOT int not null,
    constraint FK_QUESTION_CONTENIR foreign key (ID_QUESTION) references QUESTION(ID),
    constraint FK_DEPOT_CONTENIR foreign key (ID_DEPOT) references DEPOT(ID)
);


create table ROLE (
    ROLE_ID integer primary key autoincrement,
    ROLE_NAME text not null
);

insert into ROLE (ROLE_NAME) values ('ETUDIANT');
insert into ROLE (ROLE_NAME) values ('ENSEIGNANT');
insert into ROLE (ROLE_NAME) values ('ADMINISTRATEUR');


create table if not exists UTILISATEUR (
    USER_LOGIN text primary key,
    USER_FIRST_NAME text not null,
    USER_LAST_NAME text not null,
    USER_EMAIL text not null,
    USER_PASSWORD text not null,
    USER_ROLE_ID integer not null references ROLE(ROLE_ID),
    USER_TD integer default 0,
    USER_TP integer default 0,

    unique (USER_EMAIL)
);

create table if not exists ETUDIANT (
    ETUDIANT_LOGIN text primary key references UTILISATEUR (USER_LOGIN),
    TD integer not null,
    TP integer not null
);

-- ======
-- Ce trigger permet que dès qu'un utilisateur est inséré dans la table 'UTILISATEUR', on
-- vérifie si c'est un étudiant, si c'est le cas, on l'ajoute alors dans la table 'ETUDIANT'.
-- ======
create trigger if not exists UTILISATEUR_AFTER_INSERT
after insert on UTILISATEUR when new.USER_ROLE_ID = 1
begin
    insert into ETUDIANT (ETUDIANT_LOGIN, TD, TP) values (new.USER_LOGIN, new.USER_TD, new.USER_TP);
end;

-- ======
-- Ce trigger permet que dès qu'un utilisateur est update dans la table 'UTILISATEUR', on
-- vérifie si c'est un étudiant, si c'est le cas, on l'update alors dans la table 'ETUDIANT'.
-- ======
create trigger if not exists UTILISATEUR_AFTER_UPDATE
after update on UTILISATEUR when new.USER_ROLE_ID = 1
begin
    update ETUDIANT set TD = new.USER_TD, TP = new.USER_TP where ETUDIANT_LOGIN = new.USER_LOGIN;
end;

-- ======
-- Ce trigger permet que dès qu'un utilisateur est supprimé dans la table 'UTILISATEUR', on
-- vérifie si c'est un étudiant, si c'est le cas, on le supprime alors dans la table 'ETUDIANT'.
-- ======
create trigger if not exists UTILISATEUR_AFTER_DELETE
after delete on UTILISATEUR when old.USER_ROLE_ID = 1
begin
    delete from ETUDIANT where ETUDIANT_LOGIN = old.USER_LOGIN;
end;
--

-- INSERTION QCM
insert into QCM (TITRE, DESCRIPTION, TYPE, CREATEUR, EST_PUBLIC)
values ('QCM 1', 'Description du QCM 1', 'statique','bruyere', 1);

-- INSERTION DEPOT
insert into DEPOT (TITRE, DESCRIPTION, STATUS, DATE_OUVERTURE, DATE_FERMETURE, CREATEUR)
values ('R3.01', 'Developpement web', 1, '2022-12-14 00:00:00', '2022-12-19 00:00:00', 'bruyere');

insert into DEPOT (TITRE, DESCRIPTION, STATUS, DATE_OUVERTURE, DATE_FERMETURE, CREATEUR)
values ('R3.01 Partie 2', 'Developpement web', 1, '2022-12-19 00:00:00', '2022-12-31 00:00:00', 'bruyere');

insert into DEPOT (TITRE, DESCRIPTION, STATUS, DATE_OUVERTURE, DATE_FERMETURE, CREATEUR)
values ('R3.09', 'Developpement web', 1, '2022-12-14 00:00:00', '2022-12-19 00:00:00', 'bruyere');
