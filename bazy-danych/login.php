<?php

$stylesheet = "login.css";
$title = "Logowanie";

$cookie_name = "user";
if (isset($_COOKIE[$cookie_name])) {
  $style = " style=\"background-color: #e6ffee;\"";
  include "./header.php";

  echo "    Jesteś zalogowany jako: " . $_COOKIE[$cookie_name] . ".\n\n";
  echo "    <form action=\"./appadmin.php\" method=post>\n\n";
  echo "      <input type=\"submit\" name=\"button\" value=\"Dalej\">\n\n";
  echo "    </form>\n\n";
  
  goto footer;
}

include "./header.php";

echo "    <form action=\"./login2.php\" method=post>\n\n";

echo "      <input type=\"text\" name=\"login\" placeholder=\"login\" onfocus=\"this.placeholder=''\" onblur=\"this.placeholder='login'\" required>\n\n";

echo "      <input type=\"password\" name=\"password\" placeholder=\"hasło\" onfocus=\"this.placeholder=''\" onblur=\"this.placeholder='hasło'\" required>\n\n";

echo "      <input type=\"submit\" name=\"button\" value=\"Zaloguj się\">\n\n";
      
echo "    </form>\n\n";

footer:
include "./footer.php";
?>
