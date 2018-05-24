<?php

include "./header.php";

$cookie_name = "user";
if(isset($_COOKIE[$cookie_name])) {
  echo "    Jesteś zalogowany jako: " . $_COOKIE[$cookie_name] . ".\n\n";
  echo "    <form action=\"appadmin.php\" method=post>\n\n";
  echo "      <input type=\"submit\" name=\"button\" value=\"Dalej\">\n\n";
  echo "    </form>\n\n";
  
  goto footer;
}

echo "    <form action=\"login2.php\" method=post>\n\n";

echo "      <input type=\"text\" name=\"login\" placeholder=\"login\" onfocus=\"this.placeholder=''\" onblur=\"this.placeholder='login'\" >\n\n";

echo "      <input type=\"password\" name=\"password\" placeholder=\"hasło\" onfocus=\"this.placeholder=''\" onblur=\"this.placeholder='hasło'\" >\n\n";
# to delete
echo "      <input type=\"button\" value=\"pls help :(\" onclick=\"message() \" style=\"width: 150px; font-size: 15px; margin-left: 75px; margin-top: 10px;\">";
#
echo "      <input type=\"submit\" name=\"button\" value=\"Zaloguj się\">\n\n";
      
echo "    </form>\n\n";

# to delete
echo "    <script>\n";
echo "      function message() {\n";
echo "        alert(\"login: admin\\nhasło: admin1\")\n";
echo "      }\n";
echo "    </script>\n\n";
#

footer:
include "./footer.php";
?>
