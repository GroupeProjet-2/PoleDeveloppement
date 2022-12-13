
drop table if exists ROLE;
drop table if exists UTILISATEUR;

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

create trigger if not exists UTILISATEUR_AFTER_INSERT
after insert on UTILISATEUR when new.USER_ROLE_ID = 1
begin
    insert into ETUDIANT (ETUDIANT_LOGIN, TD, TP) values (new.USER_LOGIN, new.USER_TD, new.USER_TP);
end;
