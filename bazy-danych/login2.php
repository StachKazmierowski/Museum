<?php

$stylesheet = "login.css";

$cookie_name = "user";
if(isset($_COOKIE[$cookie_name])) {
  header("refresh: 2; url = ./appadmin.php");
  
  $title = "Zalogowano";
  $style = " style=\"background-color: #e6faff;\"";
  include "./header.php";

  echo "    Jesteś zalogowany jako: " . $_COOKIE[$cookie_name] . ".\n\n";
#  echo "    <form action=\"appadmin.php\" method=post>\n\n";
#  echo "      <input type=\"submit\" name=\"button\" value=\"Dalej\">\n\n";
#  echo "    </form>\n\n";
  
  goto footer;
}

function goBack($message) {
  echo "    $message\n\n";
  echo "    <script>\n";
  echo "      setTimeout(function() { history.back(); }, 2000);\n";
  echo "    </script>\n\n";
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
  
  pg_close($link);
  
  goBack("Błędny login.");
  goto footer;
}

$user = pg_fetch_array($result, 0);
  
if ($password != $user["password"]) {
  
  $title = "Nie udało się zalogować";
  $style = " style=\"background-color: #fff3f0;\"";
  include "./header.php";
  
  pg_close($link);
  
  goBack("Błędne hasło.");
  goto footer;
}

# cookie
$cookie_value = $user["login"];
setcookie($cookie_name, $cookie_value, time() + (1800), "/"); # 1800 = 30 minutes

header("refresh: 2; url = ./appadmin.php");

$title = "Zalogowano";
$style = " style=\"background-color: #e6ffee;\"";
include "./header.php";

echo "    Zalogowano jako: " . $user["login"] . ".<br>\n";

#echo "    <form action=\"appadmin.php\" method=post>\n\n";
#echo "      <input type=\"submit\" name=\"button\" value=\"Dalej\">\n\n";
#echo "    </form>\n\n";

pg_close($link);

footer:
include "./footer.php";
?>
