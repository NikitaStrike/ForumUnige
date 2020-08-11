<?php
    require_once('adminFunction.php');
    require_once('connection_config.php');
    require_once('utilError.php');

    function addTag($conn,$fileId,$threadId,$postTags)
    {
        $tags = explode(",", $postTags);
        foreach($tags as $t)
        {
            if(!empty($t))
            {
                $t = trim($t);
                $res = tagAlreadyInDb($conn,$fileId,$t);
                if(is_int($res) && $res===-1)
                    errorSiteAttribute("Impossibile controllare l'esistenza del tag", "singleThread", "idThread", $threadId);
                if(!$res)
                    if(!insertTag($conn,$t,$fileId))
                        errorSiteAttribute("Impossibile salvare i tag", "singleThread","idThread",$threadId);
            }
        }
    }
    function addTagThread($conn,$threadId,$postTags)
    {
        $tags = explode(",", $postTags);
        foreach($tags as $t)
        {
            if(!empty($t))
            {
                $t = trim($t);
                if(!insertTagThread($conn,$t,$threadId))
                    errorSiteAttribute("Impossibile inserire i tag per il thread","singleThread","idThread",$threadId);
            }
        }
    }
    function uploadFile($fileTmp,$fileName,$user)
    {
        $dir = UPLOAD;
        return move_uploaded_file($fileTmp,$dir."_".$user."_".$fileName);
    }

    function redirect($type)
    {
        header("Location:redirect.php?type=$type");
        exit();
    }

    function alreadyInFolder($fileName, $user)
    {
        $dir = UPLOAD;
        return file_exists($dir."_".$user."_".$fileName);
    }
?>