<?php

$stylesheet = "login.css";

$cookie_name = "user";
if(isset($_COOKIE[$cookie_name])) {
  $title = "Zalogowano";
  $style = " style=\"background-color: #e6faff;\"";
  include "./header.php";

  echo "    Jesteś zalogowany jako: " . $_COOKIE[$cookie_name] . ".\n\n";
  echo "    <form action=\"appadmin.php\" method=post>\n\n";
  echo "      <input type=\"submit\" name=\"button\" value=\"Dalej\">\n\n";
  echo "    </form>\n\n";
  
  goto footer;
}

function goBack($message) {
  echo "    $message\n\n";
  echo "    <form action=\"javascript:history.back()\" method=post>\n\n";
  echo "      <input type=\"submit\" name=\"button\" value=\"Powrót\">\n\n";
  echo "    </form>\n\n";
}

  $login = $_POST["login"];
  $password = $_POST["password"];

  $link = pg_connect("host=labdb dbname=mrbd user=scott password=tiger");
  $result = pg_query($link, "select * from kd370826.users where login = '" . $login . "'");

  $num = pg_numrows($result);

  if ($num == 0) {
    $title = "Nie udało się zalogować";
    $style = " style=\"background-color: #fff3f0;\"";
    include "./header.php";
    goBack("Błędny login.");
    
    pg_close($link);
    goto footer;
  }

  $user = pg_fetch_array($result, 0);
    
  if ($password != $user["password"]) {
    $title = "Nie udało się zalogować";
    $style = " style=\"background-color: #fff3f0;\"";
    include "./header.php";
    goBack("Błędne hasło.");
    
    pg_close($link);
    goto footer;
  }

  # cookie
  $cookie_value = $user["login"];
  setcookie($cookie_name, $cookie_value, time() + (3600), "/"); # 3600 = 1 hour
  
  $title = "Zalogowano";
  $style = " style=\"background-color: #e6ffee;\"";
  include "./header.php";
  
  echo "    Zalogowano jako: " . $user["login"] . ".<br>\n";

  echo "    <form action=\"appadmin.php\" method=post>\n\n";
  echo "      <input type=\"submit\" name=\"button\" value=\"Dalej\">\n\n";
  echo "    </form>\n\n";


  pg_close($link);

footer:
include "./footer.php";
?>
