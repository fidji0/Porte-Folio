<?php

namespace App\Controller;

use PHPMailer\PHPMailer\PHPMailer;

class MailerController
{
    public string $name;
    public string $email;
    public string $tel;
    public string $message;
    public string $cut;
    public string $sujet;

    function __construct()
    {
    }

    public function linkMail($name, $societe, $email)
    {
        ob_start();
        include DIR."/src/templateMail/link.php";
        $messageMail = ob_get_clean();
        //var_dump($messageMail);
        $r = $this->mailphp("Nouveau Planning", $messageMail, [$email]);

        return $r;
    }
    
    public function mailphp($subject, $message, array $to = ["contact@mavillemaboutique.fr"])
    {
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();   
            $mail->Host       = 'mail.liveproxim.fr';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'contact@liveproxim.fr';                     //SMTP username
            $mail->Password   = PASSWORDMAIL;                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;     
            

            $mail->CharSet    = "UTF-8";
            $mail->From       =  'contact@liveproxim.fr';                //L'email à afficher pour l'envoi
            $mail->FromName   = 'Live Planning';

            $mail->Subject    =  $subject;                      //Le sujet du mail
            $mail->WordWrap   = 50;                                //Nombre de caracteres pour le retour a la ligne automatique



            $mail->MsgHTML($message);                         //Le contenu au format HTML
            $mail->IsHTML(true);
            foreach ($to  as  $email) {
                $mail->AddAddress($email);
            }
            if (!$mail->send()) {
                return false;
            } else {


                return true;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}
