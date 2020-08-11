<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once('/chroot/home/S4502621/public_html/PHPMailer/src/PHPMailer.php');
require_once('/chroot/home/S4502621/public_html/PHPMailer/src/Exception.php');
require_once('/chroot/home/S4502621/public_html/PHPMailer/src/SMTP.php');
require_once('retrieve.php');

function send_mail($utente, $forum, $thread)
{
    $emails = retrieveStudents($forum);
    if(!is_int($emails))
    {
        while($email = $emails->fetch_assoc())
        {
            $mail = new PHPMailer;
            try {
            $mail->setFrom('unige.forum@mail.com', 'UnigeForum');
            $mail->addAddress($email['Email']);
            $mail->Subject = 'Nuovo messaggio per il thread '.$thread;
            $mail->Body = $utente.' ha inserito un nuovo messaggio sul forum '.$forum;

                $mail->IsSMTP();
                $mail->Host = "smtp.mail.com";
                $mail->SMTPAuth = true;
                $mail->Username = 'unige.forum@mail.com';
                $mail->Password = 'unigeForum_20';
                $mail->Port = 587;
                $mail->SMTPSecure = "tls";
                $mail->send();
            }
            catch (\Exception $e)
            {
                echo $e->getMessage();
            }
        }
    }
}
?>