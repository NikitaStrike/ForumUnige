<?php  
    function internalError($message,$sourceFile)
    {
        $_SESSION['internalError'] = $message;
        $_SESSION['sourceError'] = $sourceFile;
        header("Location:error.php");
        exit();
    }

    function errorSite($message,$location)
    {
        $_SESSION['errorMessage'] = $message;
        header("Location:".$location.".php");
        exit();
    }

    function errorSiteAttribute($message,$location,$attribute, $value)
    {
        $_SESSION['errorMessage'] = $message;
        header("Location:".$location.".php?".$attribute."=".$value);
        exit();
    }


    function noError($message,$location)
    {
        $_SESSION['okMessage'] = $message;
        header("Location:".$location.".php");
        exit();
    }

    function openError()
    {
        echo("Errore interno al sito. Contattare l'amministratore del sito");
        echo("<br>Messaggio dell'errore: ".$_SESSION['internalError']."<br>");
        echo("Fonte dell'errore: ".$_SESSION['sourceError'].".php<br>");
        unset($_SESSION['internalError']);
        unset($_SESSION['sourceError']);
        echo("<a href='home.php'>Ritorna alla homepage</a>");
    }

?>