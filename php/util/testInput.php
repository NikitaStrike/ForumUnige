<?php
    function testMatricola($value)
    {
        return preg_match("/[sS]\d{7}/", $value);
    }
    function testEmail($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
    function testUsername($value)
    {
        return preg_match("/[a-zA-Z0-9]+/",$value);
    }
    function checkDoublePwd($pwd,$secondpwd)
    {
        return $pwd===$secondpwd;
    }
    function checkName($value)
    {
        return preg_match("/^[-0-9a-zA-Z_\.]+\.pdf$/", $value);
    }
    function checkSize($value)
    {
        return $value>2000000;
    }
    function checkLength($value)
    {
        return strlen($value)>250;
    }
?>
