<?php

    require_once('util/retrieve.php');
    require_once('util/utilError.php');
    require_once('util/connection.php');
    $data = array();

    if(isset($_GET["query"]))
    {
        $conn = connection();
        if(isset($_GET['corso']))
            $result = retrieveWithSearch($conn,$_GET["query"]);
        else
            $result = retrieveTags($conn,$_GET["query"]);
        if(is_int($result))
        {
            if($result==-1)
                internalError("Impossibile eseguire la ricerca per tag","search");
        }
        while($row = $result->fetch_assoc())
        {
            $data[] = $row["Nome"];

        }
        $conn->close();
    }
    echo json_encode($data);
