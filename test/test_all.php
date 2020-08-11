<?php

require 'test_login.php';
require 'test_register.php';
require 'test_show.php';
require 'test_update.php';
require 'test_search.php';
require 'utils.php';

$baseurl = 'https://webdev19.dibris.unige.it/~S4502621/php';

echo "[+] Testing Registration - Login - Show Profile";

echo "<br>[*] Generating random user";

echo "<br>---";
$matricola = generate_random_matricola();
$email = generate_random_email();
$pass = generate_random_password();
$corso = "Informatica";
$anno = "terzo";

echo "<br>Email: $email";
echo "<br>Pass: $pass";
echo "<br>Matricola: $matricola";
echo "<br>CdL: $corso";
echo "<br>Anno: $anno";
echo "<br>---";

echo "<br>[-] Calling signup.php";
register($matricola, $email, $pass, $corso, $anno, $baseurl);

echo "<br>[-] Calling login.php";
login($email, $pass, $baseurl);

echo "<br>[-] Calling show_profile.php";
echo check_correct_user($matricola, show_logged_user($baseurl))
    ? "<br>[*] Success!"
    : "<br>[*] Failed";

echo "<br>------------------------";

echo "<br>[+] Testing Update - Show Profile";

echo "<br>[*] Generating random username";
$username = generate_random_name();

echo "<br>---";
echo "<br>Username: $username";
echo "<br>---";

echo "<br>[-] Calling update.php";
update($username, $baseurl);

echo "<br>[-] Calling show_profile.php";
echo check_correct_user($username, show_logged_user($baseurl))
    ? "<br>[*] Success!"
    : "<br>[*] Failed";

echo "<br>------------------------";
echo "<br>[+] Testing Search";
echo "<br>[*] Searching \"Introduzione alla Programmazione\"";
$nome = "Introduzione alla Programmazione";

echo "<br>[-] Calling cerca.php";
echo check_search_found($nome, search($nome, $baseurl))
    ? "<br>[*] Success!"
    : "<br>[*] Failed";
