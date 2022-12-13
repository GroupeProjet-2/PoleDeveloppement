
drop table if exists ROLE;
drop table if exists USER;

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

    unique (USER_EMAIL)
);

create table if not exists ETUDIANT (
    ETUDIANT_LOGIN text primary key references UTILISATEUR (USER_LOGIN),
    TD integer not null,
    TP integer not null
);
