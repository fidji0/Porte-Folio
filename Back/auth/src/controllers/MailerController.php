<?php

namespace App\Controller;

use PHPMailer\PHPMailer\PHPMailer;

class MailerController extends CommonController
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

    public function validateUser($checkOut, $token, $email)
    {
        ob_start();
        include DIR."/src/templateEmail/validationInscription.php";
        $messageMail = ob_get_clean();
        //var_dump($messageMail);
        $r = $this->mailphp("Activation de votre compte", $messageMail, [$email]);

        return $r;
    }
    public function forgetPasswordMail($checkOut, $code, $email)
    {
        ob_start();
        include DIR."/src/templateEmail/forgetPasswordMail.php";
        $messageMail = ob_get_clean();

        //var_dump($messageMail);
        return $this->mailphp("Mot de passe oublié", $messageMail, [$email]);
    }
    public function getform()
    {
        if (!empty($_POST)) {

            ob_start();
            $name = $this->name;
            $email = $this->email;
            $message = $this->message;
            $sujet = $this->sujet;
            $tel = $this->tel;

            include "../../templateEMail/MailContact.php";
            $messageMail = ob_get_clean();
            /* $subject = "$this->name formulaire de contact";
                $messageMail = " <h1>Réponse formulaire de contact</h1>
                <h2>$this->name</h2> 
                <h2>$this->sujet</h2> 
                <p>$this->message</p> 
                <p>condition d'utilisation des donnée $this->cut</p>";*/


            return $this->mailphp("réponse formulaire de $name", $messageMail);
        }
    }


    public function testForm()
    {
        return true;
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
                $this->logError(date("d-m-Y H:i:s") . " Create Employe : " . $mail->ErrorInfo, "controller");
                return false;
            } else {


                return true;
            }
        } catch (\Exception $e) {
            $this->logError(date("d-m-Y H:i:s") . " Create Employe : " . $mail->ErrorInfo, "controller");
            return false;
        }
    }
}
