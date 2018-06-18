<?php

$mypassword = ""; # hidden ;)

$stylesheet = "app.css";
$title = "Wynik wstawienia do bazy";

include "./header.php";

$cookie_name = "user";
if (!isset($_COOKIE[$cookie_name])) {
  echo "    <font color=\"red\">Nie jesteś zalogowany!</font>\n\n";
  echo "    <form action=\"./login.php\" method=post>\n\n";
  echo "      <input type=\"submit\" name=\"button\" value=\"Zaloguj się\">\n\n";
  echo "    </form>\n\n";
  
  echo "    <form action=\"javascript:history.back()\" method=post>\n";
  echo "      <input type=\"submit\" name=\"button\" value=\"Powrót\">\n";
  echo "    </form>\n\n";

  goto footer;
}


echo "    <br>\n";

switch ($_POST["table"]) {



### Eksponaty
  case "exhibitions":
    $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
    
    if (empty($_POST["wystawaobjazdowa"])) {
      $result_explode = explode('|', $_POST["galeria|sala"]);
      
      $result = pg_query_params($link, "insert into ekspozycja(ideksponat, idgaleria, nrsala, datarozpoczecia, datazakonczenia) values($1, $2, $3, $4, $5)", array($_POST["eksponat"], $result_explode[0], $result_explode[1], $_POST["datarozpoczecia"], $_POST["datazakonczenia"]));
    }
    else {
      $result = pg_query_params($link, "insert into ekspozycja(ideksponat, idwystawaobjazdowa, datarozpoczecia, datazakonczenia) values($1, $2, $3, $4)", array($_POST["eksponat"], $_POST["wystawaobjazdowa"], $_POST["datarozpoczecia"], $_POST["datazakonczenia"]));
    }

    if ($result) {
      echo "    <font color=\"green\">OK</font><br><br>\n";
      
      $ide = $_POST["eksponat"];
      $result = pg_query_params($link, "select tytul from eksponat where id = $1", array($ide));
      $row = pg_fetch_array($result, 0);
      echo "    Dodano ekspozycję: eksponat: " . $row["tytul"];
      if (empty($_POST["wystawaobjazdowa"])) {
        $idg = $result_explode[0];
        $result = pg_query_params($link, "select nazwa from galeria where id = $1", array($idg));
        $row = pg_fetch_array($result, 0);
        
        echo ";<br>galeria: " . $row["nazwa"] . ", nr sali: " . $result_explode[1];
      }
      else {
        $idw = $_POST["wystawaobjazdowa"];
        $result = pg_query_params($link, "select miasto from wystawaobjazdowa where id = $1", array($idw));
        $row = pg_fetch_array($result, 0);
        
        echo ";<br>wystawa objazdowa w mieście: " . $row["miasto"];
      }
      echo ";<br>data rozpoczęcia: " . $_POST["datarozpoczecia"] . "; data zakończenia: " . $_POST["datazakonczenia"] . ".\n";
      
      $back = "./appadmin?table=exhibitions";
    }
    else {
      echo "    <font color=\"red\">Nie udało się.</font><br>\n";
      echo pg_last_error($link) . "<br>\n";
      
      $back = "javascript:history.back()";
      $str = "Spróbuj ponownie";
    }
    
    
    pg_close($link);
    
    
    break;



### Eksponaty
  case "exhibits":
    $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
    
    if (!empty($_POST["autor"])) {
      $result = pg_query_params($link, "insert into eksponat(tytul, typ, wysokosc, szerokosc, waga, idtworca) values($1, $2, $3, $4, $5, $6)", array($_POST["tytul"], $_POST["typ"], $_POST["wysokosc"], $_POST["szerokosc"], $_POST["waga"], $_POST["autor"]));
    }
    else {
      $result = pg_query_params($link, "insert into eksponat(tytul, typ, wysokosc, szerokosc, waga) values($1, $2, $3, $4, $5)", array($_POST["tytul"], $_POST["typ"], $_POST["wysokosc"], $_POST["szerokosc"], $_POST["waga"]));
    }

    if ($result) {
      echo "    <font color=\"green\">OK</font><br><br>\n";
      
      echo "    Dodano eksponat: tytuł: " . $_POST["tytul"];
      if (!empty($_POST["autor"])) {
        $ida = $_POST["autor"];
        $autor = pg_query_params($link, "select imie, nazwisko from artysta where id = $1", array($ida));
        $a = pg_fetch_array($autor, 0);
        echo "; autor: " . $a["imie"] . " " . $a["nazwisko"];
      }
      echo "; typ: " . $_POST["typ"] . ";<br>wysokość: " . $_POST["wysokosc"] . ", szerokość: " . $_POST["szerokosc"] . ", waga: " . $_POST["waga"] . ".\n";
      
      $back = "./appadmin?table=exhibits";
    }
    else {
      echo "    <font color=\"red\">Nie udało się.</font><br>\n";
      echo pg_last_error($link) . "<br>\n";
      
      $back = "javascript:history.back()";
      $str = "Spróbuj ponownie";
    }
    
    
    pg_close($link);
    
    
    break;



### Artyści
  case "artists":
    $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
    
    if (!empty($_POST["roksmierci"])) {
      $result = pg_query_params($link, "insert into artysta(imie, nazwisko, rokurodzenia, roksmierci) values($1, $2, $3, $4)", array($_POST["imie"], $_POST["nazwisko"], $_POST["rokurodzenia"], $_POST["roksmierci"]));
    }
    else {
      $result = pg_query_params($link, "insert into artysta(imie, nazwisko, rokurodzenia) values($1, $2, $3)", array($_POST["imie"], $_POST["nazwisko"], $_POST["rokurodzenia"]));
    }

    if ($result) {
      echo "    <font color=\"green\">OK</font><br><br>\n";
      
      $str2 = empty($_POST["roksmierci"]) ? "" : ", " . $_POST["roksmierci"];
      echo "    Dodano artystę: " . $_POST["imie"] . ", " . $_POST["nazwisko"] . ", " . $_POST["rokurodzenia"] . $str2 . ".\n";
      
      $back = "./appadmin?table=artists";
    }
    else {
      echo "    <font color=\"red\">Nie udało się.</font><br>\n";
      echo pg_last_error($link) . "<br>\n";
      
      $back = "javascript:history.back()";
      $str = "Spróbuj ponownie";
    }
    
    
    pg_close($link);
    
    
    break;



### Galerie
  case "galleries":
    $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
    
    $result = pg_query_params($link, "insert into galeria(nazwa) values($1)", array($_POST["nazwa"]));

    if ($result) {
      echo "    <font color=\"green\">OK</font><br><br>\n";
      echo "    Dodano galerię o nazwie: " . $_POST["nazwa"] . ".\n";
      
      $back = "./appadmin?table=galleries";
    }
    else {
      echo "    <font color=\"red\">Nie udało się.</font><br>\n";
      echo pg_last_error($link) . "<br>\n";
      
      $back = "javascript:history.back()";
      $str = "Spróbuj ponownie";
    }
    
    
    pg_close($link);
    
    
    break;



### Sale
  case "rooms":
    $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
    
    $result = pg_query_params($link, "insert into sala(nr, pojemnosc, idgaleria) values($1, $2, $3)", array($_POST["nr"], $_POST["pojemnosc"], $_POST["galeria"]));

    if ($result) {
      $idg = $_POST["galeria"];
      $gallery = pg_query_params($link, "select nazwa from galeria where id = $1", array($idg));
      $gn = pg_fetch_array($gallery, 0);
      $name = $gn["nazwa"];
    
      echo "    <font color=\"green\">OK</font><br><br>\n";
      echo "    Do galerii $name dodano salę nr " . $_POST["nr"] . " o pojemności " . $_POST["pojemnosc"] . ".\n";
      
      $back = "./appadmin?table=galleries&id=$idg";
    }
    else {
      echo "    <font color=\"red\">Nie udało się.</font><br>\n";
      echo pg_last_error($link) . "<br>\n";
      
      $back = "javascript:history.back()";
      $str = "Spróbuj ponownie";
    }
    
    pg_close($link);
    
    
    break;



### Wystawy objazdowe
  case "tour":
    $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
    
    $result = pg_query_params($link, "insert into wystawaobjazdowa(miasto, datarozpoczecia, datazakonczenia) values($1, $2, $3)", array($_POST["miasto"], $_POST["datarozpoczecia"], $_POST["datazakonczenia"]));

    if ($result) {
      echo "    <font color=\"green\">OK</font><br><br>\n";
      echo "    Dodano wystawę objazdową: " . $_POST["miasto"] . ", " . $_POST["datarozpoczecia"] . ", " . $_POST["datazakonczenia"] . ".\n";
      
      $back = "./appadmin?table=tour";
    }
    else {
      echo "    <font color=\"red\">Nie udało się.</font><br>\n";
      echo pg_last_error($link) . "<br>\n";
      
      $back = "javascript:history.back()";
      $str = "Spróbuj ponownie";
    }
    
    
    pg_close($link);
    
    
    break;
    


### Błąd
  default:
    echo "    <font color=\"red\">Błąd: brak danych.</font>\n";
    $back = "javascript:history.back()";
    $str = "Powrót";

}

echo "\n\n";

if ($str == "") {
  $str = "Gotowe";
}
echo "    <form action=\"$back\" method=post>\n";
echo "      <input type=\"submit\" name=\"button\" value=\"$str\">\n";
echo "    </form>\n\n";

echo "    <form action=\"./appadmin.php\" method=post>\n";
echo "      <input type=\"submit\" name=\"button\" value=\"Strona główna aplikacji\">\n";
echo "    </form>\n\n";



footer:
include "./footer.php";
?>
