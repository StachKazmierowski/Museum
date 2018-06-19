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
  constraint checkDates check (rokSmierci is NULL or rokUrodzenia <= rokSmierci));

create table Eksponat (
  id serial primary key,
  tytul varchar(30) not null,
  typ varchar(20) not null,
  wysokosc int not null,
  szerokosc int not null,
  waga int not null,
  idTworca int references Artysta,
  constraint checkDimensions check (wysokosc >= 0 and szerokosc >= 0 and waga > 0));

create table Galeria (
  id serial primary key,
  nazwa varchar(30) unique not null);

create table Sala (
  nr int not null,
  pojemnosc int not null,
  idGaleria int not null references Galeria,
  primary key (nr, idGaleria),
  constraint checkCapacity check (pojemnosc > 0));

create table WystawaObjazdowa (
  id serial primary key,
  miasto varchar(30) not null,
  dataRozpoczecia date not null,
  dataZakonczenia date not null,
  constraint checkDates check (dataRozpoczecia <= dataZakonczenia));

create table Ekspozycja (
  id serial primary key,
  idEksponat int not null references Eksponat,
  idGaleria int,
  nrSala int,
  foreign key (idGaleria, nrSala) references Sala(idGaleria, nr),
  idWystawaObjazdowa int references WystawaObjazdowa,
  dataRozpoczecia date not null,
  dataZakonczenia date not null,
  constraint checkDates check (dataRozpoczecia <= dataZakonczenia),
  constraint checkPlace check ((idGaleria is null and nrSala is null and idWystawaObjazdowa is not null) or (idGaleria is not null and nrSala is not null and idWystawaObjazdowa is null)));



--wyzwalacze

---------------------------------------------
-- eksponat w dwóch miejscach jednocześnie --
---------------------------------------------
create or replace function f1 () returns trigger as $$
    
  begin
    
    if (exists (select * from ekspozycja where id != new.id and idEksponat = new.idEksponat and least(dataZakonczenia, new.dataZakonczenia) >= greatest(dataRozpoczecia, new.dataRozpoczecia)))
    then
      raise exception 'Eksponat nie może być w wielu miejscach jednocześnie.';
    else
      return new;
    end if;

  end;
$$ language plpgsql;


drop trigger if exists t1 on Ekspozycja;
create trigger t1 before insert or update
  on Ekspozycja for each row
  execute procedure f1();


--------------------------------------------
-- ekspozycja wewnątrz wystawy objazdowej --
--------------------------------------------
create or replace function f2 () returns trigger as $$

  begin
  
    if (new.idWystawaObjazdowa is null)
    then
      return new;
    end if;
    
    if (new.dataRozpoczecia < (select dataRozpoczecia from wystawaObjazdowa where id = new.idwystawaObjazdowa))
    then
      raise exception 'Ekspozycja nie może zaczynać się wcześniej, niż wystawa objazdowa.';
    elsif ((select dataZakonczenia from wystawaObjazdowa where id = new.idwystawaObjazdowa) < new.dataZakonczenia)
    then
      raise exception 'Ekspozycja nie może kończyć się później, niż wystawa objazdowa.';
    else
      return new;
    end if;

  end;
$$ language plpgsql;


drop trigger if exists t2 on Ekspozycja;
create trigger t2 before insert or update
  on Ekspozycja for each row
  execute procedure f2();


----------------------------------------
-- dodawanie ekspozycji z przeszłości --
----------------------------------------
create or replace function f3 () returns trigger as $$
    
  begin
    
    if (new.dataZakonczenia < current_date)
    then
      raise exception 'Nie można dodać ekspozycji, która już się odbyła.';
    else
      return new;
    end if;

  end;
$$ language plpgsql;


drop trigger if exists t3 on Ekspozycja;
create trigger t3 before insert or update
  on Ekspozycja for each row
  execute procedure f3();


------------------------------------------------
-- nie więcej eksponatów w sali niż pojemność --
------------------------------------------------
create or replace function f4 () returns trigger as $$
  
  declare
    licznik integer := 0;
    curr record;
    poj integer;
    
  begin
    
    if (new.idWystawaObjazdowa is not null)
    then
      return new;
    end if;
    
    poj := (select pojemnosc from sala where idGaleria = new.idGaleria and nr = new.nrSala);
    
    create temporary table e1 as select * from ekspozycja where id != new.id and idGaleria is not null and idGaleria = new.idGaleria and nrSala = new.nrSala and least(dataZakonczenia, new.dataZakonczenia) >= greatest(dataRozpoczecia, new.dataRozpoczecia);
    
    for curr in select *
      from
        ((select dataRozpoczecia as data, 1 as val from e1)
          union all
        (select dataZakonczenia as data, -1 as val from e1)) e2
      order by data
    loop
      licznik := licznik + curr.val;
      
      if (licznik = poj)
      then
        raise exception 'Liczba eksponatów w sali nie może być większa niż jej pojemność.';
      end if;
    end loop;
    
    drop table e1;
    
    return new;
    
  end;
$$ language plpgsql;


drop trigger if exists t4 on Ekspozycja;
create trigger t4 before insert or update
  on Ekspozycja for each row
  execute procedure f4();


------------------------------------
-- max 30 dni rocznie poza muzeum --
------------------------------------
create or replace function getYear (d date) returns integer as $$
  begin
    return (select extract(year from d));
  end;
$$ language plpgsql;


create or replace function sumExhibition (y integer, idEks integer, idEkspozycja integer) returns integer as $$

  declare
    dni integer := 0;
    gt date;
    lt date;
    
  begin
  
    gt := date (y || '-01-01');
    lt := date (y || '-12-31');
    
    select
      sum(dlugosc) into dni
    from
      (select case when dl < 0 then 0 else dl end as dlugosc
        from
          (select
            least(dataZakonczenia, lt) - greatest(dataRozpoczecia, gt) + 1 as dl --daty włącznie
          from
            Ekspozycja
          where
            idWystawaObjazdowa is not null and idEksponat = idEks and id != idEkspozycja) e1) e2;

    if (dni is null)
    then
      dni := 0;
    end if;
    
    return dni;
    
  end;
$$ language plpgsql;


create or replace function f5 () returns trigger as $$

  declare
    y1 integer;
    y2 integer;
    
  begin
  
    if (new.idWystawaObjazdowa is null)
    then
      return new;
    end if;
    
    y1 := getYear(new.dataRozpoczecia);
    y2 := getYear(new.dataZakonczenia);
    
    if (y1 = y2)
    then
      if (new.dataZakonczenia - new.dataRozpoczecia + 1 > 30) --daty włącznie
      then
        raise exception 'Zbyt długi czas przebywania tego eksponatu poza muzeum.';
      elsif ((select sumExhibition(y1, new.idEksponat, new.id)) + (new.dataZakonczenia - new.dataRozpoczecia) + 1 > 30) --daty włącznie
      then
        raise exception 'Zbyt długi czas przebywania tego eksponatu poza muzeum.';
      else
        return new;
      end if;
    else
      if ((select sumExhibition(y1, new.idEksponat, new.id)) + (date (y1 || '-12-31') - new.dataRozpoczecia) + 1 > 30) --daty włącznie
      then
        raise exception 'Zbyt długi czas przebywania tego eksponatu poza muzeum.';
      end if;
      
      if ((select sumExhibition(y2, new.idEksponat, new.id)) + (new.dataZakonczenia - date (y2 || '-01-01')) + 1 > 30) --daty włącznie
      then
        raise exception 'Zbyt długi czas przebywania tego eksponatu poza muzeum.';
      end if;
      
      return new;
    end if;

  end;
$$ language plpgsql;


drop trigger if exists t5 on Ekspozycja;
create trigger t5 before insert or update
  on Ekspozycja for each row
  execute procedure f5();


---------------------------------------------------------
-- co najmniej jeden eksponat każdego artysty w muzeum --
---------------------------------------------------------
create or replace function f6 () returns trigger as $$
  
  declare
    licznik integer := 0;
    curr record;
    liczbaEksponatow integer;
    idArtysta integer;
    
  begin
    
    if (new.idWystawaObjazdowa is null or (select idTworca from eksponat where id = new.idEksponat) is null)
    then
      return new;
    end if;
    
    idArtysta := (select idTworca from eksponat where id = new.idEksponat);
    
    liczbaEksponatow := (select count(*) from eksponat where idTworca is not null and idTworca = idArtysta);
    
    if (liczbaEksponatow <= 1)
    then
      raise exception 'Muzeum powinno zawsze mieć w swoich galeriach<br>lub w magazynie co najmniej jeden eksponat każdego artysty.';
    end if;
    
    create temporary table e1 as select * from ekspozycja where id != new.id and idWystawaObjazdowa is not null and least(dataZakonczenia, new.dataZakonczenia) >= greatest(dataRozpoczecia, new.dataRozpoczecia) and (select idTworca from eksponat where id = idEksponat) = idArtysta;
    
    for curr in select *
      from
        ((select dataRozpoczecia as data, 1 as val from e1)
          union all
        (select dataZakonczenia as data, -1 as val from e1)) e2
      order by data
    loop
      licznik := licznik + curr.val;
      
      if (licznik = liczbaEksponatow - 1)
      then
        raise exception 'Muzeum powinno zawsze mieć w swoich galeriach<br>lub w magazynie co najmniej jeden eksponat każdego artysty.';
      end if;
    end loop;
    
    drop table e1;
    
    return new;
    
  end;
$$ language plpgsql;


drop trigger if exists t6 on Ekspozycja;
create trigger t6 before insert or update
  on Ekspozycja for each row
  execute procedure f6();


