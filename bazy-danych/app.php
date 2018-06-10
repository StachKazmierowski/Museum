<?php

$mypassword = ""; # hidden ;)

$stylesheet = "app.css";
$title = "Aplikacja dla gościa";

switch ($_GET["relation"]) {

# Eksponaty
  case "exhibits":
    $header = "Eksponaty";
    include "./header.php";
    echo "    <div class=\"header\">\n      $header\n    </div>\n\n";
    
    $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
    $result = pg_query($link, "select * from eksponat left outer join artysta on eksponat.idtworca = artysta.id order by artysta.nazwisko");
    $num = pg_numrows($result);
    
    echo "    <table class=\"t_exhibits\">\n";
    echo "      <tr>\n";
    echo "        <th>tytuł</th>\n";
    echo "        <th>autor</th>\n";
    echo "        <th>typ</th>\n";
    echo "        <th>wysokość, szerokość (cm),<br>waga (kg)</th>\n";
    echo "      </tr>\n";
    
    for ($i = 0; $i < $num; $i++) {
      $row = pg_fetch_array($result, $i);
#      var_dump($row);
      
      echo "      <tr>\n";
      echo "        <td><i>" . $row["tytul"] . "</i></td>\n";
      echo "        <td>" . $row["imie"] . " " . $row["nazwisko"] . "</td>\n";
      echo "        <td>" . $row["typ"] . "</td>\n";
      echo "        <td>" . $row["wysokosc"] . ", " . $row["szerokosc"] . ", " . $row["waga"] . "</td>\n";
      echo "      </tr>\n";
    }
    
    echo "    </table>\n";
    
    echo "\n\n";
    pg_close($link);
    
    echo "    <form action=\"app.php\" method=post>\n";
    echo "      <input type=\"submit\" name=\"button\" value=\"Powrót\">\n";
    echo "    </form>\n\n";
    
    
    break;

# Artyści
  case "artists":
    $header = "Artyści";
    include "./header.php";
    echo "    <div class=\"header\">\n      $header\n    </div>\n\n";
    
    $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
    $result = pg_query($link, "select * from artysta order by nazwisko");
    $num = pg_numrows($result);
    
    echo "    <table class=\"t_artists\">\n";
    echo "      <tr>\n";
    echo "        <th>imię</th>\n";
    echo "        <th>nazwisko</th>\n";
    echo "        <th>rok urodzenia, śmierci</th>\n";
    echo "      </tr>\n";
    
    for ($i = 0; $i < $num; $i++) {
      $row = pg_fetch_array($result, $i);
      
      echo "      <tr>\n";
      echo "        <td>" . $row["imie"] . "</td>\n";
      echo "        <td>" . $row["nazwisko"] . "</td>\n";
      echo "        <td>" . $row["rokurodzenia"] . ", " . $row["roksmierci"] . "</td>\n";
      echo "      </tr>\n";
    }
    
    echo "    </table>\n";
    
    echo "\n\n";
    pg_close($link);
    
    
    echo "    <form action=\"app.php\" method=post>\n";
    echo "      <input type=\"submit\" name=\"button\" value=\"Powrót\">\n";
    echo "    </form>\n\n";
    
    break;

# Galerie
  case "galleries":
    $header = "Galerie";
    include "./header.php";
    echo "    <div class=\"header\">\n      $header\n    </div>\n\n";
    
    $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
    $result = pg_query($link, "select * from galeria order by nazwa");
    $num = pg_numrows($result);
    
    echo "    <table class=\"t_galleries\">\n";
    echo "      <tr>\n";
    echo "        <th>nazwa</th>\n";
    echo "        <th>sale</th>\n";
    echo "      </tr>\n";
    
    for ($i = 0; $i < $num; $i++) {
      $row = pg_fetch_array($result, $i);
      
      echo "      <tr>\n";
      echo "        <td>" . $row["nazwa"] . "</td>\n";
      echo "        <td>";
      
      $rooms = pg_query($link, "select nr from sala where idgaleria = " . $row["id"] . " order by nr");
      for ($j = 0; $j < pg_numrows($rooms) - 1; $j++) {
        $room = pg_fetch_array($rooms, $j);
        echo $room["nr"] . ", ";
      }
      $room = pg_fetch_array($rooms, pg_numrows($rooms) - 1);
      echo $room["nr"];
      
      echo "</td>\n";
      echo "      </tr>\n";
    }
    
    echo "    </table>\n";
    
    echo "\n\n";
    pg_close($link);
    
    
    echo "    <form action=\"app.php\" method=post>\n";
    echo "      <input type=\"submit\" name=\"button\" value=\"Powrót\">\n";
    echo "    </form>\n\n";
    
    break;

# Wystawy objazdowe
  case "tour":
    $header = "Wystawy objazdowe";
    include "./header.php";
    echo "    <div class=\"header\">\n      $header\n    </div>\n\n";
    
    $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
    $result = pg_query($link, "select * from wystawaobjazdowa");
    $num = pg_numrows($result);
    
    echo "    <table class=\"t_tour\">\n";
    echo "      <tr>\n";
    echo "        <th>miasto</th>\n";
    echo "        <th>data rozpoczęcia, zakończenia</th>\n";
    echo "      </tr>\n";
    
    for ($i = 0; $i < $num; $i++) {
      $row = pg_fetch_array($result, $i);
      
      echo "      <tr>\n";
      echo "        <td>" . $row["miasto"] . "</td>\n";
      echo "        <td>" . "###" . ", " . "###" . "</td>\n";
      echo "      </tr>\n";
    }
    
    echo "    </table>\n";
    
    echo "\n\n";
    pg_close($link);
    
    
    echo "    <form action=\"app.php\" method=post>\n";
    echo "      <input type=\"submit\" name=\"button\" value=\"Powrót\">\n";
    echo "    </form>\n\n";
    
    break;
    
#index
  case "":

    $header = "Witamy w aplikacji muzeum.";
    include "./header.php";
    echo "    <div class=\"header\">\n      $header\n    </div>\n\n";
    
    echo "    <div class=\"tiny\">\n";

    echo "      <form action=\"http://students.mimuw.edu.pl/~kd370826/bazy-danych/\" method=post>\n";
    echo "        <input type=\"submit\" name=\"button\" value=\"Strona główna\">\n";
    echo "      </form>\n";

    echo "    </div>\n\n";

    echo "    <a href=\"?relation=exhibits\">\n      <div class=\"relation\">\n        ekspo<br>naty\n      </div>\n    </a>\n\n";
    echo "    <a href=\"?relation=artists\">\n      <div class=\"relation\">\n        arty<br>ści\n      </div>\n    </a>\n\n";
    echo "    <a href=\"?relation=galleries\">\n      <div class=\"relation\">\n        gale<br>rie\n      </div>\n    </a>\n\n";
    echo "    <a href=\"?relation=tour\">\n      <div class=\"relation\">\n        obja<br>zdy\n      </div>\n    </a>\n\n";
    
    echo "    <div class=\"tiny\">\n";

    $cookie_name = "user";
    if (isset($_COOKIE[$cookie_name])) {
      echo "      Jesteś zalogowany jako: " . $_COOKIE[$cookie_name] . "\n";
      echo "      <form action=\"appadmin.php\" method=post>\n";
      echo "        <input type=\"submit\" name=\"button\" value=\"Wersja dla pracownika\">\n";
      echo "      </form>\n";
    }
    else {
      echo "      <form action=\"login.php\" method=post>\n";
      echo "        <input type=\"submit\" name=\"button\" value=\"Jestem pracownikiem\">\n";
      echo "      </form>\n";
    }

    echo "    </div>\n";
    
    break;

#wrong url
  default:
    header("Location: ./app.php");
}




footer:
include "./footer.php";
?>
