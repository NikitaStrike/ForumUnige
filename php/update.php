<?php
    session_start();
    require_once('util/testInput.php');
    require_once('util/adminFunction.php');
    require_once('util/utilError.php');
    require_once('util/retrieve.php');
    require_once('util/connection.php');
    if(isset($_SESSION['email']))
    {
        $email = $_SESSION['email'];
        $conn = connection();
        if(isset($_POST['username']) && !empty($_POST['username']))
        {
            $user = $_POST['username'];
            if(!testUsername($user))
                errorSite("Lo username può contenere solo caratteri alfanumerici","show_profile");

            if(!updateUsername($conn,$email,$user))
                errorSite("Impossibile aggiornare l'username","show_profile");
            $_SESSION['username'] = $user;
            noError("Username aggiornato correttamente","show_profile"); 
        }
        if(isset($_POST['password']))
        {
            if(!empty($_POST['password']) && !empty($_POST['passwordrepeat']))
            {
                if(!checkDoublePwd($_POST['password'],$_POST['passwordrepeat']))
                    errorSite("Le password non coincidono","show_profile");

                $pwd = hash('sha256',$_POST['password']);
                if(!updatePassword($conn,$email,$pwd))
                    errorSite("Impossibile aggiornare le password","show_profile");
                noError("Password aggiornata correttamente", "show_profile");
            }
        }
        $conn->close();
    }
    else
        echo("Utente non registrato");
