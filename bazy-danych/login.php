<?php

$mypassword = ""; # hidden ;)

$stylesheet = "login.css";
$cookie_name = "user";

### Zalogowany
if (isset($_COOKIE[$cookie_name])) {
  $title = "Jesteś zalogowany";
  $style = " style=\"background-color: #e6ffee;\"";
  
  include "./header.php";

  echo "    Jesteś zalogowany jako: " . $_COOKIE[$cookie_name] . ".\n\n";
  echo "    <form action=\"./appadmin.php\" method=post>\n\n";
  echo "      <input type=\"submit\" name=\"button\" value=\"Przejdź do aplikacji\">\n\n";
  echo "      <input type=\"submit\" name=\"button\" formaction=\"./logout.php\" value=\"Wyloguj się\">\n";
  echo "    </form>\n";
  
  goto footer;
}

function goBack($message) {
  echo "    $message\n\n";
  echo "    <script>\n";
  echo "      setTimeout(function() { history.back(); }, 2000);\n";
  echo "    </script>\n\n";
}


### Formularz
if (empty($_POST["login"])) {
  $title = "Logowanie";
  
  include "./header.php";

  echo "    <form action=\"./login.php\" method=post>\n\n";
  echo "      <input type=\"text\" name=\"login\" placeholder=\"login\" onfocus=\"this.placeholder=''\" onblur=\"this.placeholder='login'\" required>\n";
  echo "      <input type=\"password\" name=\"password\" placeholder=\"hasło\" onfocus=\"this.placeholder=''\" onblur=\"this.placeholder='hasło'\" required>\n";
  echo "      <input type=\"submit\" name=\"button\" value=\"Zaloguj się\">\n\n";
  echo "    </form>\n\n";
  
  goto footer;
}
### Uwierzytelnienie
else {
  $login = $_POST["login"];
  $password = $_POST["password"];
  
  $link = pg_connect("host=labdb dbname=mrbd user=kd370826 password=$mypassword");
  $result = pg_query_params($link, "select * from users where login = $1", array($login));

  $num = pg_numrows($result);
  
  if ($num == 0) {
    
    pg_close($link);
    
    $title = "Nie udało się zalogować";
    $style = " style=\"background-color: #fff3f0;\"";
    include "./header.php";
    
    goBack("Błędny login.");
    goto footer;
  }
  
  $user = pg_fetch_array($result, 0);
  
  if ($password != $user["password"]) {
    
    pg_close($link);
    
    $title = "Nie udało się zalogować";
    $style = " style=\"background-color: #fff3f0;\"";
    include "./header.php";
    
    goBack("Błędne hasło.");
    goto footer;
  }
  
  pg_close($link);
  
  $cookie_value = $login;
  setcookie($cookie_name, $cookie_value, time() + (1800), "/"); # 1800 = 30 minutes # "./" not working on apple devices
  
  $title = "Zalogowano";
  $style = " style=\"background-color: #e6ffee;\"";
  
  header("refresh: 2; url = ./appadmin.php");
  
  include "./header.php";
  
  echo "    Zalogowano jako: " . $login . ".<br>przekierowuję...\n";

}


footer:
include "./footer.php";
?>
