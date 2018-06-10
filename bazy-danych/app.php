<?php

switch ($_GET["page"]) {
  case "exhibits":
    $header = "Eksponaty";
    break;
  case "artists":
    $header = "Artyści";
    break;
  case "galleries":
    $header = "Galerie";
    break;
  case "tour":
    $header = "Wystawy objazdowe";
    break;
  case "":
    $header = "Witamy w aplikacji muzeum.";
    break;
  default:
    header("Location: ./app.php");
}

$stylesheet = "app.css";
$title = "Aplikacja dla gościa";
include "./header.php";

echo "    <div class=\"header\">\n      $header\n    </div>\n\n";

if ($header == "Witamy w aplikacji muzeum.") {
  echo "    <div class=\"tiny\">\n";

  echo "      <form action=\"http://students.mimuw.edu.pl/~kd370826/bazy-danych/\" method=post>\n";
  echo "        <input type=\"submit\" name=\"button\" value=\"Strona główna\">\n";
  echo "      </form>\n";

  echo "    </div>\n\n";

  echo "    <a href=\"?page=exhibits\">\n      <div class=\"page\">\n        ekspo<br>naty\n      </div>\n    </a>\n\n";
  echo "    <a href=\"?page=artists\">\n      <div class=\"page\">\n        arty<br>ści\n      </div>\n    </a>\n\n";
  echo "    <a href=\"?page=galleries\">\n      <div class=\"page\">\n        gale<br>rie\n      </div>\n    </a>\n\n";
  echo "    <a href=\"?page=tour\">\n      <div class=\"page\">\n        obja<br>zdy\n      </div>\n    </a>\n\n";
}
else {
  echo "    <form action=\"app.php\" method=post>\n";
  echo "      <input type=\"submit\" name=\"button\" value=\"Powrót\">\n";
  echo "    </form>\n\n";
}


echo "    <div class=\"tiny\">\n";

$cookie_name = "user";
if (isset($_COOKIE[$cookie_name])) {
  echo "      Jesteś zalogowany jako: " . $_COOKIE[$cookie_name] . "\n";
  echo "      <form action=\"appadmin.php\" method=post>\n";
  echo "        <input type=\"submit\" name=\"button\" value=\"Wersja dla pracownika\">\n";
  echo "      </form>\n";
}
else {
  echo "      <form action=\"login.php\" method=post>\n";
  echo "        <input type=\"submit\" name=\"button\" value=\"Jestem pracownikiem\">\n";
  echo "      </form>\n";
}

echo "    </div>\n";

footer:
include "./footer.php";
?>
