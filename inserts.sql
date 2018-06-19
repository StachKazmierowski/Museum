--inserty

insert into Artysta(imie, nazwisko, rokUrodzenia, rokSmierci) values('Jan', 'Matejko', 1838, 1893);
insert into Artysta(imie, nazwisko, rokUrodzenia, rokSmierci) values('Sandro', 'Botticelli', 1445, 1510);
insert into Artysta(imie, nazwisko, rokUrodzenia, rokSmierci) values('Rafael', 'Santi', 1483, 1520);
insert into Artysta(imie, nazwisko, rokUrodzenia, rokSmierci) values('Michał', 'Anioł', 1475, 1564);
insert into Artysta(imie, nazwisko, rokUrodzenia, rokSmierci) values('Leonardo', 'da Vinci', 1452, 1519);


insert into Eksponat(tytul, typ, wysokosc, szerokosc, waga, idTworca) values('Stańczyk', 'obraz', 88, 120, 20, 1);
insert into Eksponat(tytul, typ, wysokosc, szerokosc, waga, idTworca) values('Unia lubelska', 'obraz', 298, 512, 70, 1);
insert into Eksponat(tytul, typ, wysokosc, szerokosc, waga, idTworca) values('Dawid', 'rzeźba', 517, 100, 5660, 4);
insert into Eksponat(tytul, typ, wysokosc, szerokosc, waga, idTworca) values('Narodziny Wenus', 'obraz', 173, 279, 45, 2);
insert into Eksponat(tytul, typ, wysokosc, szerokosc, waga, idTworca) values('Dama z gronostajem', 'obraz', 55, 40, 18, 5);
insert into Eksponat(tytul, typ, wysokosc, szerokosc, waga, idTworca) values('Ostatnia Wieczerza', 'obraz', 460, 880, 85, 5);

insert into Galeria(nazwa) values('Galeria1');
insert into Galeria(nazwa) values('Galeria2');
insert into Galeria(nazwa) values('Galeria3');


insert into Sala(nr, pojemnosc, idGaleria) values(1, 20, 1);
insert into Sala(nr, pojemnosc, idGaleria) values(2, 10, 1);
insert into Sala(nr, pojemnosc, idGaleria) values(3, 5, 1);
insert into Sala(nr, pojemnosc, idGaleria) values(1, 10, 2);
insert into Sala(nr, pojemnosc, idGaleria) values(2, 11, 2);
insert into Sala(nr, pojemnosc, idGaleria) values(100, 13, 3);


insert into WystawaObjazdowa(miasto, dataRozpoczecia, dataZakonczenia) values('Warszafka', '2018-05-21', '2018-06-21');
insert into WystawaObjazdowa(miasto, dataRozpoczecia, dataZakonczenia) values('Kraków', '2018-07-01', '2018-07-14');
insert into WystawaObjazdowa(miasto, dataRozpoczecia, dataZakonczenia) values('Wrocław', '2018-06-17', '2018-06-30');
insert into WystawaObjazdowa(miasto, dataRozpoczecia, dataZakonczenia) values('Kraków', '2018-07-07', '2018-07-31');


insert into Ekspozycja(idEksponat, idGaleria, nrSala, idWystawaObjazdowa, dataRozpoczecia, dataZakonczenia) values(2, 1, 3, null, '2018-06-14', '2018-07-23');
insert into Ekspozycja(idEksponat, idGaleria, nrSala, idWystawaObjazdowa, dataRozpoczecia, dataZakonczenia) values(5, 1, 1, null, '2018-06-16', '2018-07-23');
insert into Ekspozycja(idEksponat, idGaleria, nrSala, idWystawaObjazdowa, dataRozpoczecia, dataZakonczenia) values(6, null, null, 3, '2018-06-13', '2018-07-01');


--insert into Artysta(id, imie, nazwisko, rokUrodzenia, rokSmierci) values();
--insert into Eksponat(id, tytul, typ, wysokosc, szerokosc, waga, idTworca) values();
--insert into Galeria(id, nazwa) values();
--insert into Sala(nr, pojemnosc, idGaleria) values();
--insert into WystawaObjazdowa(id, miasto, dataRozpoczecia, dataZakonczenia) values();
--insert into Ekspozycja(id, idEksponat, idGaleria, nrSala, idWystawaObjazdowa, dataRozpoczecia, dataZakonczenia) values();

