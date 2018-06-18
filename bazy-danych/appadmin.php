<?php

$mypassword = ""; # hidden ;)

$stylesheet = "app.css";
$script = "script.js";
$title = "Aplikacja dla pracownika";

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
    if ($_GET["mode"] != "" && $_GET["mode"] != "add") {
      header("Location: ./appadmin.php?table=exhibitions");
    }

    $header = "Ekspozycje";
    include "./header.php";
    echo "    <div class=\"header\">\n      $header\n    </div>\n\n";
    
    $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
    
    
### Ekspozycje ## add
    if ($_GET["mode"] == "add") {
      echo "    tryb dodawania<br><br>\n\n";
      
      echo "    <div style=\"margin-right: 220px; text-align: right;\">\n\n";
      
      echo "      <form action=\"./result.php\" method=post id=\"add_exhibition\">\n";
      
      # eksponat
      $result = pg_query_params($link, "select *, eksponat.id as ide from eksponat left outer join artysta on eksponat.idtworca = artysta.id order by tytul", array());
      $num = pg_numrows($result);
      
      echo "        eksponat: <select name=\"eksponat\" form=\"add_exhibition\" required>\n";
      
      echo "          <option disabled selected value>wybierz eksponat</option>\n";
      for ($i = 0; $i < $num; $i++) {
        $row = pg_fetch_array($result, $i);
        $ide = $row["ide"];
        $autor = $row["imie"] . " " . $row["nazwisko"];
        $autor = ($autor == " ") ? "" : " (" . $autor . ")";
        
        echo "          <option value=\"$ide\">" . $row["tytul"] . $autor . "</option>\n";
      }
      echo "        </select><br><br>\n\n";
      
      echo "        <div style=\"margin-right: 60px;\">\n";
      echo "          wystawa w galerii <input type=\"radio\" name=\"galeriaobjazd\" onclick=\"disable('wo', 'wo_div', this); enable('gs', 'gs_div', this);\" checked><br>\n";
      
      echo "          wystawa objazdowa <input type=\"radio\" name=\"galeriaobjazd\" onclick=\"disable('gs', 'gs_div', this); enable('wo', 'wo_div', this);\"><br>\n";
      echo "        </div>\n\n";
      
      
      # galeria, sala
      $result = pg_query_params($link, "select * from galeria join sala on galeria.id = sala.idgaleria order by nazwa, nr", array());
      $num = pg_numrows($result);
      
      echo "        <div id=\"gs_div\">galeria, sala: <select name=\"galeria|sala\" id=\"gs\" form=\"add_exhibition\" required>\n";
      
      echo "          <option disabled selected value>wybierz galerię i salę</option>\n";
      for ($i = 0; $i < $num; $i++) {
        $row = pg_fetch_array($result, $i);
        $idg = $row["idgaleria"];
        $nr = $row["nr"];
        $value = $idg . "|" . $nr;
        
        echo "          <option value=\"$value\">" . $row["nazwa"] . ", sala nr " . $nr . "</option>\n";
      }
      echo "        </select></div>\n\n";
      
      
      # wystawa objazdowa
      $result = pg_query_params($link, "select * from wystawaobjazdowa where datazakonczenia >= current_date order by datarozpoczecia, miasto", array());
      $num = pg_numrows($result);
      
      echo "        <div id=\"wo_div\" style=\"display: none;\">wystawa objazdowa: <select name=\"wystawaobjazdowa\" id=\"wo\" form=\"add_exhibition\" required disabled>\n";
      
      echo "          <option disabled selected value>wybierz objazd</option>\n";
      for ($i = 0; $i < $num; $i++) {
        $row = pg_fetch_array($result, $i);
        $idw = $row["id"];
        
        echo "          <option value=\"$idw\">" . $row["miasto"] . ": " . $row["datarozpoczecia"] . ", " . $row["datazakonczenia"] . "</option>\n";
      }
      echo "        </select></div>\n\n";
      
      
      # daty
      echo "        <br>data rozpoczęcia: <input type=\"date\" name=\"datarozpoczecia\" required><br>\n";
      echo "        data zakończenia: <input type=\"date\" name=\"datazakonczenia\" required><br>\n";
      
      
      echo "        <input type=\"submit\" name=\"button\" value=\"Dodaj\">\n\n";
      echo "        <input type=\"hidden\" name=\"table\" value=\"exhibitions\">\n";
      echo "      </form>\n\n";
      
      echo "      <form action=\"./appadmin.php\" method=post>\n";
      echo "        <input type=\"submit\" name=\"button\" value=\"Anuluj\">\n";
      echo "      </form>\n\n";
      
      echo "    </div>\n\n";
      
      pg_close($link);
      break;
    }
    
    switch ($_GET["id"]) {


### Ekspozycje ## index
      case "":
        echo "    <form action=\"?table=exhibitions&mode=add\" method=post>\n";
        echo "      <input type=\"submit\" name=\"button\" class=\"green\" value=\"Dodaj nową\">\n";
        echo "    </form><br>\n\n";
      
      
        $result = pg_query_params($link, "select *, ekspozycja.id as id, ekspozycja.datarozpoczecia as dr, ekspozycja.datazakonczenia as dz from ((ekspozycja join eksponat on ekspozycja.ideksponat = eksponat.id) left join (sala left join galeria on sala.idgaleria = galeria.id) on ekspozycja.nrsala = sala.nr and ekspozycja.idgaleria = sala.idgaleria) left join wystawaobjazdowa on ekspozycja.idwystawaobjazdowa = wystawaobjazdowa.id order by ekspozycja.id desc", array());
        $num = pg_numrows($result);
        
        $tableId = "t_exhibitions";
        echo "    <table id=\"$tableId\">\n";
        echo "      <tr>\n";
        echo "        <th onclick=\"sortTableNumerically('$tableId', 0)\" class=\"t_th_pointer\">id</th>\n";
        echo "        <th onclick=\"sortTableAlphabetically('$tableId', 1)\" class=\"t_th_pointer\">eksponat</th>\n";
        echo "        <th onclick=\"sortTableAlphabetically('$tableId', 2)\" class=\"t_th_pointer\">galeria</th>\n";
        echo "        <th onclick=\"sortTableNumerically('$tableId', 3)\" class=\"t_th_pointer\">sala</th>\n";
        echo "        <th onclick=\"sortTableAlphabetically('$tableId', 4)\" class=\"t_th_pointer\">wystawa objazdowa</th>\n";
        echo "        <th onclick=\"sortTableAlphabetically('$tableId', 5)\" class=\"t_th_pointer\">data rozpoczęcia,<br>zakończenia</th>\n";
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

          echo "        <td>" . $row["dr"] . ",<br>" . $row["dz"] . "</td>\n";
          echo "      </tr>\n";
        }
        
        echo "    </table>\n";
        $back = "./appadmin.php";
        
        break;


### Ekspozycje ## id
      default:
        $result = pg_query_params($link, "select *, ekspozycja.id as id, ekspozycja.datarozpoczecia as dr, ekspozycja.datazakonczenia as dz from ((ekspozycja join eksponat on ekspozycja.ideksponat = eksponat.id) left join (sala left join galeria on sala.idgaleria = galeria.id) on ekspozycja.nrsala = sala.nr and ekspozycja.idgaleria = sala.idgaleria) left join wystawaobjazdowa on ekspozycja.idwystawaobjazdowa = wystawaobjazdowa.id where ekspozycja.id = $1", array($_GET["id"]));
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
          echo "        <td>" . $row["dr"] . "</td>\n";
          echo "      </tr>\n";
          
          echo "      <tr>\n";
          echo "        <th>data zakończenia</th>\n";
          echo "        <td>" . $row["dz"] . "</td>\n";
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
    if ($_GET["mode"] != "" && $_GET["mode"] != "add") {
      header("Location: ./appadmin.php?table=exhibits");
    }
    
    $header = "Eksponaty";
    include "./header.php";
    echo "    <div class=\"header\">\n      $header\n    </div>\n\n";
    
    $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
    
    
### Eksponaty ## add
    if ($_GET["mode"] == "add") {
      echo "    tryb dodawania<br><br>\n\n";
      
      echo "    <div style=\"margin-right: 220px; text-align: right;\">\n\n";
      
      echo "      <form action=\"./result.php\" method=post id=\"add_exhibit\">\n";
      echo "        tytuł: <input type=\"text\" name=\"tytul\" placeholder=\"tytuł eksponatu\" onblur=\"this.placeholder='tytuł eksponatu'\" onfocus=\"this.placeholder=''\" required><br>\n";
      
      $result = pg_query_params($link, "select * from artysta order by nazwisko", array());
      $num = pg_numrows($result);
      
      echo "        autor: <select name=\"autor\" form=\"add_exhibit\">\n";
      
      echo "          <option disabled selected value>wybierz autora (pomiń, jeśli nieznany)</option>\n";
      for ($i = 0; $i < $num; $i++) {
        $row = pg_fetch_array($result, $i);
        $ida = $row["id"];
        
        echo "          <option value=\"$ida\">" . $row["imie"] . " " . $row["nazwisko"] . "</option>\n";
      }
      echo "        </select><br>\n\n";
      
      echo "        typ: <select name=\"typ\" form=\"add_exhibit\" required>\n";
      
      echo "          <option disabled selected value>wybierz typ eksponatu</option>\n";
      echo "          <option value=\"obraz\">obraz</option>\n";
      echo "          <option value=\"rzeźba\">rzeźba</option>\n";
      echo "          <option value=\"instalacja\">instalacja</option>\n";
      echo "          <option value=\"fotografia\">fotografia</option>\n";
      echo "          <option value=\"numizmatyka\">numizmatyka</option>\n";

      echo "        </select><br>\n\n";
      
      echo "        wysokość: <input type=\"number\" name=\"wysokosc\" placeholder=\"wysokość [cm]\" onblur=\"this.placeholder='wysokość [cm]'\" onfocus=\"this.placeholder=''\" min=\"0\" step=\"1\" required><br>\n";
      echo "        szerokość: <input type=\"number\" name=\"szerokosc\" placeholder=\"szerokość [cm]\" onblur=\"this.placeholder='szerokość [cm]'\" onfocus=\"this.placeholder=''\" min=\"0\" step=\"1\" required><br>\n";
      echo "        waga: <input type=\"number\" name=\"waga\" placeholder=\"waga [kg]\" onblur=\"this.placeholder='waga [kg]'\" onfocus=\"this.placeholder=''\" min=\"1\" step=\"1\" required><br>\n";
      echo "        <input type=\"submit\" name=\"button\" value=\"Dodaj\">\n\n";
      echo "        <input type=\"hidden\" name=\"table\" value=\"exhibits\">\n";
      echo "      </form>\n\n";
      
      echo "      <form action=\"./appadmin.php\" method=post>\n";
      echo "        <input type=\"submit\" name=\"button\" value=\"Anuluj\">\n";
      echo "      </form>\n\n";
      
      echo "    </div>\n\n";
      
      pg_close($link);
      break;
    }
    
    
    switch ($_GET["id"]) {


### Eksponaty ## index
      case "":
        echo "    <form action=\"?table=exhibits&mode=add\" method=post>\n";
        echo "      <input type=\"submit\" name=\"button\" class=\"green\" value=\"Dodaj nowy\">\n";
        echo "    </form><br>\n\n";
        
        
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
        $back = "./appadmin.php";
        
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
    if ($_GET["mode"] != "" && $_GET["mode"] != "add") {
      header("Location: ./appadmin.php?table=artists");
    }
    
    $header = "Artyści";
    include "./header.php";
    echo "    <div class=\"header\">\n      $header\n    </div>\n\n";
    
    
### Artyści ## add
    if ($_GET["mode"] == "add") {
      echo "    tryb dodawania<br><br>\n\n";
      
      echo "    <div style=\"margin-right: 220px; text-align: right;\">\n\n";
      
      echo "      <form action=\"./result.php\" method=post>\n";
      echo "        imię: <input type=\"text\" name=\"imie\" placeholder=\"imię artysty\" onblur=\"this.placeholder='imię artysty'\" onfocus=\"this.placeholder=''\" required><br>\n";
      echo "        nazwisko: <input type=\"text\" name=\"nazwisko\" placeholder=\"nazwisko artysty\" onblur=\"this.placeholder='nazwisko artysty'\" onfocus=\"this.placeholder=''\" required><br>\n";
      echo "        rok urodzenia: <input type=\"number\" name=\"rokurodzenia\" placeholder=\"rok urodzenia artysty\" onblur=\"this.placeholder='rok urodzenia artysty'\" onfocus=\"this.placeholder=''\" min=\"0\" max=\"2018\" value=\"1900\" step=\"1\" required><br>\n";
      echo "        rok śmierci: <input type=\"number\" name=\"roksmierci\" placeholder=\"pomiń, jeśli artysta żyje\" onblur=\"this.placeholder='pomiń, jeśli artysta żyje'\" onfocus=\"this.placeholder=''\" min=\"0\" max=\"2018\" step=\"1\"><br>\n";
      echo "        <input type=\"submit\" name=\"button\" value=\"Dodaj\">\n\n";
      echo "        <input type=\"hidden\" name=\"table\" value=\"artists\">\n";
      echo "      </form>\n\n";
      
      echo "      <form action=\"./appadmin.php\" method=post>\n";
      echo "        <input type=\"submit\" name=\"button\" value=\"Anuluj\">\n";
      echo "      </form>\n\n";
      
      echo "    </div>\n\n";
      
      break;
    }
    
    
    $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
    
    switch ($_GET["id"]) {


### Artyści ## index
      case "":
        echo "    <form action=\"?table=artists&mode=add\" method=post>\n";
        echo "      <input type=\"submit\" name=\"button\" class=\"green\" value=\"Dodaj nowego\">\n";
        echo "    </form><br>\n\n";
        
        
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
        $back = "./appadmin.php";
        
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
          echo "        <td>" . $row["imie"] . " " . $row["nazwisko"] .  "</td>\n";
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
    if ($_GET["mode"] != "" && $_GET["mode"] != "add") {
      header("Location: ./appadmin.php?table=galleries");
    }
    
    $header = "Galerie";
    include "./header.php";
    echo "    <div class=\"header\">\n      $header\n    </div>\n\n";
    

### Galerie ## add
    if ($_GET["mode"] == "add") {
      echo "    tryb dodawania<br><br>\n\n";
      
      echo "    <div style=\"margin-right: 220px; text-align: right;\">\n\n";
      
      echo "      <form action=\"./result.php\" method=post>\n";
      echo "        nazwa: <input type=\"text\" name=\"nazwa\" placeholder=\"unikalna nazwa galerii\" onblur=\"this.placeholder='unikalna nazwa galerii'\" onfocus=\"this.placeholder=''\" required><br>\n";
      echo "        <input type=\"submit\" name=\"button\" value=\"Dodaj\">\n\n";
      echo "        <input type=\"hidden\" name=\"table\" value=\"galleries\">\n";
      echo "      </form>\n\n";
      
      echo "      <form action=\"./appadmin.php\" method=post>\n";
      echo "        <input type=\"submit\" name=\"button\" value=\"Anuluj\">\n";
      echo "      </form>\n\n";
      
      echo "    </div>\n\n";
      
      break;
    }

    
    $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
    
    switch ($_GET["id"]) {


### Galerie ## index
      case "":
        echo "    <form action=\"?table=galleries&mode=add\" method=post>\n";
        echo "      <input type=\"submit\" name=\"button\" class=\"green\" value=\"Dodaj nową\">\n";
        echo "    </form><br>\n\n";
        
        
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
        $back = "./appadmin.php";
        
        break;


### Galerie ## id
      default:
        
        switch($_GET["room"]) {


### Galerie ## id # index
          case "":
            echo "    <form action=\"?table=rooms&mode=add\" method=post>\n";
            $idg = $_GET["id"];
            echo "      <input type=\"hidden\" name=\"idgal\" value=\"$idg\">\n";
            echo "      <input type=\"submit\" name=\"button\" class=\"green\" value=\"Dodaj salę w tej galerii\">\n";
            echo "    </form><br>\n\n";
          
          
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



### Sale
  case "rooms":
    if ($_GET["mode"] != "" && $_GET["mode"] != "add") {
      header("Location: ./appadmin.php");
    }
    
    $header = "Sale";
    include "./header.php";
    echo "    <div class=\"header\">\n      $header\n    </div>\n\n";

    
### Sale ## add
    if ($_GET["mode"] == "add") {
      $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
      
      echo "    tryb dodawania<br><br>\n\n";
      
      echo "    <div style=\"margin-right: 220px; text-align: right;\">\n\n";
      
      echo "      <form action=\"./result.php\" method=post id=\"add_room\">\n";
      echo "        numer: <input type=\"text\" name=\"nr\" placeholder=\"nr sali\" onblur=\"this.placeholder='nr sali'\" onfocus=\"this.placeholder=''\" required><br>\n";
      echo "        pojemność: <input type=\"text\" name=\"pojemnosc\" placeholder=\"pojemność sali\" onblur=\"this.placeholder='pojemność sali'\" onfocus=\"this.placeholder=''\" required><br>\n\n";

      
      $result = pg_query_params($link, "select * from galeria order by nazwa", array());
      $num = pg_numrows($result);
      
      echo "        galeria: <select name=\"galeria\" form=\"add_room\" required>\n";
      
      if (empty($_POST["idgal"])) {
        echo "          <option disabled selected value>wybierz galerię</option>\n";
      }
      for ($i = 0; $i < $num; $i++) {
        $row = pg_fetch_array($result, $i);
        $idg = $row["id"];
        
        $rooms = pg_query_params($link, "select * from sala where idgaleria = $1 order by nr", array($idg));
        $num2 = pg_numrows($rooms);
        
        echo "          <option value=\"$idg\"";
        if ($idg != "" && $idg == $_POST["idgal"]) {
          echo " selected";
        }
        echo ">" . $row["nazwa"];
        if ($num2 > 0) {
          echo " (ma już sale nr: ";
          
          for ($j = 0; $j < $num2 - 1; $j++) {
            $room = pg_fetch_array($rooms, $j);
            
            echo $room["nr"] . ", ";
          }
          $room = pg_fetch_array($rooms, $num2 - 1);
          echo $room["nr"] . ")";
        }
        
        echo "</option>\n";
      }
      
      echo "        </select><br>\n\n";
      
      echo "        <input type=\"submit\" name=\"button\" value=\"Dodaj\">\n\n";
      echo "        <input type=\"hidden\" name=\"table\" value=\"rooms\">\n";
      echo "      </form>\n\n";
      
      echo "      <form action=\"./appadmin.php\" method=post>\n";
      echo "        <input type=\"submit\" name=\"button\" value=\"Anuluj\">\n";
      echo "      </form>\n\n";
      
      echo "    </div>\n\n";
      
      pg_close($link);
    }
    break;



### Wystawy objazdowe
  case "tour":
    if ($_GET["mode"] != "" && $_GET["mode"] != "add") {
      header("Location: ./appadmin.php?table=tour");
    }
    
    $header = "Wystawy objazdowe";
    include "./header.php";
    echo "    <div class=\"header\">\n      $header\n    </div>\n\n";


### Wystawy objazdowe ## add
    if ($_GET["mode"] == "add") {
      echo "    tryb dodawania<br><br>\n\n";
      
      echo "    <div style=\"margin-right: 220px; text-align: right;\">\n\n";
      
      echo "      <form action=\"./result.php\" method=post>\n";
      echo "        miasto: <input type=\"text\" name=\"miasto\" placeholder=\"miasto wystawy\" onblur=\"this.placeholder='miasto wystawy'\" onfocus=\"this.placeholder=''\" required><br>\n";
      $today = date('Y-m-d');
      echo "        data rozpoczęcia: <input type=\"date\" name=\"datarozpoczecia\" value=\"$today\" required><br>\n";
      echo "        data zakończenia: <input type=\"date\" name=\"datazakonczenia\" value=\"$today\" required><br>\n";
      echo "        <input type=\"submit\" name=\"button\" value=\"Dodaj\">\n\n";
      echo "        <input type=\"hidden\" name=\"table\" value=\"tour\">\n";
      echo "      </form>\n\n";
      
      echo "      <form action=\"./appadmin.php\" method=post>\n";
      echo "        <input type=\"submit\" name=\"button\" value=\"Anuluj\">\n";
      echo "      </form>\n\n";
      
      echo "    </div>\n\n";
      
      break;
    }
    
    
    $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
    
    switch ($_GET["id"]) {


### Wystawy objazdowe ## index
      case "":
        echo "    <form action=\"?table=tour&mode=add\" method=post>\n";
        echo "      <input type=\"submit\" name=\"button\" class=\"green\" value=\"Dodaj nową\">\n";
        echo "    </form><br>\n\n";
        
        
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
        $back = "./appadmin.php";
        
        
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
    if ($_GET["mode"] != "" && $_GET["mode"] != "add") {
      header("Location: ./appadmin.php");
    }

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
    echo "    <a href=\"?table=exhibitions&mode=add\">\n      <div class=\"relation_admin\">\n        dodaj<br>ekspozycję\n      </div>\n    </a>\n\n";
    echo "    <a href=\"?table=exhibits&mode=add\">\n      <div class=\"relation_admin\">\n        dodaj<br>eksponat\n      </div>\n    </a>\n\n";
    echo "    <a href=\"?table=artists&mode=add\">\n      <div class=\"relation_admin\">\n        dodaj<br>artystę\n      </div>\n    </a>\n\n";
    echo "    <a href=\"?table=galleries&mode=add\">\n      <div class=\"relation_admin\">\n        dodaj<br>galerię\n      </div>\n    </a>\n\n";
    echo "    <a href=\"?table=rooms&mode=add\">\n      <div class=\"relation_admin\">\n        dodaj<br>salę\n      </div>\n    </a>\n\n";
    echo "    <a href=\"?table=tour&mode=add\">\n      <div class=\"relation_admin\">\n        dodaj<br>objazd\n      </div>\n    </a>\n\n";

    
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
