<?php
    require_once('connection.php');

/////////////////////////////////STUDENTE/////////////////////////////////
////////////////Recupera tutti gli studenti iscritti e che hanno il corso indicato tra i preferiti////////////////
    function retrieveStudents($courseName)
    {
        $conn = connection();
        $query = "SELECT Email FROM Studente JOIN Preferisce JOIN Insegnamento 
                  WHERE Studente.Matricola=Preferisce.Studente 
                  AND Preferisce.CodInsegnamento=Insegnamento.Codice 
                  AND Insegnamento.Nome='$courseName'";
        if(!$stmt = $conn->query($query))
            return -1;
        return $stmt;
    }
////////////////Recupera la matricola conoscendo l'email////////////////
    function retrieveSerialNumberFromMail($conn,$email)
    {
        $email = $conn->real_escape_string($email);
        if(!$stmt = $conn->query("SELECT Matricola FROM Studente WHERE Email = '$email'"))
            return -1;
        if($stmt->num_rows==0)
            return 0;
        $res = $stmt->fetch_assoc();
        $stmt->close();
        return $res['Matricola'];
    }
////////////////Recupera l'username conoscendo l'email////////////////
    function retrieveUsernameFromSN($conn,$matricola)
    {
        if(!$stmt = $conn->query("SELECT Username FROM Studente WHERE Matricola = '$matricola'"))
            return -1;
        if($stmt->num_rows==0)
            return 0;
        $res = $stmt->fetch_assoc();
        $stmt->close();
        return $res['Username'];
    }
////////////////Recupera la password conoscendo l'email////////////////
    function retrieveUserPwdFromMail($conn,$email)
    {
        $email = $conn->real_escape_string($email);
        if(!$stmt = $conn->query("SELECT Pwd FROM Studente WHERE Email = '$email'"))
            return false;
        $res = $stmt->fetch_assoc();
        $stmt->close();
        return $res['Pwd'];
    }
/////////////////////////////////INSEGNAMENTO/////////////////////////////////
////////////////Recupera il nome dell'insegnamento conoscendo il codice////////////////
    function retrieveTeachingNameFromId($conn,$id)
    {
        if(!$stmt = $conn->query("SELECT Nome FROM Insegnamento WHERE Codice = '$id'"))
            return false;
        $res = $stmt->fetch_assoc();
        $stmt->close();
        return $res['Nome'];
    }
////////////////Recupera il codice dell'insegnamento conoscendo il nome////////////////
    function retrieveTeachingIdFromName($conn,$name)
    {
        if(!$stmt = $conn->query("SELECT Codice FROM Insegnamento WHERE Nome = '$name'"))
            return false;
        $res = $stmt->fetch_assoc();
        $stmt->close();
        return $res['Codice'];
    }
////////////////Recupera il nome dell'insegnamento conoscendo il thread del forum corrispondente////////////////
    function retrieveTeachingNameFromThread($conn,$idThread)
    {
        $query = "SELECT Nome FROM Insegnamento JOIN Forum JOIN Thread
        WHERE Insegnamento.Codice = Forum.CodInsegnamento AND Forum.Id = Thread.Forum AND Thread.Id = '$idThread'";
        if(!$stmt = $conn->query($query))
            return false;
        $res = $stmt->fetch_assoc();
        $stmt->close();
        return $res['Nome'];
    }
////////////////Recupera tutti gli insegnamenti seguiti dallo studente////////////////
    function retrieveTeachingFollowed($conn, $idUser)
    {
        if(!$res = $conn->query("SELECT Nome, Codice, VersioneAnno, CFU, Semestre FROM Preferisce JOIN Insegnamento
        WHERE Preferisce.CodInsegnamento = Insegnamento.Codice
        AND Preferisce.Studente = '$idUser' ORDER BY Insegnamento.Nome"))
            return false;
        return $res;
    }
////////////////Recupera tutti gli insegnamenti non seguiti dallo studente////////////////
    function retrieveAllTeachingNotFollowed($conn,$idUser)
    {
        if(!$result = $conn->query("SELECT Nome, Codice, VersioneAnno, CFU, Semestre FROM Insegnamento WHERE Insegnamento.Nome
        NOT IN
        (SELECT Insegnamento.Nome FROM Preferisce JOIN Studente JOIN Insegnamento
        WHERE  Preferisce.CodInsegnamento = Insegnamento.Codice
        AND Preferisce.Studente = '$idUser') ORDER BY Insegnamento.CorsoDiLaurea, Insegnamento.Nome"))
            return false;
        return $result;
    }
////////////////Recupera tutti gli insegnamenti////////////////
    function retrieveAllTeaching($conn)
    {
        if(!$result = $conn->query("SELECT Nome, Codice, VersioneAnno, CFU, Semestre FROM Insegnamento ORDER BY CorsoDiLaurea, Nome"))
            return false;
        return $result;
    }
///////////////Recupera il nome e l'anno dell'insegnamento conoscendo il corso di laurea e l'anno di riferimento///////////////
    function retrieveTeachingFromCourseAndYear($conn,$course,$anno)
    {
        if(!$result = $conn->query("SELECT Nome, Codice,CFU, Semestre, VersioneAnno FROM Insegnamento WHERE CorsoDiLaurea='$course' AND Anno='$anno'"))
            return false;
        return $result;
    }
///////////////Recupera il nome e l'anno dell'insegnamento conoscendo il cordo di laurea///////////////
    function retrieveTeachingFromCourse($conn,$course)
    {
        if(!$result = $conn->query("SELECT Nome, Codice,CFU, Semestre,VersioneAnno FROM Insegnamento WHERE CorsoDiLaurea='$course'"))
            return false;
        return $result;
    }
///////////////Recupera il nome e l'anno dell'insegnamento conoscendo l'anno di riferimento///////////////
    function retrieveTeachingFromYear($conn,$anno)
    {
        if(!$result = $conn->query("SELECT Nome,Codice,CFU, Semestre, VersioneAnno FROM Insegnamento WHERE Anno='$anno'"))
            return false;
        return $result;
    }
///////////////Recupera le informazioni dell'insegnamento conoscendo il nome derivante dalla ricerca///////////////
    function retrieveWithSearch($conn,$name)
    {
        if(!$result = $conn->query("SELECT Nome, VersioneAnno, Semestre, Codice, CFU FROM Insegnamento WHERE Nome LIKE '".$name."%'"))
            return -1;
        if($result->num_rows==0)
            return 0;
        return $result;
    }
///////////////Controlla che l'insegnamento cercato sia seguito dallo studente indicato///////////////
    function checkIfFollowed($conn,$name,$user)
    {
        if(!$stmt=$conn->query("SELECT true FROM Insegnamento WHERE Nome='$name' AND Codice IN (SELECT CodInsegnamento FROM Preferisce JOIN Insegnamento WHERE CodInsegnamento=Codice AND Insegnamento.Nome='$name'AND Studente='$user')"))
            return -1;
        if($stmt->num_rows == 0)
            return 0;
        $stmt->close();
        return "OK";
    }
/////////////////////////////////FILE/////////////////////////////////
////////////////Recupera il nome del file conoscendo l'id e l'autore////////////////
    function retrieveFileId($fileName,$userId)
    {
        $conn = connection();
        $query = "SELECT Id FROM Files WHERE Nome = '$fileName' AND Autore = '$userId";
        if(!$stmt = $conn->query($query))
            return false;
        $result = $stmt->fetch_assoc();
        $stmt->close();
        return $result['Id'];
    }
////////////////Recupera il nome del file conoscendo l'id////////////////
    function retrieveFileNamefromId($conn,$id)
    {
        $query = "SELECT Nome FROM Files WHERE Id = '$id'";
        if(!$stmt = $conn->query($query))
            return false;
        $result = $stmt->fetch_assoc();
        $stmt->close();
        return $result['Nome'];
    }
////////////////Recupera l'autore del file////////////////
    function retrieveAuthorFromIdFile($conn,$id)
    {
        $query = "SELECT Autore FROM Files WHERE Id = '$id'";
        if(!$stmt = $conn->query($query))
            return false;
        $result = $stmt->fetch_assoc();
        $stmt->close();
        return $result['Autore'];
    }
////////////////Recupera tutti i file dell'insegnamento////////////////
    function retrieveAllFileFromCourse($conn,$nameCourse)
    {
        $query = "SELECT Files.Id, Files.Nome, Files.Autore FROM Files JOIN Messaggio
        JOIN Thread JOIN Forum JOIN Insegnamento
        WHERE Messaggio.Id = Files.Messaggio AND Messaggio.Thread=Thread.Id
        AND Forum.Id=Thread.Forum AND Insegnamento.Codice=Forum.CodInsegnamento
        AND Insegnamento.Nome='$nameCourse'";
        if(!$result = $conn->query($query))
            return -1;
        if($result->num_rows==0)
            return 0;
        return $result;
    }
////////////////Recupera il nome di un eventuale file associato al messaggio indicato////////////////
    function retrieveFileNameFromMex($conn,$mexId)
    {
        $query = "SELECT Nome FROM Files WHERE Messaggio='$mexId'";
        if(!$result = $conn->query($query))
            return -1;
        if($result->num_rows==0)
            return 0;
        $res = $result->fetch_assoc();
        $result->close();
        return $res['Nome'];
    }
////////////////Recupera l'id del file associato al messaggio indicato, se presente////////////////
    function checkIfHasFile($conn,$mex)
    {
        $query = "SELECT Id From Files WHERE Messaggio = '$mex'";
        if(!$result = $conn->query($query))
            return -1;
        if($result->num_rows==0)
            return 0;
        $res = $result->fetch_assoc();
        $result->close();
        return $res['Id'];
    }
/////////////////////////////////MESSAGGI/////////////////////////////////
////////////////Recupera il testo e la data di pubblicazione dei messaggi scritti dallo studente passato come argomento/////////////
    function retrieveMessagesFromSN($conn,$sn)
    {
        if(!$result = $conn->query("SELECT Testo, DataPubblicazione, Files.Nome FROM Messaggio LEFT JOIN Files ON Files.Messaggio = Messaggio.Id WHERE Messaggio.Studente = '$sn'"))
            return false;
        return $result;
    }
////////////////Recupera il testo del messaggio indicato////////////////
    function retrieveTextFromId($conn,$mexId)
    {
        $conn = connection();
        if(!$stmt = $conn->query("SELECT Testo FROM Messaggio WHERE Id = '$mexId'"))
            return false;
        $result = $stmt->fetch_assoc();
        $stmt->close();
        return $result['Testo'];
    }
////////////////Recupera il numero di like ricevuti da un messaggio////////////////
    function retrieveLikesForMessage($conn,$mexId)
    {
        $query = "SELECT COUNT(*) AS Likes FROM Punti WHERE Messaggio = '$mexId'";
        if(!$stmt = $conn->query($query))
            return false;
        $result = $stmt->fetch_assoc();
        $stmt->close();
        return $result['Likes'];
    }
////////////////Recupera i messaggi del thread indicato////////////////
    function retrieveMessagesInThread($conn,$idThread)
    {
        if(!$result = $conn->query("SELECT Messaggio.Id AS MexId, Testo, DataPubblicazione, Messaggio.Studente, Files.Id, Files.Nome FROM Thread JOIN Messaggio
        LEFT JOIN Files ON Files.Messaggio = Messaggio.Id WHERE Thread.Id = Messaggio.Thread AND Thread.Id = '$idThread' ORDER BY PrimaDomanda DESC, (SELECT COUNT(*) FROM Punti WHERE Punti.Messaggio=Messaggio.Id) DESC, DataPubblicazione ASC"))
            return false;
        return $result;
    }
////////////////Controlla se il messaggio indicato sia la domanda d'apertura del thread////////////////
    function checkIfFirstQuestion($conn,$idMex)
    {
        if(!$stmt = $conn->query("SELECT PrimaDomanda FROM Messaggio WHERE Id = '$idMex'"))
            return -1;
        $res = $stmt->fetch_assoc();
        $stmt->close();
        return $res['PrimaDomanda'];
    }
/////////////////////////////////PUNTI/////////////////////////////////
////////////////Recupera i punti dello studente////////////////
    function retrievePointsFromSN($conn,$sn)
    {
        $query = "SELECT COUNT(*) AS Points, Insegnamento.Nome FROM Punti JOIN Messaggio JOIN Insegnamento
        WHERE Punti.Messaggio = Messaggio.Id AND Punti.Insegnamento = Insegnamento.Codice
        AND Messaggio.Studente = '$sn'
        GROUP BY(Insegnamento.Nome)";
        if(!$result = $conn->query($query))
            return -1;
        return $result;
    }
////////////////Conta i punti totali dell'utente////////////////
    function retrieveAllPoints($conn,$sn)
    {
        $query = "SELECT COUNT(*) AS Points FROM Punti JOIN Messaggio WHERE Messaggio.Id=Punti.Messaggio AND Messaggio.Studente='$sn'";
        if(!$stmt = $conn->query($query))
            return -1;
        $res = $stmt->fetch_assoc();
        $stmt->close();
        return $res['Points'];
    }
////////////////Controlla se l'utente ha già dato il like al messaggio////////////////
    function checkLikeAlreadyExists($conn,$user,$mex,$codInsegnamento)
    {
        if(!$stmt=$conn->query("SELECT * FROM Punti WHERE Studente = '$user' AND Messaggio='$mex' AND Insegnamento='$codInsegnamento'"))
            return -1;
        if($stmt->num_rows==0)
            return 0;
        $stmt->close();
        return 1;
    }
/////////////////////////////////FORUM/////////////////////////////////
////////////////Recupera l'id del forum dato il nome dell'insegnamento////////////////
    function retrieveForumIdFromName($conn,$nameCourse)
    {
        $query = "SELECT Id FROM Forum JOIN Insegnamento 
        WHERE Forum.CodInsegnamento=Insegnamento.Codice AND Insegnamento.Nome='$nameCourse'";
        if(!$stmt = $conn->query($query))
            return false;
        $result = $stmt->fetch_assoc();
        $stmt->close();
        return $result['Id'];
    }
/////////////////////////////////THREAD/////////////////////////////////
////////////////Recupera il titolo del thread dato l'id////////////////
    function retrieveThreadNameFromId($conn,$idThread)
    {
        if(!$stmt = $conn->query("SELECT Titolo FROM Thread WHERE Id = '$idThread'"))
            return false;
        $result = $stmt->fetch_assoc();
        $stmt->close();
        return $result['Titolo'];
    }
////////////////Recupera tutti i thread del forum indicato////////////////
    function retrieveThreadFromForum($conn,$codForum)
    {
        if(!$result = $conn->query("SELECT Id,Titolo,Domanda FROM Thread WHERE Forum = '$codForum'"))
            return false;
        return $result;
    }
////////////////Recupera lo studente creatore del thread////////////////
    function retrieveStudentFromThreadId($conn,$idThread)
    {
        if(!$stmt = $conn->query("SELECT Studente FROM Thread WHERE Id = '$idThread'"))
            return false;
        $result = $stmt->fetch_assoc();
        $stmt->close();
        return $result['Studente'];
    }
////////////////Controlla se il thread indicato è chiuso////////////////
    function checkIfClose($idThread)
    {
        $conn = connection();
        if(!$stmt=$conn->query("SELECT Chiuso FROM Thread WHERE Id = '$idThread'"))
            return -1;
        $result = $stmt->fetch_assoc();
        $stmt->close();
        return $result['Chiuso'];
    }
////////////////Controlla se il thread indicato è risolto////////////////
    function checkIfResolved($conn,$idThread)
    {
        if(!$stmt=$conn->query("SELECT Risolto FROM Thread WHERE Id = '$idThread'"))
            return -1;
        $result = $stmt->fetch_assoc();
        $stmt->close();
        return $result['Risolto'];
    }

/////////////////////////////////TAG/////////////////////////////////
////////////////Recupera gli insegnamenti, file e thread che condividono il tag indicato////////////////
    function retrieveTag($conn,$tag)
    {
        if(!$result = $conn->query("SELECT DISTINCT Insegnamento,File,Thread FROM HaTags WHERE Tag LIKE '".$tag."%' "))
            return -1;
        if($result->num_rows==0)
            return 0;
        return $result;
    }
////////////////Recupera i tag che hanno lo stesso nome o sono simili al valore indicato////////////////
    function retrieveTags($conn,$name)
    {
        $conn = connection();
        $query = "SELECT Nome FROM Tags WHERE Nome LIKE '".$name."%' ORDER BY Nome ASC LIMIT 15";
        if(!$result = $conn->query($query))
            return -1;
        return $result;
    }
?>
