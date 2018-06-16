<?php

$mypassword = ""; # hidden ;)

$stylesheet = "app.css";
$title = "Wynik wstawienia do bazy";

include "./header.php";

echo "    <br>\n";

switch ($_POST["table"]) {



### Galerie
  case "galleries":
    $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
    
    $result = pg_query_params($link, "insert into galeria(nazwa) values($1)", array($_POST["nazwa"]));

    if ($result) {
      echo "    <font color=\"green\">OK</font><br><br>\n";
      echo "    Dodano galerię o nazwie: " . $_POST["nazwa"] . "\n";
      
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
    echo "    to może coś będzie (jak mi się będzie chciało lol)\n\n";
    $back = "javascript:history.back()";
#    $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
#    
#    $result = pg_query_params($link, "insert into galeria(nazwa) values($1)", array($_POST["nazwa"]));

#    if ($result) {
#      echo "    <font color=\"green\">OK</font><br><br>\n";
#      echo "    Dodano galerię o nazwie: " . $_POST["nazwa"] . "\n";
#      
#      $back = "./appadmin?table=galleries";
#    }
#    else {
#      echo "    <font color=\"red\">Nie udało się.</font><br>\n";
#      echo pg_last_error($link) . "<br>\n";
#      
#      $back = "javascript:history.back()";
#      $str = "Spróbuj ponownie";
#    }
#    
#    
#    pg_close($link);
    
    
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
