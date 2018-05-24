<?php
include "./header.php";

  $login = $_POST["login"];
  if ($login == "") {
    echo "Podaj login.";
    echo "    <form action=\"javascript:history.back()\" method=post>\n\n";
    echo "      <input type=\"submit\" name=\"button\" value=\"Powrót\">\n\n";
    echo "    </form>\n\n";
    
    goto footer;
  }

  $password = $_POST["password"];
  if ($password == "") {
    echo "Podaj hasło.";
    echo "    <form action=\"javascript:history.back()\" method=post>\n\n";
    echo "      <input type=\"submit\" name=\"button\" value=\"Powrót\">\n\n";
    echo "    </form>\n\n";
    
    goto footer;
  }

  $link = pg_connect("host=labdb dbname=mrbd user=scott password=tiger");
  $result = pg_query($link, "select * from users where login = '" . $login . "'");

  $num = pg_numrows($result);

  if ($num == 0) {
    echo "Błędny login.";
    echo "    <form action=\"javascript:history.back()\" method=post>\n\n";
    echo "      <input type=\"submit\" name=\"button\" value=\"Powrót\">\n\n";
    echo "    </form>\n\n";
    
    pg_close($link);
    goto footer;
  }

  $user = pg_fetch_array($result, 0);
    
  if ($password != $user["password"]) {
    echo "Błędne hasło.";
    echo "    <form action=\"javascript:history.back()\" method=post>\n\n";
    echo "      <input type=\"submit\" name=\"button\" value=\"Powrót\">\n\n";
    echo "    </form>\n\n";
    
    pg_close($link);
    goto footer;
  }

  echo "Zalogowano jako " . $user["login"] . ".<br>\n";
  if ($user["isadmin"] == "t") {
    echo "Posiadasz uprawnienia administratora.\n\n";
  }

  echo "    <form action=\"login3.php\" method=post>\n\n";
  echo "      <input type=\"submit\" name=\"button\" value=\"Dalej\">\n\n";
  echo "    </form>\n\n";


  pg_close($link);

footer:
include "./footer.php";
?>
