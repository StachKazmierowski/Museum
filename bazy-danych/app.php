<?php

$mypassword = ""; # hidden ;)

$stylesheet = "app.css";
$script = "sortTable.js";
$title = "Aplikacja dla gościa";

switch ($_GET["table"]) {



## Eksponaty
  case "exhibits":
    $header = "Eksponaty";
    include "./header.php";
    echo "    <div class=\"header\">\n      $header\n    </div>\n\n";
    
    $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
    
    switch ($_GET["id"]) {
    
    # index
      case "":
        $result = pg_query_params($link, "select *, eksponat.id as ide, artysta.id as ida from eksponat left outer join artysta on eksponat.idtworca = artysta.id order by artysta.nazwisko", array());
        $num = pg_numrows($result);
        
        $tableId = "t_exhibits";
        echo "    <table id=\"$tableId\">\n";
        echo "      <tr>\n";
        echo "        <th onclick=\"sortTable('$tableId', 0)\" class=\"t_th_pointer\">tytuł</th>\n";
        echo "        <th onclick=\"sortTable('$tableId', 1)\" class=\"t_th_pointer\">autor</th>\n";
        echo "        <th onclick=\"sortTable('$tableId', 2)\" class=\"t_th_pointer\">typ</th>\n";
        echo "      </tr>\n";
        
        for ($i = 0; $i < $num; $i++) {
          $row = pg_fetch_array($result, $i);
          # var_dump($row);
          
          $ide = $row["ide"];
          $ida = $row["ida"];
          echo "      <tr>\n";
          echo "        <td onclick=\"javascript:location.href='?table=exhibits&id=$ide'\" class=\"t_td_pointer\"><i>" . $row["tytul"] . "</i></td>\n";
          echo "        <td onclick=\"javascript:location.href='?table=artists&id=$ida'\" class=\"t_td_pointer\">" . $row["imie"] . " " . $row["nazwisko"] . "</td>\n";
          echo "        <td>" . $row["typ"] . "</td>\n";
          echo "      </tr>\n";
        }
        
        echo "    </table>\n";
        $back = "./app.php";
        
        break;

    # id
      default:
        $result = pg_query_params($link, "select *, artysta.id as ida from eksponat left outer join artysta on eksponat.idtworca = artysta.id where eksponat.id = $1", array($_GET["id"]));
        $num = pg_numrows($result);
        
        if ($num == 0) {
          echo "    <font color=\"red\">Błędne id.</font>";
        }
        else {
          $row = pg_fetch_array($result, 0);
          
          echo "    <table id=\"t_exhibits_id\">\n";

          echo "      <tr>\n";
          echo "        <th>tytuł</th>\n";
          echo "        <td><i>" . $row["tytul"] . "</i></td>\n";
          echo "      </tr>\n";
          $ida = $row["ida"];
          echo "      <tr>\n";
          echo "        <th>autor</th>\n";
          echo "        <td onclick=\"javascript:location.href='?table=artists&id=$ida'\" class=\"t_td_pointer\">" . $row["imie"] . " " . $row["nazwisko"] . "</td>\n";
          echo "      </tr>\n";
          echo "      <tr>\n";
          echo "        <th>typ</th>\n";
          echo "        <td>" . $row["typ"] . "</td>\n";
          echo "      </tr>\n";
          echo "      <tr>\n";
          echo "        <th>wysokość<br>szerokość<br>waga</th>\n";
          echo "        <td>" . $row["wysokosc"] . "cm<br>" . $row["szerokosc"] . "cm<br>" . $row["waga"] . "kg</td>\n";
          echo "      </tr>\n";
          
          echo "    </table>\n";
        }
        
        $back = "javascript:history.back()";
    }
    
    echo "\n\n";
    pg_close($link);
    
    echo "    <form action=\"$back\" method=post>\n";
    echo "      <input type=\"submit\" name=\"button\" value=\"Powrót\">\n";
    echo "    </form>\n\n";
    
    
    break;



## Artyści
  case "artists":
    $header = "Artyści";
    include "./header.php";
    echo "    <div class=\"header\">\n      $header\n    </div>\n\n";
    
    $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
    
    switch ($_GET["id"]) {
    
    # index
      case "":
        $result = pg_query_params($link, "select * from artysta order by nazwisko", array());
        $num = pg_numrows($result);
        
        $tableId = "t_artists";
        echo "    <table id=\"$tableId\">\n";
        echo "      <tr>\n";
        echo "        <th onclick=\"sortTable('$tableId', 0)\" class=\"t_th_pointer\">imię</th>\n";
        echo "        <th onclick=\"sortTable('$tableId', 1)\" class=\"t_th_pointer\">nazwisko</th>\n";
        echo "        <th onclick=\"sortTable('$tableId', 2)\" class=\"t_th_pointer\">rok urodzenia, śmierci</th>\n";
        echo "      </tr>\n";
        
        for ($i = 0; $i < $num; $i++) {
          $row = pg_fetch_array($result, $i);
          
          $ida = $row["id"];
          echo "      <tr>\n";
          echo "        <td onclick=\"javascript:location.href='?table=artists&id=$ida'\" class=\"t_td_pointer\">" . $row["imie"] . "</td>\n";
          echo "        <td onclick=\"javascript:location.href='?table=artists&id=$ida'\" class=\"t_td_pointer\">" . $row["nazwisko"] . "</td>\n";
          echo "        <td>" . $row["rokurodzenia"] . ", " . $row["roksmierci"] . "</td>\n";
          echo "      </tr>\n";
        }
        
        echo "    </table>\n";
        $back = "./app.php";
        
        break;

    # id
      default:
        $result = pg_query_params($link, "select *, eksponat.id as ide from eksponat right outer join artysta on eksponat.idtworca = artysta.id where artysta.id = $1", array($_GET["id"]));
        $num = pg_numrows($result);
        
        if ($num == 0) {
          echo "    <font color=\"red\">Błędne id.</font>";
        }
        else {
          $row = pg_fetch_array($result, 0);
          
          echo "    <table id=\"t_artists_id\">\n";

          echo "      <tr>\n";
          echo "        <th>imię i nazwisko</th>\n";
          echo "        <td>" . $row["imie"] . " " . $row["nazwisko"] .  "</td>\n";
          echo "      </tr>\n";
          echo "      <tr>\n";
          echo "        <th>rok urodzenia, śmierci</th>\n";
          echo "        <td>" . $row["rokurodzenia"] . ", " . $row["roksmierci"] . "</td>\n";
          echo "      </tr>\n";
          
          echo "    </table>\n";
          
          
          echo "    <br><br>";
          echo "    Eksponaty artysty w naszym muzeum:<br><br>\n";
          
          $tableId = "t_artists_id_exhibits";
          echo "    <table id=\"$tableId\">\n";
          echo "      <tr>\n";
          echo "        <th onclick=\"sortTable('$tableId', 0)\" class=\"t_th_pointer\">tytuł</th>\n";
          echo "        <th onclick=\"sortTable('$tableId', 1)\" class=\"t_th_pointer\">typ</th>\n";
          echo "      </tr>\n";
          
          for ($i = 0; $i < $num; $i++) {
            $row = pg_fetch_array($result, $i);
            
            $ide = $row["ide"];
            echo "      <tr>\n";
            echo "        <td onclick=\"javascript:location.href='?table=exhibits&id=$ide'\" class=\"t_td_pointer\">" . $row["tytul"] . "</td>\n";
            echo "        <td>" . $row["typ"] . "</td>\n";
            echo "      </tr>\n";
          }
          
          echo "    </table>\n";
      
        }
        
        $back = "javascript:history.back()";
    }
    echo "\n\n";
    pg_close($link);
    
    
    echo "    <form action=\"$back\" method=post>\n";
    echo "      <input type=\"submit\" name=\"button\" value=\"Powrót\">\n";
    echo "    </form>\n\n";
    
    break;


## Galerie
  case "galleries":
    $header = "Galerie";
    include "./header.php";
    echo "    <div class=\"header\">\n      $header\n    </div>\n\n";
    
    $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
    $result = pg_query_params($link, "select * from galeria order by nazwa", array());
    $num = pg_numrows($result);
    
    $tableId = "t_galleries";
    echo "    <table id=\"$tableId\">\n";
    echo "      <tr>\n";
    echo "        <th onclick=\"sortTable('$tableId', 0)\" class=\"t_th_pointer\">nazwa</th>\n";
    echo "        <th onclick=\"sortTable('$tableId', 1)\" class=\"t_th_pointer\">sale</th>\n";
    echo "      </tr>\n";
    
    for ($i = 0; $i < $num; $i++) {
      $row = pg_fetch_array($result, $i);
      
      echo "      <tr>\n";
      echo "        <td>" . $row["nazwa"] . "</td>\n";
      echo "        <td>";
      
      $rooms = pg_query_params($link, "select nr from sala where idgaleria = $1 order by nr", array($row["id"]));
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
    
    
    echo "    <form action=\"./app.php\" method=post>\n";
    echo "      <input type=\"submit\" name=\"button\" value=\"Powrót\">\n";
    echo "    </form>\n\n";
    
    break;

## Wystawy objazdowe
  case "tour":
    $header = "Wystawy objazdowe";
    include "./header.php";
    echo "    <div class=\"header\">\n      $header\n    </div>\n\n";
    
    $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
    $result = pg_query_params($link, "select * from wystawaobjazdowa", array());
    $num = pg_numrows($result);
    
    $tableId = "t_tour";
    echo "    <table id=\"$tableId\">\n";
    echo "      <tr>\n";
    echo "        <th onclick=\"sortTable('$tableId', 0)\" class=\"t_th_pointer\">miasto</th>\n";
    echo "        <th onclick=\"sortTable('$tableId', 1)\" class=\"t_th_pointer\">data rozpoczęcia, zakończenia</th>\n";
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
    
    
    echo "    <form action=\"./app.php\" method=post>\n";
    echo "      <input type=\"submit\" name=\"button\" value=\"Powrót\">\n";
    echo "    </form>\n\n";
    
    break;
    
## index
  case "":

    $header = "Witamy w aplikacji muzeum.";
    include "./header.php";
    echo "    <div class=\"header\">\n      $header\n    </div>\n\n";
    
    echo "    <div class=\"tiny\">\n";

    echo "      <form action=\"http://students.mimuw.edu.pl/~kd370826/bazy-danych/\" method=post>\n";
    echo "        <input type=\"submit\" name=\"button\" value=\"Strona główna\">\n";
    echo "      </form>\n";

    echo "    </div>\n\n";
    echo "    <div style=\"margin-top: 20px;\"></div>\n\n";

    
    echo "    <a href=\"?table=exhibits\">\n      <div class=\"relation\">\n        ekspo<br>naty\n      </div>\n    </a>\n\n";
    echo "    <div class=\"empty_relation\"></div>\n\n";
    echo "    <a href=\"?table=artists\">\n      <div class=\"relation\">\n        arty<br>ści\n      </div>\n    </a>\n\n";
    echo "    <div class=\"empty_relation\"></div>\n\n";
    echo "    <div class=\"empty_relation\"></div>\n\n";
    echo "    <a href=\"?table=galleries\">\n      <div class=\"relation\">\n        gale<br>rie\n      </div>\n    </a>\n\n";
    echo "    <div class=\"empty_relation\"></div>\n\n";
    echo "    <a href=\"?table=tour\">\n      <div class=\"relation\">\n        obja<br>zdy\n      </div>\n    </a>\n\n";
    
    
    echo "    <div style=\"margin-top: 80px;\"></div>\n\n";
    echo "    <div class=\"tiny\">\n";

    $cookie_name = "user";
    if (isset($_COOKIE[$cookie_name])) {
      echo "      Jesteś zalogowany jako: " . $_COOKIE[$cookie_name] . "\n";
      echo "      <form action=\"./appadmin.php\" method=post>\n";
      echo "        <input type=\"submit\" name=\"button\" value=\"Wersja dla pracownika\">\n";
      echo "      </form>\n";
    }
    else {
      echo "      <form action=\"./login.php\" method=post>\n";
      echo "        <input type=\"submit\" name=\"button\" value=\"Jestem pracownikiem\">\n";
      echo "      </form>\n";
    }

    echo "    </div>\n";
    
    break;

## wrong url
  default:
    header("Location: ./app.php");
}

if ($_GET["table"] != "") {
  echo "    <div class=\"tiny\">\n";
  echo "      <form action=\"./app.php\" method=post>\n";
  echo "        <input type=\"submit\" name=\"button\" value=\"Strona główna aplikacji\">\n";
  echo "      </form>\n";
  echo "    </div>\n\n";
}


footer:
include "./footer.php";
?>
