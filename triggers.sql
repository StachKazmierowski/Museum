--wyzwalacze

--create or replace function firstOfJanuary (d date) returns date as $$
--  begin
--    return (select cast(date_trunc('year', d) as date));
--  end;
--$$ language plpgsql;

--create or replace function lastOfDecember (d date) returns date as $$
--  begin
--    return (select firstOfJanuary(d) - 1 + interval '1 year');
--  end;
--$$ language plpgsql;


create or replace function getYear (d date) returns integer as $$
  begin
    return (select extract(year from d));
  end;
$$ language plpgsql;


create or replace function sumExhibition (y integer, idEks integer) returns integer as $$

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
            idWystawaObjazdowa is not null and idEksponat = idEks) e1) e2;

    if (dni is null)
    then
      dni := 0;
    end if;
    
    return dni;
    
  end;
$$ language plpgsql;


-- max 30 dni rocznie poza muzeum
create or replace function f1 () returns trigger as $$

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
      elsif ((select sumExhibition(y1, new.idEksponat)) + (new.dataZakonczenia - new.dataRozpoczecia) + 1 > 30) --daty włącznie
      then
        raise exception 'Zbyt długi czas przebywania tego eksponatu poza muzeum.';
      else
        return new;
      end if;
    else
      if ((select sumExhibition(y1, new.idEksponat)) + (date (y1 || '-12-31') - new.dataRozpoczecia) + 1 > 30) --daty włącznie
      then
        raise exception 'Zbyt długi czas przebywania tego eksponatu poza muzeum.';
      end if;
      
      if ((select sumExhibition(y2, new.idEksponat)) + (new.dataZakonczenia - date (y2 || '-01-01')) + 1 > 30) --daty włącznie
      then
        raise exception 'Zbyt długi czas przebywania tego eksponatu poza muzeum.';
      end if;
      
      return new;
    end if;

  end;
$$ language plpgsql;


drop trigger if exists t1 on Ekspozycja;
create trigger t1 before insert or update
  on Ekspozycja for each row
  execute procedure f1();



-- ekspozycja wewnątrz wystawy objazdowej
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



-- eksponat w dwóch miejscach jednocześnie
create or replace function f3 () returns trigger as $$
    
  begin
    
    if (exists (select * from ekspozycja where least(dataZakonczenia, new.dataZakonczenia) >= greatest(dataRozpoczecia, new.dataRozpoczecia)))
    then
      raise exception 'Eksponat nie może być w wielu miejscach jednocześnie.';
    else
      return new;
    end if;

  end;
$$ language plpgsql;


drop trigger if exists t3 on Ekspozycja;
create trigger t3 before insert or update
  on Ekspozycja for each row
  execute procedure f3();
  
  
  
