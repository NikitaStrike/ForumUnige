<?php
    session_start();
    require_once('util/retrieve.php');
    require_once('util/adminFunction.php');
    require_once('util/testExistence.php');
    require_once('util/testInput.php');
    require_once('util/utilFunction.php');
    require_once('util/utilError.php');
    require_once('util/sendmail.php');
    require_once('util/connection.php');
    
    if(isset($_POST) && !empty($_POST))
    {
        if(isset($_SESSION['matricola']) && !empty($_SESSION['matricola']))
        {
            $conn = connection();
            $userId = $_SESSION['matricola'];
            if(!empty($_GET['idThread']))
                $threadId = $_GET['idThread'];
            if(checkThreadExists($conn,$threadId))
            {
                if(empty($_POST['textThread']))
                    errorSiteAttribute("Il testo del messaggio non può essere vuoto","singleThread","idThread",$threadId);
                $_SESSION['textThread'] = $_POST['textThread'];
                if(!empty($_POST['tags']) && empty($_FILES["userfile"]["tmp_name"]))
                    errorSiteAttribute("Scegliere il file da inserire. Dimensione consentita: 2MB.","singleThread","idThread",$threadId);
                    $_SESSION['tagsFile'] = $_POST['tags'];
                if(!empty($_FILES["userfile"]["tmp_name"]) && empty($_POST['tags']))
                    errorSiteAttribute("Inserire almeno un tag per il file","singleThread","idThread",$threadId);
                if(!empty($_POST['tags']))
                {
                    $tags = explode(",",$_POST['tags']);
                    foreach($tags as $t)
                    {
                        if(!empty($t))
                        {
                            $t = trim($t);
                            $resultTag = checkTag($conn,$t);
                            if($resultTag===-1)
                                internalError("Impossibile completare la ricerca del tag", "singleThreadAnswer");
                            if($resultTag==0)
                                errorSiteAttribute("Il tag ".$t." non esiste.Scegli i tag tra quelli proposti","singleThread","idThread",$threadId);
                        }
                    }
                }
               
                if(!empty($_FILES["userfile"]["tmp_name"]))
                {
                    if(!checkName($_FILES["userfile"]["name"]))
                        errorSiteAttribute("Il nome del file può contenere solo caratteri alfanumerici,.-_\nIl tipo può essere solo .pdf","singleThread","idThread",$threadId);
                    if(checkLength($_FILES["userfile"]["name"]))
                        errorSiteAttribute("Il nome del file deve essere inferiore ai 250 caratteri", "singleThread","idThread",$threadId);
                    if(checkSize($_FILES["userfile"]["size"]))
                        errorSiteAttribute("Dimensione massima consentita 2MB","singleThread","idThread",$threadId);
                    if(empty($_GET['mexId']))
                        if(alreadyInFolder($_FILES["userfile"]["name"], $userId))
                            errorSiteAttribute("Il file è già stato caricato. Si consiglia di cambiarne il nome","singleThread", "idThread", $threadId);
                }
                
                if(!empty($_GET['mexId']))
                {
                    $messId = $_GET['mexId'];
                    
                    if(!empty($_FILES["userfile"]["tmp_name"]))
                    {
                        $res = checkIfHasFile($conn,$messId);
                        if($res===-1)
                            internalError("Impossibile controllare l'esistenza di file associati al messaggio", "singleThreadAnswer");
                        if($res==0)
                        {
                            if(!$fileId = insertFile($conn,$_FILES["userfile"]["name"],$messId,$userId))
                                internalError("Impossibile salvare il file","singleThreadAnswer");
                        }
                        else
                        {   $fileId=$res;
                            if(!$fileName = retrieveFileNamefromId($conn,$fileId))
                                internalError("Impossibile recuperare il nome del file", "singleThreadAnswer");
                            if(!deleteFile($userId, $fileName))
                                internalError("Impossibile cancellare il file dalla cartella", "singleThreadAnswer");
                            if(!updateFile($conn,$fileId, $_FILES["userfile"]["name"], $messId, $userId))
                                internalError("Impossibile aggiornare il file", "singleThreadAnswer");
                        }
                        if(!uploadFile($_FILES["userfile"]["tmp_name"],$_FILES["userfile"]["name"],$userId))
                            internalError("Impossibile caricare il file","singleThreadAnswer");
                        
                        addTag($conn,$fileId,$threadId,$_POST['tags'],$_FILES["userfile"]["name"]);
                    }
                    if(checkIfFirstQuestion($conn,$messId))
                        if(!updateQuestion($conn,$_POST['textThread'], $threadId))
                            internalError("Impossibile modificare la domanda", "singleThreadAnswer");
                    if(!updateMessage($conn,$messId,$_POST['textThread']))
                        internalError("Impossibile modificare il messaggio","singleThreadAnswer");
                }
                else
                {
                    if(!$mexId = insertMessage($conn,$_POST['textThread'],$threadId,$userId,0))
                        internalError("Impossibile salvare il messaggio","singleThreadAnswer");
                    if(!empty($_FILES["userfile"]["tmp_name"]))
                    {
                        if(!uploadFile($_FILES["userfile"]["tmp_name"],$_FILES["userfile"]["name"],$userId))
                            internalError("Impossibile caricare il file","singleThreadAnswer");
                        if(!$fileId = insertFile($conn,$_FILES["userfile"]["name"],$mexId,$userId))
                        {
                            if(!deleteFile($userId,$_FILES["userfile"]["name"]))
                                internalError("Impossibile cancellare il file", "singleThreadAnswer");
                            if(!deleteMessage($conn,$mexId))
                                internalError("Impossibile cancellare il messaggio","singleThreadAnswer");
                            internalError("Impossibile salvare il file","singleThreadAnswer");
                        }
                        addTag($conn,$fileId,$threadId,$_POST['tags']);
                    }
                }
                if(isset($_SESSION['forumName']) && isset($_SESSION['threadName']) && !empty($_SESSION['forumName']) && !empty($_SESSION['threadName']))
                    send_mail($userId, $_SESSION['forumName'], $_SESSION['threadName']);
                $conn->close();
                unset($_SESSION['modify']);
                unset($_SESSION['textThread']);
                unset($_SESSION['tagsFile']);
                unset($_SESSION['errorMessage']);
                header("Location:singleThread.php?idThread=$threadId");
                exit(); 
            }
            else
                internalError("Impossibile recuperare il thread","singleThreadAnswer","singleThreadAnswer");
        }  
    }
    else
    {   unset($_SESSION['modify']);
        unset($_SESSION['textThread']);
        unset($_SESSION['tagsFile']);
        unset($_SESSION['errorMessage']);
    }
