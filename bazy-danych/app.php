<?php

$mypassword = ""; # hidden ;)

$stylesheet = "app.css";
$script = "script.js";
$title = "Aplikacja dla gościa";

switch ($_GET["table"]) {



### Eksponaty
  case "exhibits":
    $header = "Eksponaty";
    include "./header.php";
    echo "    <div class=\"header\">\n      $header\n    </div>\n\n";
    
    $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
    
    switch ($_GET["id"]) {


### Eksponaty ## index
      case "":
        $result = pg_query_params($link, "select *, eksponat.id as ide, artysta.id as ida from eksponat left outer join artysta on eksponat.idtworca = artysta.id order by artysta.nazwisko", array());
        $num = pg_numrows($result);
        
        $tableId = "t_exhibits";
        echo "    <table id=\"$tableId\">\n";
        echo "      <tr>\n";
        echo "        <th onclick=\"sortTableAlphabetically('$tableId', 0)\" class=\"t_th_pointer\">tytuł</th>\n";
        echo "        <th onclick=\"sortTableAlphabetically('$tableId', 1)\" class=\"t_th_pointer\">autor</th>\n";
        echo "        <th onclick=\"sortTableAlphabetically('$tableId', 2)\" class=\"t_th_pointer\">typ</th>\n";
        echo "      </tr>\n";
        
        for ($i = 0; $i < $num; $i++) {
          $row = pg_fetch_array($result, $i);
          # var_dump($row);
          
          $ide = $row["ide"];
          $ida = $row["ida"];
          echo "      <tr>\n";
          echo "        <td onclick=\"javascript:location.href='?table=exhibits&id=$ide'\" class=\"t_td_pointer\"><i>" . $row["tytul"] . "</i></td>\n";
          
          $onclick = ($ida == "") ? ">" : " onclick=\"javascript:location.href='?table=artists&id=$ida'\" class=\"t_td_pointer\">";
          $str = ($ida == "") ? "-" : $row["imie"] . " " . $row["nazwisko"];
          echo "        <td" . $onclick . $str . "</td>\n";

          echo "        <td>" . $row["typ"] . "</td>\n";
          echo "      </tr>\n";
        }
        
        echo "    </table>\n";
        $back = "./app.php";
        
        break;


### Eksponaty ## id
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
          
          $onclick = ($ida == "") ? ">" : " onclick=\"javascript:location.href='?table=artists&id=$ida'\" class=\"t_td_pointer\">";
          $str = ($ida == "") ? "-" : $row["imie"] . " " . $row["nazwisko"];
          echo "        <td" . $onclick . $str . "</td>\n";
          
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
          
          echo "    <br><br>\n\n";

          $result = pg_query_params($link, "select *, galeria.id as idg from ((ekspozycja join eksponat on ekspozycja.ideksponat = eksponat.id) left join (sala left join galeria on sala.idgaleria = galeria.id) on ekspozycja.nrsala = sala.nr and ekspozycja.idgaleria = sala.idgaleria) left join wystawaobjazdowa on ekspozycja.idwystawaobjazdowa = wystawaobjazdowa.id where ekspozycja.ideksponat = $1 and ekspozycja.datarozpoczecia <= current_date and current_date <= ekspozycja.datazakonczenia", array($_GET["id"]));
          $num = pg_numrows($result);
        
          if ($num == 0) {
            echo "    Obecnie nie ma możliwości obejrzenia tego eksponatu,<br>gdyż znajduje się on w magazynie.\n";
          }
          else {
            echo "    Eksponat ten znajduje się obecnie ";
            
            $row = pg_fetch_array($result, 0);
          
            if ($row["idwystawaobjazdowa"] == "") {
              $idg = $row["idg"];
              $nrsala = $row["nr"];
              echo "w <a class=\"hoverable\" href=\"?table=galleries&id=$idg\">galerii " . $row["nazwa"] . "</a>, w <a class=\"hoverable\" href=\"?table=galleries&id=$idg&room=$nrsala\">sali nr " . $row["nr"] . "</a>.\n";
            }
            else {
              $idw = $row["idwystawaobjazdowa"];
              echo "na <a class=\"hoverable\" href=\"?table=tour&id=$idw\">wystawie objazdowej w mieście " . $row["miasto"] . "</a>.\n";
            }
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




### Artyści
  case "artists":
    $header = "Artyści";
    include "./header.php";
    echo "    <div class=\"header\">\n      $header\n    </div>\n\n";
    
    $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
    
    switch ($_GET["id"]) {


### Artyści ## index
      case "":
        $result = pg_query_params($link, "select * from artysta order by nazwisko", array());
        $num = pg_numrows($result);
        
        $tableId = "t_artists";
        echo "    <table id=\"$tableId\">\n";
        echo "      <tr>\n";
        echo "        <th onclick=\"sortTableAlphabetically('$tableId', 0)\" class=\"t_th_pointer\">imię</th>\n";
        echo "        <th onclick=\"sortTableAlphabetically('$tableId', 1)\" class=\"t_th_pointer\">nazwisko</th>\n";
        echo "        <th onclick=\"sortTableAlphabetically('$tableId', 2)\" class=\"t_th_pointer\">rok urodzenia, śmierci</th>\n";
        echo "      </tr>\n";
        
        for ($i = 0; $i < $num; $i++) {
          $row = pg_fetch_array($result, $i);
          
          $ida = $row["id"];
          echo "      <tr>\n";
          echo "        <td onclick=\"javascript:location.href='?table=artists&id=$ida'\" class=\"t_td_pointer\">" . $row["imie"] . "</td>\n";
          echo "        <td onclick=\"javascript:location.href='?table=artists&id=$ida'\" class=\"t_td_pointer\">" . $row["nazwisko"] . "</td>\n";
          $str = $row["roksmierci"] == "" ? "-" : $row["roksmierci"];
          echo "        <td>" . $row["rokurodzenia"] . ", " . $str . "</td>\n";
          echo "      </tr>\n";
        }
        
        echo "    </table>\n";
        $back = "./app.php";
        
        break;


### Artyści ## id
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
          echo "        <td>" . $row["imie"] . " " . $row["nazwisko"] . "</td>\n";
          echo "      </tr>\n";
          echo "      <tr>\n";
          echo "        <th>rok urodzenia, śmierci</th>\n";
          $str = $row["roksmierci"] == "" ? "-" : $row["roksmierci"];
          echo "        <td>" . $row["rokurodzenia"] . ", " . $str . "</td>\n";
          echo "      </tr>\n";
          
          echo "    </table>\n";
          
          echo "    <br><br>\n";
          echo "    Eksponaty tego artysty w naszym muzeum:<br><br>\n";
          
          if ($row["tytul"] == "") {
            echo "    brak<br>\n";
          }
          else {
            $tableId = "t_artists_id_exhibits";
            echo "    <table id=\"$tableId\">\n";
            echo "      <tr>\n";
            echo "        <th onclick=\"sortTableAlphabetically('$tableId', 0)\" class=\"t_th_pointer\">tytuł</th>\n";
            echo "        <th onclick=\"sortTableAlphabetically('$tableId', 1)\" class=\"t_th_pointer\">typ</th>\n";
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


### Galerie ## index
      case "":
        $result = pg_query_params($link, "select * from galeria order by nazwa", array());
        $num = pg_numrows($result);
        
        $tableId = "t_galleries";
        echo "    <table id=\"$tableId\">\n";
        echo "      <tr>\n";
        echo "        <th onclick=\"sortTableAlphabetically('$tableId', 0)\" class=\"t_th_pointer\">nazwa</th>\n";
        echo "        <th onclick=\"sortTableAlphabetically('$tableId', 1)\" class=\"t_th_pointer\">sale</th>\n";
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
        $back = "./app.php";
        
        break;


### Galerie ## id
      default:
        
        switch($_GET["room"]) {


### Galerie ## id # index
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
              
              echo "    <br><br>\n";
              echo "    Sale znajdujące się w tej galerii:<br><br>\n";
              
              $rooms = pg_query_params($link, "select * from sala  where idgaleria = $1 order by nr", array($_GET["id"]));
              $num = pg_numrows($rooms);
              
              if ($num == 0) {
                echo "    brak<br>\n";
              }
              else {
                $tableId = "t_galleries_id_rooms";
                echo "    <table id=\"$tableId\">\n";
                echo "      <tr>\n";
                echo "        <th onclick=\"sortTableNumerically('$tableId', 0)\" class=\"t_th_pointer\">nr</th>\n";
                echo "        <th onclick=\"sortTableNumerically('$tableId', 1)\" class=\"t_th_pointer\">pojemność</th>\n";
                echo "      </tr>\n";
                
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
            }
        

            break;


### Galerie ## id # nr
          default:
            $result = pg_query_params($link, "select * from sala natural join galeria where idgaleria = $1 and nr = $2", array($_GET["id"], $_GET["room"]));
            $num = pg_numrows($result);
          
            if ($num == 0) {
              echo "    <font color=\"red\">Błędne id / nr sali.</font>";
            }
            else {
              $row = pg_fetch_array($result, 0);
              
              echo "    <table id=\"t_galleries_id_rooms_nr\">\n";

              echo "      <tr>\n";
              echo "        <th>nazwa</th>\n";
              echo "        <td>" . $row["nazwa"] . "</td>\n";
              echo "      </tr>\n";
              echo "      <tr>\n";
              echo "        <th>nr sali</th>\n";
              echo "        <td>" . $row["nr"] . "</td>\n";
              echo "      </tr>\n";
              echo "      <tr>\n";
              echo "        <th>pojemność</th>\n";
              echo "        <td>" . $row["pojemnosc"] . "</td>\n";
              echo "      </tr>\n";
              
              echo "    </table>\n";
              
              echo "    <br><br>\n";
              echo "    Eksponaty znajdujące się obecnie w tej sali:<br><br>\n";
              
              $result = pg_query_params($link, "select tytul, eksponat.id as ide, artysta.id as ida, imie, nazwisko, typ from (ekspozycja join (eksponat left join artysta on eksponat.idtworca = artysta.id) on ekspozycja.ideksponat = eksponat.id) left join sala on ekspozycja.nrsala = sala.nr and ekspozycja.idgaleria = sala.idgaleria where ekspozycja.idgaleria is not null and ekspozycja.idgaleria = $1 and ekspozycja.nrsala = $2 and datarozpoczecia <= current_date and current_date <= datazakonczenia order by ekspozycja.id", array($_GET["id"], $_GET["room"]));
              $num = pg_numrows($result);
              
              if ($num == 0) {
                echo "    brak<br>\n";
              }
              else {
                $tableId = "t_galleries_id_rooms_nr";
                echo "    <table id=\"$tableId\">\n";
                echo "      <tr>\n";
                echo "        <th onclick=\"sortTableAlphabetically('$tableId', 0)\" class=\"t_th_pointer\">tytuł</th>\n";
                echo "        <th onclick=\"sortTableAlphabetically('$tableId', 1)\" class=\"t_th_pointer\">autor</th>\n";
                echo "        <th onclick=\"sortTableAlphabetically('$tableId', 2)\" class=\"t_th_pointer\">typ</th>\n";
                echo "      </tr>\n";
                
                for ($i = 0; $i < $num; $i++) {
                  $row = pg_fetch_array($result, $i);
                  
                  $ide = $row["ide"];
                  $ida = $row["ida"];
                  echo "      <tr>\n";
                  echo "        <td onclick=\"javascript:location.href='?table=exhibits&id=$ide'\" class=\"t_td_pointer\"><i>" . $row["tytul"] . "</i></td>\n";
                  echo "        <td onclick=\"javascript:location.href='?table=artists&id=$ida'\" class=\"t_td_pointer\">" . $row["imie"] . " " . $row["nazwisko"] . "</td>\n";
                  echo "        <td>" . $row["typ"] . "</td>\n";
                  echo "      </tr>\n";
                }
                
                echo "    </table>\n";
              }
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
    
    switch ($_GET["id"]) {


### Wystawy objazdowe ## index
      case "":
        $result = pg_query_params($link, "select * from wystawaobjazdowa", array());
        $num = pg_numrows($result);
        
        $tableId = "t_tour";
        echo "    <table id=\"$tableId\">\n";
        echo "      <tr>\n";
        echo "        <th onclick=\"sortTableAlphabetically('$tableId', 0)\" class=\"t_th_pointer\">miasto</th>\n";
        echo "        <th onclick=\"sortTableAlphabetically('$tableId', 1)\" class=\"t_th_pointer\">data rozpoczęcia, zakończenia</th>\n";
        echo "      </tr>\n";
        
        for ($i = 0; $i < $num; $i++) {
          $row = pg_fetch_array($result, $i);
          
          $idw = $row["id"];
          echo "      <tr>\n";
          echo "        <td onclick=\"javascript:location.href='?table=tour&id=$idw'\" class=\"t_td_pointer\">" . $row["miasto"] . "</td>\n";
          echo "        <td>" . $row["datarozpoczecia"] . ", " . $row["datazakonczenia"] . "</td>\n";
          echo "      </tr>\n";
        }
        
        echo "    </table>\n";
        $back = "./app.php";
        
        
        break;


### Wystawy objazdowe ## id
      default:
        $result = pg_query_params($link, "select * from wystawaobjazdowa where id = $1", array($_GET["id"]));
        $num = pg_numrows($result);
      
        if ($num == 0) {
          echo "    <font color=\"red\">Błędne id.</font>";
        }
        else {
          $row = pg_fetch_array($result, 0);
          
          echo "    <table id=\"t_tour_id\">\n";

          echo "      <tr>\n";
          echo "        <th>miasto</th>\n";
          echo "        <td>" . $row["miasto"] . "</td>\n";
          echo "      </tr>\n";
          echo "      <tr>\n";
          echo "        <th>data rozpoczęcia</th>\n";
          echo "        <td>" . $row["datarozpoczecia"] . "</td>\n";
          echo "      </tr>\n";
          echo "      <tr>\n";
          echo "        <th>data zakończenia</th>\n";
          echo "        <td>" . $row["datazakonczenia"] . "</td>\n";
          echo "      </tr>\n";
          
          echo "    </table>\n";
          
          echo "    <br><br>\n\n";
          echo "    Eksponaty obecne na tej wystawie:<br><br>\n\n";
          
          $result = pg_query_params($link, "select tytul, eksponat.id as ide, artysta.id as ida, imie, nazwisko, typ, ekspozycja.datarozpoczecia as dr, ekspozycja.datazakonczenia as dz from (ekspozycja join (eksponat left join artysta on eksponat.idtworca = artysta.id) on ekspozycja.ideksponat = eksponat.id) left join wystawaobjazdowa on ekspozycja.idwystawaobjazdowa = wystawaobjazdowa.id where ekspozycja.idwystawaobjazdowa is not null and ekspozycja.idwystawaobjazdowa = $1 order by ekspozycja.id", array($_GET["id"]));
          $num = pg_numrows($result);
          
          if ($num == 0) {
            echo "    brak<br>\n";
          }
          else {
            $tableId = "t_tour_id_exhibits";
            echo "    <table id=\"$tableId\">\n";
            echo "      <tr>\n";
            echo "        <th onclick=\"sortTableAlphabetically('$tableId', 0)\" class=\"t_th_pointer\">tytuł</th>\n";
            echo "        <th onclick=\"sortTableAlphabetically('$tableId', 1)\" class=\"t_th_pointer\">autor</th>\n";
            echo "        <th onclick=\"sortTableAlphabetically('$tableId', 2)\" class=\"t_th_pointer\">typ</th>\n";
            echo "        <th onclick=\"sortTableAlphabetically('$tableId', 3)\" class=\"t_th_pointer\">początek<br>pobytu</th>\n";
            echo "        <th onclick=\"sortTableAlphabetically('$tableId', 4)\" class=\"t_th_pointer\">koniec<br>pobytu</th>\n";
            echo "      </tr>\n";
            
            for ($i = 0; $i < $num; $i++) {
              $row = pg_fetch_array($result, $i);
              
              $ide = $row["ide"];
              $ida = $row["ida"];
              echo "      <tr>\n";
              echo "        <td onclick=\"javascript:location.href='?table=exhibits&id=$ide'\" class=\"t_td_pointer\"><i>" . $row["tytul"] . "</i></td>\n";
              echo "        <td onclick=\"javascript:location.href='?table=artists&id=$ida'\" class=\"t_td_pointer\">" . $row["imie"] . " " . $row["nazwisko"] . "</td>\n";
              echo "        <td>" . $row["typ"] . "</td>\n";
              echo "        <td>" . $row["dr"] . "</td>\n";
              echo "        <td>" . $row["dz"] . "</td>\n";
              echo "      </tr>\n";
            }
            
            echo "    </table>\n";
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



### index
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
#    echo "    <div class=\"empty_relation\"></div>\n\n";
    echo "    <a href=\"?table=artists\">\n      <div class=\"relation\">\n        arty<br>ści\n      </div>\n    </a>\n\n";
#    echo "    <div class=\"empty_relation\"></div>\n\n";
#    echo "    <div class=\"empty_relation\"></div>\n\n";
    echo "    <a href=\"?table=galleries\">\n      <div class=\"relation\">\n        gale<br>rie\n      </div>\n    </a>\n\n";
#    echo "    <div class=\"empty_relation\"></div>\n\n";
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



### wrong url
  default:
    header("Location: ./app.php");
}



if ($_GET["table"] != "" && $_GET["id"] != "") {
  echo "    <div class=\"tiny\">\n";
  echo "      <form action=\"./app.php\" method=post>\n";
  echo "        <input type=\"submit\" name=\"button\" value=\"Strona główna aplikacji\">\n";
  echo "      </form>\n";
  echo "    </div>\n\n";
}


footer:
include "./footer.php";
?>
