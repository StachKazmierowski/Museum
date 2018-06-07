<?php

$stylesheet = "app.css";
$title = "Aplikacja dla pracownika";
include "./header.php";

$cookie_name = "user";
if(!isset($_COOKIE[$cookie_name])) {
  echo "    <font color=\"red\">Nie jesteś zalogowany!</font>\n\n";
  echo "    <form action=\"javascript:history.back()\" method=post>\n\n";
  echo "      <input type=\"submit\" name=\"button\" value=\"Powrót\">\n\n";
  echo "    </form>\n\n";

  goto footer;
}
else {
  echo "    Jesteś zalogowany jako: " . $_COOKIE[$cookie_name] . ".<br><br>\n\n";
}

echo "    <font color=\"#660066\" size=\"10\">[tu będzie aplikacja dla pracownika]</font>\n\n";

echo "    <form action=\"logout.php\" method=post>\n\n";
echo "      <input type=\"submit\" name=\"button\" value=\"Wyloguj się\">\n\n";
echo "    </form>\n\n";

footer:
include "./footer.php";
?>
