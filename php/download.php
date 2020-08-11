<?php
session_start();
require_once('util/retrieve.php');
require_once('util/connection_config.php');
require_once('util/utilError.php');
require_once('util/connection.php');
?>
<?php
if (isset($_SESSION['email'])) {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $conn = connection();
        if (!$userId = retrieveAuthorFromIdFile($conn,$id))
            internalError("Impossibile recuperare l'autore del file", "download");

        if (!$fileName = retrieveFileNameFromId($conn,$id))
            internalError("Impossibile recuperare il nome del file", "download");

        $path = UPLOAD . "_" . $userId . "_" . $fileName;
        if (file_exists($path)) {
            $size = filesize($path);
            $type = "application/pdf";
            $data = fread(fopen($path, "rb"), $size);
            header("Content-Disposition: inline; filename=$fileName");
            header("Content-type: $type");
            header("Content-length: $size");
            print $data;
        }
        $conn->close();
    }
} else {
    echo "Effettua il login per avere accesso alle informazioni";
}
