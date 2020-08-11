<?php
    require_once('connection.php');
    require_once('connection_config.php');

//////////////////////////////////////INSERT//////////////////////////////////////
////////////////////////Inserisce lo studente appena registrato////////////////////////
    function insertStudent($conn,$matricola,$email,$pwd, $corso, $anno)
    {
        if(!$stmt = $conn->prepare("INSERT INTO Studente (Matricola, Email, Pwd, Corso, Anno) VALUES (?,?,?,?,?)"))
            return false;
        if(!$stmt->bind_param("sssss", $matricola,$email, $pwd, $corso,$anno))
            return false;
        if(!$stmt->execute())
            return false;
        if($stmt->affected_rows==0)
            return false;
        $stmt->close();
        return true;
    }
////////////////////////Inserisce nella tabella Preferisce, l'insegnamento che lo studente ha deciso di seguire////////////////////////
    function insertFollowedTeaching($conn,$userId,$codInsegnamento,$verAnno)
    {
        $query = "INSERT INTO Preferisce VALUES(?,?,?)";
        if(!$stmt = $conn->prepare($query))
            return false;
        if(!$stmt->bind_param("sss",$userId,$codInsegnamento,$verAnno))
            return false;
        if(!$stmt->execute())
            return false;
        if($stmt->affected_rows==0)
            return false;
        $stmt->close();
        return true;
    }
////////////////////////Inserisce un nuovo thread////////////////////////
    function insertThread($conn,$titleThread,$textThread,$forumId,$userId)
    {
        if(!$stmt = $conn->prepare("INSERT INTO Thread VALUES(NULL,?,?,DEFAULT,?,?,DEFAULT,DEFAULT)"))
            return false;
        if(!$stmt->bind_param("ssss", $titleThread, $textThread,$forumId,$userId))
            return false;
        if(!$stmt->execute())
            return false;
        if($stmt->affected_rows==0)
            return false;
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }
////////////////////////Inserisce un nuovo messaggio////////////////////////
    function insertMessage($conn,$textThread,$threadId,$userId,$firstQuestion)
    {
        if($firstQuestion)
            $query ="INSERT INTO Messaggio VALUES(NULL,?,DEFAULT,?,?,1)";
        else
            $query = "INSERT INTO Messaggio VALUES(NULL,?,DEFAULT,?,?,DEFAULT)";
        if(!$stmt =$conn->prepare($query))
            return false;
        if(!$stmt->bind_param("sss",$textThread, $threadId, $userId))
            return false;
        if(!$stmt->execute())
            return false;
        if($stmt->affected_rows==0)
            return false;
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }
////////////////////////Inserisce un nuovo file. Il file "fisico" si trova nella cartella upload////////////////////////
    function insertFile($conn,$fileName,$mexId,$userId)
    {
        if(!$stmt = $conn->prepare("INSERT INTO Files VALUES(NULL, ?,?,?)"))
            return false;
        if(!$stmt->bind_param("sis",$fileName,$mexId,$userId))
            return false;
        if(!$stmt->execute())
            return false;
        if($stmt->affected_rows==0)
            return false;
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }
////////////////////////Aggiunge la relazione tra tag e file////////////////////////
    function insertTag($conn,$tag,$fileId)
    {
        $query = "INSERT INTO HaTags VALUES(NULL,?,NULL,?,NULL)";
        if(!$stmt = $conn->prepare($query))
            return false;
        if(!$stmt->bind_param("si",$tag,$fileId))
            return false;
        if(!$stmt->execute())
            return false;
        if($stmt->affected_rows==0)
            return false;
        $stmt->close();
        return true;
    }
////////////////////////Aggiunge la relazione tra tag e thread////////////////////////
    function insertTagThread($conn,$tag,$threadId)
    {
        $query = "INSERT INTO HaTags VALUES(NULL,?,?,NULL,NULL)";
        if(!$stmt = $conn->prepare($query))
            return false;
        if(!$stmt->bind_param("si",$tag,$threadId))
            return false;
        if(!$stmt->execute())
            return false;
        if($stmt->affected_rows==0)
            return false;
        $stmt->close();
        return true;
    }
////////////////////////Inserisce nella tabella punti, il nuovo like ricevuto dal messaggio indicato////////////////////////
    function insertPunti($conn,$matricola, $mexId, $codInsegnamento)
    {
        $query = "INSERT INTO Punti VALUES(?,?,?)";
        if(!$stmt = $conn->prepare($query))
            return false;
        if(!$stmt->bind_param("sis",$matricola, $mexId,$codInsegnamento))
            return false;
        if(!$stmt->execute())
            return false;
        if($stmt->affected_rows==0)
            return false;
        $stmt->close();
        return true;
    }
//////////////////////////////////////UPDATE//////////////////////////////////////
////////////////////////Aggiorna lo username dello studente////////////////////////
    function updateUsername($conn,$email, $username)
    {  
        $query = "UPDATE Studente SET Username = ? WHERE Email = ?";
        if(!$stmt = $conn->prepare($query))
            return false;
        if(!$stmt->bind_param("ss",$username, $email))
            return false;
        if(!$stmt->execute())
            return false;
        if($stmt->affected_rows==0)
            return false;
        $stmt->close();
        return true;
    }
////////////////////////Aggiorna la password dello studente////////////////////////
    function updatePassword($conn,$email, $pwd)
    {
        $query = "UPDATE Studente SET Pwd = ? WHERE Email = ?";
        if(!$stmt = $conn->prepare($query))
            return false;
        if(!$stmt->bind_param("ss",$pwd, $email))
            return false;
        if(!$stmt->execute())
            return false;
        if($stmt->affected_rows==0)
            return false;
        $stmt->close();
        return true;
    }
////////////////////////Aggiorna il testo del messaggio modificato////////////////////////
    function updateMessage($conn,$idMex,$text)
    {
        $query = "UPDATE Messaggio SET Testo = ? WHERE Id = ?";
        if(!$stmt = $conn->prepare($query))
            return false;
        if(!$stmt->bind_param("si",$text, $idMex))
            return false;
        if(!$stmt->execute())
            return false;
        $stmt->close();
        return true;
    }
////////////////////////Aggiorna il testo della domanda////////////////////////
    function updateQuestion($conn,$text,$thread)
    {
        $query = "UPDATE Thread SET Domanda = ? WHERE Id = ?";
        if(!$stmt = $conn->prepare($query))
        return false;
        if(!$stmt->bind_param("si",$text, $thread))
            return false;
        if(!$stmt->execute())
            return false;
        if($stmt->affected_rows==0)
            return false;
        $stmt->close();
        return true;
    }

////////////////////////Aggiorna lo stato di chiusura del thread////////////////////////
    function closeThread($conn,$idThread)
    {
        $query = "UPDATE Thread SET Chiuso = 1 WHERE Id = ?";
        if(!$stmt = $conn->prepare($query))
            return false;
        if(!$stmt->bind_param("i", $idThread))
            return false;
        if(!$stmt->execute())
            return false;
        if($stmt->affected_rows==0)
            return false;
        $stmt->close();
        return true;
    }
////////////////////////Aggiorna lo stato di risoluzione del thread////////////////////////
    function resolveThread($conn,$idThread)
    {
        $query = "UPDATE Thread SET Risolto = 1 WHERE Id = ?";
        if(!$stmt = $conn->prepare($query))
            return false;
        if(!$stmt->bind_param("i",$idThread))
            return false;
        if(!$stmt->execute())
            return false;
        if($stmt->affected_rows==0)
            return false;
        $stmt->close();
        return true;
    }
////////////////////////Aggiorna il file già associato ad un messaggio////////////////////////
    function updateFile($conn,$id, $name, $mex, $user)
    {
        $query = "UPDATE Files SET Nome =?, Messaggio=?, Autore=? WHERE Id = ?";
        if(!$stmt = $conn->prepare($query))
            return false;
        if(!$stmt->bind_param("sssi", $name,$mex,$user,$id))
            return false;
        if(!$stmt->execute())
            return false;
        $stmt->close();
        return true;
    }
//////////////////////////////////////DELETE//////////////////////////////////////
////////////////////////Cancella il messaggio indicato////////////////////////
    function deleteMessage($conn,$idMex)
    {
        $query= "DELETE FROM Messaggio WHERE Id=?";
        if(!$stmt=$conn->prepare($query))
            return false;
        if(!$stmt->bind_param("i",$idMex))
            return false;
        if(!$stmt->execute())
            return false;
        if($stmt->affected_rows==0)
            return false;
        $stmt->close();
        return true;
    }
////////////////////////Cancella dalla tabella Preferisce, l'insegnamento non più seguito dallo studente////////////////////////
    function deleteFollowedTeaching($conn,$codInsegnamento,$verAnno,$userId)
    {
        $query = "DELETE FROM Preferisce WHERE CodInsegnamento = ? AND AnnoInsegnamento=? AND Studente = ?";
        if(!$stmt=$conn->prepare($query))
            return false;
        if(!$stmt->bind_param("sss",$codInsegnamento,$verAnno,$userId))
            return false;
        if(!$stmt->execute())
            return false;
        if($stmt->affected_rows==0)
            return false;
        $stmt->close();
        return true;
    }
////////////////////////Cancella il thread indicato////////////////////////
    function deleteThread($conn,$threadId)
    {
        $query = "DELETE FROM Thread WHERE Id = ?";
        if(!$stmt=$conn->prepare($query))
            return false;
        if(!$stmt->bind_param("i",$threadId))
            return false;
        if(!$stmt->execute())
            return false;
        if($stmt->affected_rows==0)
            return false;
        $stmt->close();
        return true;    
    }
////////////////////////Cancella il like////////////////////////
    function deleteLike($conn, $userId,$mexId,$codInsegnamento)
    {
        $query="DELETE FROM Punti WHERE Studente=? AND Messaggio=? AND Insegnamento=?";
        if(!$stmt=$conn->prepare($query))
            return false;
        if(!$stmt->bind_param("sis",$userId,$mexId,$codInsegnamento))
            return false;
        if(!$stmt->execute())
            return false;
        if($stmt->affected_rows==0)
            return false;
        $stmt->close();
        return true;  
    }

////////////////////////Cancella il file dalla cartella upload////////////////////////
    function deleteFile($userId, $fileName)
    {   
        $path = UPLOAD."_".$userId."_".$fileName;
        return unlink($path);
    }
?>