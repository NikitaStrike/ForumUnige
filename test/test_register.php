<?php

function register($matricola, $email, $password, $corso, $anno, $baseurl) {

    $matricola= urlencode($matricola);
    $email = urlencode($email);
    $password = urlencode($password);
    $corso = urlencode($corso);
    $anno = urlencode($anno);
    $ch = curl_init();

    $url = "$baseurl/signup.php";

    $cookieFile = "cookies";
    if(!file_exists($cookieFile)) {
        $fh = fopen($cookieFile, "w");
        fwrite($fh, "");
        fclose($fh);
    }

    curl_setopt($ch, CURLOPT_URL, "$baseurl/signup.php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "matricola=$matricola&email=$email&password=$password&passwordrepeat=$password&corsoDiLaurea=$corso&anno=$anno");

    $headers = array();
    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile); // Cookie aware
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile); // Cookie aware

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
}
