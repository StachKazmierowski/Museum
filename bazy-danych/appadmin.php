<?php

$mypassword = ""; # hidden ;)

$stylesheet = "app.css";
$script = "sortTable.js";
$title = "Aplikacja dla pracownika";
#include "./header.php";

$cookie_name = "user";
if (!isset($_COOKIE[$cookie_name])) {
  include "./header.php";
  
  echo "    <font color=\"red\">Nie jesteś zalogowany!</font>\n\n";
  echo "    <form action=\"./login.php\" method=post>\n\n";
  echo "      <input type=\"submit\" name=\"button\" value=\"Zaloguj się\">\n\n";
  echo "    </form>\n\n";

  goto footer;
}



switch ($_GET["table"]) {


### Ekspozycje
  case "exhibitions":
    $header = "Ekspozycje";
    include "./header.php";
    echo "    <div class=\"header\">\n      $header\n    </div>\n\n";
    
    $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
    
    switch ($_GET["id"]) {
    
    ## index
      case "":
        $result = pg_query_params($link, "select *, ekspozycja.id as id from ((ekspozycja join eksponat on ekspozycja.ideksponat = eksponat.id) left join (sala left join galeria on sala.idgaleria = galeria.id) on ekspozycja.nrsala = sala.nr and ekspozycja.idgaleria = sala.idgaleria) left join wystawaobjazdowa on ekspozycja.idwystawaobjazdowa = wystawaobjazdowa.id order by ekspozycja.id desc", array());
        $num = pg_numrows($result);
        
        $tableId = "t_exhibitions";
        echo "    <table id=\"$tableId\">\n";
        echo "      <tr>\n";
        echo "        <th onclick=\"sortTable('$tableId', 0)\" class=\"t_th_pointer\">id</th>\n";
        echo "        <th onclick=\"sortTable('$tableId', 1)\" class=\"t_th_pointer\">eksponat</th>\n";
        echo "        <th onclick=\"sortTable('$tableId', 2)\" class=\"t_th_pointer\">galeria</th>\n";
        echo "        <th onclick=\"sortTable('$tableId', 3)\" class=\"t_th_pointer\">sala</th>\n";
        echo "        <th onclick=\"sortTable('$tableId', 4)\" class=\"t_th_pointer\">wystawa objazdowa</th>\n";
        echo "        <th onclick=\"sortTable('$tableId', 5)\" class=\"t_th_pointer\">data rozpoczęcia,<br>zakończenia</th>\n";
        echo "      </tr>\n";
        
#        var_dump(pg_fetch_array($result, $num - 1));
        for ($i = 0; $i < $num; $i++) {
          $row = pg_fetch_array($result, $i);
          
          
          $id = $row["id"];
          $ide = $row["ideksponat"];
          $idg = $row["idgaleria"];
          $nr = $row["nrsala"];
          $idw = $row["idwystawaobjazdowa"];
          echo "      <tr>\n";
          echo "        <td onclick=\"javascript:location.href='?table=exhibitions&id=$id'\" class=\"t_td_pointer\">" . $id . "</td>\n";
          echo "        <td onclick=\"javascript:location.href='?table=exhibits&id=$ide'\" class=\"t_td_pointer\"><i>" . $row["tytul"] . "</i></td>\n";
          
          $onclick = ($idg != "") ? " onclick=\"javascript:location.href='?table=galleries&id=$idg'\" class=\"t_td_pointer\">" : ">";
          $str = ($row["nazwa"] == "") ? "-" : $row["nazwa"];
          echo "        <td" . $onclick . $str . "</td>\n";
          
          $onclick = ($nr != "") ? " onclick=\"javascript:location.href='?table=galleries&id=$idg&room=$nr'\" class=\"t_td_pointer\">" : ">";
          $str = ($nr == "") ? "-" : $nr;
          echo "        <td" . $onclick . $str . "</td>\n";
          
          $onclick = ($idw != "") ? " onclick=\"javascript:location.href='?table=tour&id=$idw'\" class=\"t_td_pointer\">" : ">";
          $str = ($idw == "") ? "-" : $row["miasto"];
          echo "        <td" . $onclick . $str . "</td>\n";

          $str = ($row["datazakonczenia"] == "") ? "-" : $row["datazakonczenia"];
          echo "        <td>" . $row["datarozpoczecia"] . ",<br>" . $str . "</td>\n";
          echo "      </tr>\n";
        }
        
        echo "    </table>\n";
        $back = "./appadmin.php";
        
        break;

    ## id
      default:
        $result = pg_query_params($link, "select *, ekspozycja.id as id from ((ekspozycja join eksponat on ekspozycja.ideksponat = eksponat.id) left join (sala left join galeria on sala.idgaleria = galeria.id) on ekspozycja.nrsala = sala.nr and ekspozycja.idgaleria = sala.idgaleria) left join wystawaobjazdowa on ekspozycja.idwystawaobjazdowa = wystawaobjazdowa.id where ekspozycja.id = $1", array($_GET["id"]));
        $num = pg_numrows($result);
        
        if ($num == 0) {
          echo "    <font color=\"red\">Błędne id.</font>";
        }
        else {
          $row = pg_fetch_array($result, 0);
          


          $ide = $row["ideksponat"];
          $idg = $row["idgaleria"];
          $nr = $row["nrsala"];
          $idw = $row["idwystawaobjazdowa"];
          
          echo "    <table id=\"t_exhibitions_id\">\n";
          
          echo "      <tr>\n";
          echo "        <th>id</th>\n";
          echo "        <td>" . $row["id"] . "</td>\n";
          echo "      </tr>\n";
          
          echo "      <tr>\n";
          echo "        <th>eksponat</th>\n";
          echo "        <td onclick=\"javascript:location.href='?table=exhibits&id=$ide'\" class=\"t_td_pointer\"><i>" . $row["tytul"] . "</i></td>\n";
          echo "      </tr>\n";
          
          $onclick = ($idg != "") ? " onclick=\"javascript:location.href='?table=galleries&id=$idg'\" class=\"t_td_pointer\">" : ">";
          $str = ($row["nazwa"] == "") ? "-" : $row["nazwa"];
          echo "      <tr>\n";
          echo "        <th>galeria</th>\n";
          echo "        <td" . $onclick . $str . "</td>\n";
          echo "      </tr>\n";
          
          $onclick = ($nr != "") ? " onclick=\"javascript:location.href='?table=galleries&id=$idg&room=$nr'\" class=\"t_td_pointer\">" : ">";
          $str = ($nr == "") ? "-" : $nr;
          echo "      <tr>\n";
          echo "        <th>sala</th>\n";
          echo "        <td" . $onclick . $str . "</td>\n";
          echo "      </tr>\n";
          
          $onclick = ($idw != "") ? " onclick=\"javascript:location.href='?table=tour&id=$idw'\" class=\"t_td_pointer\">" : ">";
          $str = ($idw == "") ? "-" : $row["miasto"];
          echo "      <tr>\n";
          echo "        <th>wystawa objazdowa</th>\n";
          echo "        <td" . $onclick . $str . "</td>\n";
          echo "      </tr>\n";

          echo "      <tr>\n";
          echo "        <th>data rozpoczęcia</th>\n";
          echo "        <td>" . $row["datarozpoczecia"] . "</td>\n";
          echo "      </tr>\n";
          
          $str = ($row["datazakonczenia"] == "") ? "-" : $row["datazakonczenia"];
          echo "      <tr>\n";
          echo "        <th>data zakończenia</th>\n";
          echo "        <td>" . $str . "</td>\n";
          echo "      </tr>\n";
          
          echo "    </table>\n\n";
          
          echo "    <form action=\"#\" method=post>\n";
          echo "      <input type=\"submit\" name=\"button\" value=\"Edytuj\">\n";
          echo "    </form>\n\n";
        }
        
        $back = "javascript:history.back()";
    }
    
    echo "\n\n";
    pg_close($link);
    
    echo "    <form action=\"$back\" method=post>\n";
    echo "      <input type=\"submit\" name=\"button\" value=\"Powrót\">\n";
    echo "    </form>\n\n";
    
    
    break;



### Eksponaty
  case "exhibits":
    $header = "Eksponaty";
    include "./header.php";
    echo "    <div class=\"header\">\n      $header\n    </div>\n\n";
    
    $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
    
    switch ($_GET["id"]) {
    
    ## index
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
        $back = "./appadmin.php";
        
        break;

    ## id
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



### Artyści
  case "artists":
    $header = "Artyści";
    include "./header.php";
    echo "    <div class=\"header\">\n      $header\n    </div>\n\n";
    
    $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
    
    switch ($_GET["id"]) {
    
    ## index
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
        $back = "./appadmin.php";
        
        break;

    ## id
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
            echo "        <td onclick=\"javascript:location.href='?table=exhibits&id=$ide'\" class=\"t_td_pointer\"><i>" . $row["tytul"] . "</i></td>\n";
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


### Galerie
  case "galleries":
    $header = "Galerie";
    include "./header.php";
    echo "    <div class=\"header\">\n      $header\n    </div>\n\n";
    
    $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
    
    switch ($_GET["id"]) {
    
    ## index
      case "":
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
          
          $idg = $row["id"];
          echo "      <tr>\n";
          echo "        <td onclick=\"javascript:location.href='?table=galleries&id=$idg'\" class=\"t_td_pointer\">" . $row["nazwa"] . "</td>\n";
          echo "        <td>";
          
          $rooms = pg_query_params($link, "select nr from sala where idgaleria = $1 order by nr", array($idg));
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
        $back = "./appadmin.php";
        
        break;

    ## id
      default:
        
        switch($_GET["room"]) {
          
        # index
          case "":
            $result = pg_query_params($link, "select * from galeria where id = $1", array($_GET["id"]));
            $num = pg_numrows($result);
          
            if ($num == 0) {
              echo "    <font color=\"red\">Błędne id.</font>";
            }
            else {
              $row = pg_fetch_array($result, 0);
              
              echo "    <table id=\"t_galleries_id\">\n";

              echo "      <tr>\n";
              echo "        <th>nazwa</th>\n";
              echo "        <td>" . $row["nazwa"] . "</td>\n";
              echo "      </tr>\n";
              
              echo "    </table>\n";
              
              echo "    <br><br>";
              echo "    Sale znajdujące się w tej galerii:<br><br>\n";
              
              $tableId = "t_galleries_id_rooms";
              echo "    <table id=\"$tableId\">\n";
              echo "      <tr>\n";
              echo "        <th onclick=\"sortTable('$tableId', 0)\" class=\"t_th_pointer\">nr</th>\n";
              echo "        <th onclick=\"sortTable('$tableId', 1)\" class=\"t_th_pointer\">pojemność</th>\n";
              echo "      </tr>\n";
              
              $rooms = pg_query_params($link, "select * from sala  where idgaleria = $1 order by nr", array($_GET["id"]));
              $num = pg_numrows($rooms);
              
              $row = pg_fetch_array($rooms, 0);
              $idg = $row["idgaleria"];
              for ($i = 0; $i < $num; $i++) {
                $row = pg_fetch_array($rooms, $i);
                
                $nr=$row["nr"];
                echo "      <tr>\n";
                echo "        <td onclick=\"javascript:location.href='?table=galleries&id=$idg&room=$nr'\" class=\"t_td_pointer\">" . $nr . "</td>\n";
                echo "        <td>" . $row["pojemnosc"] . "</td>\n";
                echo "      </tr>\n";
              }
              
              echo "    </table>\n";
            }
        

            break;
        
        # nr
          default:
            $result = pg_query_params($link, "select * from sala where idgaleria = $1 and nr = $2", array($_GET["id"], $_GET["room"]));
            $num = pg_numrows($result);
          
            if ($num == 0) {
              echo "    <font color=\"red\">Błędne id / nr sali.</font>";
            }
            else {
              echo "    Galeria " . $_GET["id"] . ", sala " . $_GET["room"] . ".<br><br>Tu coś będzie (jak mi się będzie chciało lol).\n";
#              $row = pg_fetch_array($result, 0);
#              
#              echo "    <table id=\"t_galleries_id\">\n";

#              echo "      <tr>\n";
#              echo "        <th>nazwa</th>\n";
#              echo "        <td>" . $row["nazwa"] . "</td>\n";
#              echo "      </tr>\n";
#              
#              echo "    </table>\n";
#              
#              echo "    <br><br>";
#              echo "    Sale znajdujące się w tej galerii:<br><br>\n";
#              
#              $tableId = "t_galleries_id_rooms";
#              echo "    <table id=\"$tableId\">\n";
#              echo "      <tr>\n";
#              echo "        <th onclick=\"sortTable('$tableId', 0)\" class=\"t_th_pointer\">nr</th>\n";
#              echo "        <th onclick=\"sortTable('$tableId', 1)\" class=\"t_th_pointer\">pojemność</th>\n";
#              echo "      </tr>\n";
#              
#              $rooms = pg_query_params($link, "select * from sala  where idgaleria = $1 order by nr", array($_GET["id"]));
#              $num = pg_numrows($rooms);
#              
#              $row = pg_fetch_array($rooms, 0);
#              $idg = $row["idgaleria"];
#              for ($i = 0; $i < $num; $i++) {
#                $row = pg_fetch_array($rooms, $i);
#                
#                $nr=$row["nr"];
#                echo "      <tr>\n";
#                echo "        <td onclick=\"javascript:location.href='?table=galleries&id=$idg&room=$nr'\" class=\"t_td_pointer\">" . $nr . "</td>\n";
#                echo "        <td>" . $row["pojemnosc"] . "</td>\n";
#                echo "      </tr>\n";
#              }
#              
#              echo "    </table>\n";
            }
        
        }
      
      
        
        
        $back = "javascript:history.back()";
    }
    echo "\n\n";
    pg_close($link);
    
    
    echo "    <form action=\"$back\" method=post>\n";
    echo "      <input type=\"submit\" name=\"button\" value=\"Powrót\">\n";
    echo "    </form>\n\n";
    
    break;

### Wystawy objazdowe
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
    
    
    echo "    <form action=\"./appadmin.php\" method=post>\n";
    echo "      <input type=\"submit\" name=\"button\" value=\"Powrót\">\n";
    echo "    </form>\n\n";
    
    break;
    
### index
  case "":

    $header = "Witamy w aplikacji muzeum.";
    include "./header.php";
    echo "    <div class=\"header\">\n      $header\n    </div>\n\n";
    
    echo "    <div class=\"tiny\">\n";

    echo "      <form method=post>\n";
    echo "        <input type=\"submit\" name=\"button\" formaction=\"http://students.mimuw.edu.pl/~kd370826/bazy-danych/\" value=\"Strona główna\">\n";
    echo "        <br>Jesteś zalogowany jako: " . $_COOKIE[$cookie_name] . ".\n";
    echo "        <input type=\"submit\" name=\"button\" formaction=\"./logout.php\" value=\"Wyloguj się\">\n";
    echo "      </form>\n";

    echo "    </div>\n\n";
    echo "    <div style=\"margin-top: 20px;\"></div>\n\n";

    
    echo "    <a href=\"?table=exhibitions\">\n      <div class=\"relation_admin\">\n        wyświetl<br>ekspozycje\n      </div>\n    </a>\n\n";
    echo "    <a href=\"?table=exhibits\">\n      <div class=\"relation_admin\">\n        wyświetl<br>eksponaty\n      </div>\n    </a>\n\n";
    echo "    <a href=\"?table=artists\">\n      <div class=\"relation_admin\">\n        wyświetl<br>artystów\n      </div>\n    </a>\n\n";
    echo "    <a href=\"?table=galleries\">\n      <div class=\"relation_admin\">\n        wyświetl<br>galerie\n      </div>\n    </a>\n\n";
    echo "    <a href=\"?table=tour\">\n      <div class=\"relation_admin\">\n        wyświetl<br>objazdy\n      </div>\n    </a>\n\n";
    echo "    <a href=\"?table=#\">\n      <div class=\"relation_admin\">\n        dodaj<br>ekspozycję\n      </div>\n    </a>\n\n";
    echo "    <a href=\"?table=#\">\n      <div class=\"relation_admin\">\n        dodaj<br>eksponat\n      </div>\n    </a>\n\n";
    echo "    <a href=\"?table=#\">\n      <div class=\"relation_admin\">\n        dodaj<br>artystę\n      </div>\n    </a>\n\n";
    echo "    <a href=\"?table=#\">\n      <div class=\"relation_admin\">\n        dodaj<br>salę\n      </div>\n    </a>\n\n";
    echo "    <a href=\"?table=#\">\n      <div class=\"relation_admin\">\n        dodaj<br>galerię\n      </div>\n    </a>\n\n";
    echo "    <a href=\"?table=#\">\n      <div class=\"relation_admin\">\n        dodaj<br>objazd\n      </div>\n    </a>\n\n";
    
    
    echo "    <div style=\"margin-top: 80px;\"></div>\n\n";
    
    break;

### wrong url
  default:
    header("Location: ./appadmin.php");
}

if ($_GET["table"] != "" && $_GET["id"] != "") {
  echo "    <div class=\"tiny\">\n";
  echo "      <form action=\"./appadmin.php\" method=post>\n";
  echo "        <input type=\"submit\" name=\"button\" value=\"Strona główna aplikacji\">\n";
  echo "      </form>\n";
  echo "    </div>\n\n";
}





footer:
include "./footer.php";
?>
