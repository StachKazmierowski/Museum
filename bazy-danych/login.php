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
#TODO: to remove
echo "      <input type=\"button\" value=\"pls help :(\" onclick=\"message() \" style=\"width: 150px; font-size: 15px; margin-left: 75px; margin-top: 10px;\">\n\n";
#
echo "      <input type=\"submit\" name=\"button\" value=\"Zaloguj się\">\n\n";
      
echo "    </form>\n\n";

#TODO: to remove
echo "    <script>\n";
echo "      function message() {\n";
echo "        alert(\"login: admin\\nhasło: admin1\")\n";
echo "      }\n";
echo "    </script>\n\n";
#

footer:
include "./footer.php";
?>
