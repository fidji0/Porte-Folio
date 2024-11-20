<?php

namespace App\Controller;

use PHPMailer\PHPMailer\PHPMailer;

class MailerController extends CommonController
{
    public string $MailName;
    public string $Email;
    public string $tel;
    public string $message;
    public string $cut;
    public string $sujet;

    public function sendMailModif($objet , $message , $email , $from ){

        try {
            ob_start();
            include DIR."/src/templateEmail/tempMail.php";
            $messageMail = ob_get_clean();
            $this->mailphp($objet , $messageMail , [$email] , $from);
        } catch (\Throwable $th) {
            $this->setResponse(false , "erreur" , 500);
            $this->logError(date("d-m-Y H:i:s ") . $th , "mail");
            return false;
        }
        
        

    }
    public function readBoutiqueDataForMail($boutiqueId){
        try {
            $request = "SELECT name , contactMail FROM boutique  WHERE id = ?";
            $r = $this->pdo->prepare($request);
            $r->execute([$boutiqueId]);
            $result = $r->fetch(\PDO::FETCH_ASSOC);
            $this->MailName = $result["name"] ?? null;
            $this->Email = $result["contactMail"] ?? null;
            return true;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s ")  . $th ,'mail');
            return false;
        }
    }
    
    
    public function mailphp($subject, $message, array $to = ["contact@mavillemaboutique.fr"] , $from )
    {
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'mail.liveproxim.fr';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'contact@liveproxim.fr';                     //SMTP username
            $mail->Password   = PASSWORDMAIL;                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;      
            $mail->addReplyTo("no_reply@liveproxim.fr");                           //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            $mail->CharSet    = "UTF-8";
            $mail->From       =  'contact@mavillemaboutique.fr';                //L'email à afficher pour l'envoi
            $mail->FromName   = $from;

            $mail->Subject    =  $subject;                      //Le sujet du mail
            $mail->WordWrap   = 50;                             
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
            
            $this->logError(date("d-m-Y H:i:s") . " Mail Error : " . $e, "class");

            return false;
        }
    }
}
