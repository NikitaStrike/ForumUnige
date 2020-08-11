<?php
    require_once('connection.php');

    function checkNameCourseExists($conn,$nameCourse)
    {
        $query = "SELECT Nome FROM Insegnamento WHERE Nome = '$nameCourse'";
        if(!$stmt = $conn->query($query))
            return -1;
        if($stmt->num_rows==0)
            return false;
        $stmt->close();
        return true;
    }
    function checkSNExists($conn,$sn)
    {
        $query = "SELECT Matricola FROM Studente WHERE Matricola = '$sn'";
        if(!$stmt = $conn->query($query))
            return -1;
        if($stmt->num_rows==0)
            return false;
        $stmt->close();
        return true;
    }
    function checkThreadExists($conn,$idThread)
    {
        $query = "SELECT Id FROM Thread WHERE Id = '$idThread'";
        if(!$stmt=$conn->query($query))
            return -1;
        if($stmt->num_rows==0)
            return false;
        $stmt->close();
        return true;
    }

    function tagAlreadyInDb($conn,$fileId, $tag)
    {
        $query = "SELECT Id FROM HaTags WHERE Tag = '$tag' AND File = '$fileId'";
        if(!$result = $conn->query($query))
            return -1;
        if($result->num_rows!=0)
            return true;
        return false;
    }

    function checkTag($conn,$t)
    {
        $query = "SELECT Nome FROM Tags WHERE Nome = '$t'";
        if(!$result = $conn->query($query))
            return -1;
        if($result->num_rows==0)
            return 0;
        return 1;
    }
?>