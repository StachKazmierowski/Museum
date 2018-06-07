--tabele

drop table if exists Ekspozycja cascade;
drop table if exists Sala cascade;
drop table if exists Galeria cascade;
drop table if exists WystawaObjazdowa cascade;
drop table if exists Eksponat cascade;
drop table if exists Artysta cascade;


create table Artysta (
  id serial primary key,
  imie varchar(15) not null,
  nazwisko varchar(15) not null,
  rokUrodzenia int not null,
  rokSmierci int,
  constraint checkArtystaDaty check (rokSmierci is NULL or rokUrodzenia <= rokSmierci));

create table Eksponat (
  id serial primary key,
  tytul varchar(30) not null,
  typ varchar(20) not null,
  wysokosc int not null,
  szerokosc int not null,
  waga int not null,
  idTworca int references Artysta,
  constraint checkEksponatWymiary check (wysokosc >= 0 and szerokosc >= 0 and waga > 0));

create table Galeria (
  id serial primary key,
  nazwa varchar(30) not null);

create table Sala (
  nr int not null,
  pojemnosc int not null,
  idGaleria int not null references Galeria,
  primary key (nr, idGaleria),
  constraint checkSalaPojemosc check (pojemnosc >= 0));

create table WystawaObjazdowa (
  id serial primary key,
  miasto varchar(30) not null);

create table Ekspozycja (
  id serial primary key,
  idEksponat int not null references Eksponat,
  idGaleria int,
  nrSala int,
  foreign key (idGaleria, nrSala) references Sala(idGaleria, nr),
  idWystawaObjazdowa int references WystawaObjazdowa,
  dataRozpoczecia date not null,
  dataZakonczenia date,
  constraint checkEkspozycjaDaty check (dataZakonczenia is null or dataRozpoczecia <= dataZakonczenia),
  constraint checkEkspozycjaMiejsce check ((idGaleria is null and nrSala is null and idWystawaObjazdowa is not null and dataZakonczenia is not null) or (idGaleria is not null and nrSala is not null and idWystawaObjazdowa is null)));

