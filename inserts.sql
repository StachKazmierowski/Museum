--inserty

insert into Artysta(id, imie, nazwisko, rokUrodzenia, rokSmierci) values(1, 'Jan', 'Matejko', 1838, 1893);
insert into Artysta(id, imie, nazwisko, rokUrodzenia, rokSmierci) values(2, 'Sandro', 'Botticelli', 1445, 1510);
insert into Artysta(id, imie, nazwisko, rokUrodzenia, rokSmierci) values(3, 'Rafael', 'Santi', 1483, 1520);
insert into Artysta(id, imie, nazwisko, rokUrodzenia, rokSmierci) values(4, 'Michał', 'Anioł', 1475, 1564);
insert into Artysta(id, imie, nazwisko, rokUrodzenia, rokSmierci) values(5, 'Leonardo', 'da Vinci', 1452, 1519);

select setval('artysta_id_seq', 7);


insert into Eksponat(id, tytul, typ, wysokosc, szerokosc, waga, idTworca) values(1, 'Stańczyk', 'obraz', 88, 120, 20, 1);
insert into Eksponat(id, tytul, typ, wysokosc, szerokosc, waga, idTworca) values(2, 'Unia lubelska', 'obraz', 298, 512, 70, 1);
insert into Eksponat(id, tytul, typ, wysokosc, szerokosc, waga, idTworca) values(3, 'Dawid', 'rzeźba', 517, 100, 5660, 4);
insert into Eksponat(id, tytul, typ, wysokosc, szerokosc, waga, idTworca) values(4, 'Narodziny Wenus', 'obraz', 173, 279, 45, 2);
insert into Eksponat(id, tytul, typ, wysokosc, szerokosc, waga, idTworca) values(5, 'Dama z gronostajem', 'obraz', 55, 40, 18, 5);
insert into Eksponat(id, tytul, typ, wysokosc, szerokosc, waga, idTworca) values(6, 'Ostatnia Wieczerza', 'obraz', 460, 880, 85, 5);

select setval('eksponat_id_seq', 8);


insert into Galeria(id, nazwa) values(1, 'Galeria1');
insert into Galeria(id, nazwa) values(2, 'Galeria2');
insert into Galeria(id, nazwa) values(3, 'Galeria3');

select setval('galeria_id_seq', 5);


insert into Sala(nr, pojemnosc, idGaleria) values(1, 20, 1);
insert into Sala(nr, pojemnosc, idGaleria) values(2, 10, 1);
insert into Sala(nr, pojemnosc, idGaleria) values(3, 5, 1);
insert into Sala(nr, pojemnosc, idGaleria) values(1, 10, 2);
insert into Sala(nr, pojemnosc, idGaleria) values(2, 11, 2);
insert into Sala(nr, pojemnosc, idGaleria) values(100, 13, 3);


insert into WystawaObjazdowa(id, miasto, dataRozpoczecia, dataZakonczenia) values(1, 'Warszafka', '2018-05-21', '2018-06-21');
insert into WystawaObjazdowa(id, miasto, dataRozpoczecia, dataZakonczenia) values(2, 'Kraków', '2018-07-01', '2018-07-14');
insert into WystawaObjazdowa(id, miasto, dataRozpoczecia, dataZakonczenia) values(3, 'Wrocław', '2018-06-17', '2018-06-30');
insert into WystawaObjazdowa(id, miasto, dataRozpoczecia, dataZakonczenia) values(4, 'Kraków', '2018-07-07', '2018-07-31');

select setval('wystawaobjazdowa_id_seq', 6);


insert into Ekspozycja(id, idEksponat, idGaleria, nrSala, idWystawaObjazdowa, dataRozpoczecia, dataZakonczenia) values(1, 2, 1, 3, null, '2018-06-14', '2018-07-23');
insert into Ekspozycja(id, idEksponat, idGaleria, nrSala, idWystawaObjazdowa, dataRozpoczecia, dataZakonczenia) values(3, 5, 1, 1, null, '2018-06-16', '2018-07-23');
insert into Ekspozycja(id, idEksponat, idGaleria, nrSala, idWystawaObjazdowa, dataRozpoczecia, dataZakonczenia) values(4, 6, null, null, 3, '2018-06-13', '2018-07-01');

select setval('ekspozycja_id_seq', 6);


--insert into Artysta(id, imie, nazwisko, rokUrodzenia, rokSmierci) values();
--insert into Eksponat(id, tytul, typ, wysokosc, szerokosc, waga, idTworca) values();
--insert into Galeria(id, nazwa) values();
--insert into Sala(nr, pojemnosc, idGaleria) values();
--insert into WystawaObjazdowa(id, miasto, dataRozpoczecia, dataZakonczenia) values();
--insert into Ekspozycja(id, idEksponat, idGaleria, nrSala, idWystawaObjazdowa, dataRozpoczecia, dataZakonczenia) values();

