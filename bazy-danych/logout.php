<?php

$stylesheet = "login.css";
$title = "Logout";

$cookie_name = "user";
if (!isset($_COOKIE[$cookie_name])) {
  include "./header.php";
  
  echo "    <font color=\"red\">Nie jesteś zalogowany!</font>\n\n";
  echo "    <form action=\"javascript:history.back()\" method=post>\n\n";
  echo "      <input type=\"submit\" name=\"button\" value=\"Powrót\">\n\n";
  echo "    </form>\n\n";
  
  goto footer;
}

$login = $_COOKIE[$cookie_name];
unset($_COOKIE[$cookie_name]);
setcookie($cookie_name, null, -1, './');

include "./header.php";
echo "    Wylogowano: $login.\n\n";

echo "    <form action=\"http://students.mimuw.edu.pl/~kd370826/bazy-danych/\" method=post>\n";
echo "      <input type=\"submit\" name=\"button\" value=\"Strona główna\">\n";
echo "    </form>\n\n";

echo "    <form action=\"http://students.mimuw.edu.pl/~kd370826/bazy-danych/app.php\" method=post>\n";
echo "      <input type=\"submit\" name=\"button\" value=\"Aplikacja\">\n";
echo "    </form>\n\n";

footer:
include "./footer.php";
?>
