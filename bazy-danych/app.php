<?php

$stylesheet = "app.css";
$title = "Aplikacja dla gościa";
include "./header.php";
    
echo "    <font color=\"#660066\" size=\"10\">[tu będzie aplikacja dla gościa]</font>\n\n";

$cookie_name = "user";
if(isset($_COOKIE[$cookie_name])) {

  echo "    <br><br>\n    Jesteś zalogowany jako: " . $_COOKIE[$cookie_name] . ".\n\n";
  echo "    <form action=\"appadmin.php\" method=post>\n\n";
  echo "      <input type=\"submit\" name=\"button\" value=\"Wersja dla pracownika\">\n\n";
  echo "    </form>\n\n";
  
  goto footer;
}

echo "    <form action=\"login.php\" method=post>\n\n";

echo "      <input type=\"submit\" name=\"button\" value=\"Zaloguj się jako pracownik\">\n\n";
      
echo "    </form>\n\n";

footer:
include "./footer.php";
?>
