<?php

include "./header.php";
    
echo "    <form action=\"./login2.php\" method=post>\n\n";
echo "      <input type=\"text\" name=\"login\" placeholder=\"login\" onfocus=\"this.placeholder=''\" onblur=\"this.placeholder='login'\" >\n\n";

echo "      <input type=\"password\" name=\"password\" placeholder=\"hasło\" onfocus=\"this.placeholder=''\" onblur=\"this.placeholder='hasło'\" >\n\n";

echo "      <input type=\"submit\" name=\"button\" value=\"Zaloguj się\">\n\n";
      
echo "    </form>\n\n";

include "./footer.php";
?>
