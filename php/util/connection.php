<?php

    require_once('connection_config.php');

    function connection()
    {
        $conn = new mysqli(HOST, USER, PWD, DBNAME);
        if($conn -> connect_error)
            die("Connection failed: ".$conn->connect_error);
        return $conn;
    }
?>