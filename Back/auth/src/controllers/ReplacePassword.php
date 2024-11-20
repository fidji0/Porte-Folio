<?php

namespace App\Controller;

use App\Controller\Mailer;

class ReplacePassword extends AuthenticationController
{

    public $newPassword;
    public $code;

    public function resetPAsswordWhithConnexionOk( string $oldPassword, string $newPassword)
    {
        $this->newPassword = $newPassword;

        if (!$this->verifyPasswordOk($newPassword)) {
            $this->setResponse(false, "Mot de passe non conforme", 400);
            return false;
        } else {
            $this->setHashPassword($this->newPassword);
            $object = $this->RecupUser();
            if ($object !== false) {

                $this->password = $oldPassword;
                if (!$this->VerifyPassword($object)) {
                    $this->setResponse(false, "Le Mot de passe ne correspond pas", 400);
                    return false;
                } else {

                    if ($this->updatePassword()) {
                        $this->setResponse(true, "Mot de passe modifié avec succès", 200);
                    } else {
                        $this->setResponse(false, "Une erreur c'est produite", 500);
                    }
                }
            }
        }
    }
    public function updatePassword()
    {
        try {
            $request = "UPDATE boutique SET password = ? WHERE id = ? ;";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->hashPassword, $this->id]);
            return true;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create Employe : " . $th, "controller");
            $this->setResponse(false, "impossible de modifier le mot de passe", 400);
            return false;
        }
    }

    public function ChangeForgetPassword($id, $newPassword, $code)
    {
        $this->id = $id;
        $this->code = $code;
        $this->newPassword = $newPassword;
        $this->setHashPassword($this->newPassword);


        if (!$this->verifyCodeForgetPassword($code)) {
            $this->setResponse(false, "Le code de reinitialisation n'est pas valide", 400);
            return false;
        } else {
            if (!$this->verifyPasswordOk($newPassword)) {
                $this->setResponse(false, "Mot de passe non conforme", 400);
                return false;
            } else {
                if (!$this->updatePassword()) {
                    $this->setResponse(false, "Une erreur c'est produite lors du changement de mot de passe", 400);
                    return false;
                } else {
                    $this->setResponse(true, "Mot de passe changer avec succès", 200);
                    $this->resetErrorWithId();
                    $this->updateValidyCode($code);
                    return true;
                }
            }
        }
    }
    /**
     * update validity code reset password
     */
    public function updateValidyCode($code)
    {
        try {
            $request = "UPDATE forget_password SET validity = false WHERE code = ?";
            $r = $this->pdo->prepare($request);
            $r->execute([$code]);
            return true;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create Employe : " . $th, "controller");
            return false;
        }
    }
    public function getIdUser($identifier)
    {
        try {
            $request = "SELECT id , email FROM boutique WHERE email = ?";
            $r = $this->pdo->prepare($request);
            $r->execute([$identifier]);
            $result = $r->fetch(\PDO::FETCH_OBJ);
            if ($result !== false) {
                $this->id = $result->id;
                $this->email = $result->email;
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create Employe : " . $th, "controller");
            return false;
        }
    }



    public function createCodeForgetPassword($identifier, $checkOut)
    {
        // Récupère l'utilisateur

        if (!$this->getIdUser($identifier)) {
            $this->setResponse(false, "L'utilisateur n'a pas été retrouvé", 400);
        } else {

            if (!$this->verifyCodeAlreadyExist()) {

                $mail = new MailerController(true);
                $base64 = base64_encode(json_encode(["code" => $this->code, "id" => $this->id]));

                $mail->forgetPasswordMail($checkOut, $base64, $this->email);
                $this->setResponse(true, "email renvoyer", 200);


    
            } else {
                $code =  uniqid() . uniqid();
                $base64 = base64_encode(json_encode(["code" => $code, "id" => $this->id]));
                // Insere en base le code et le timestamp Validité de 20 minutes

                try {
                    $request = "INSERT INTO forget_password (code , user_id , timeValidity) VALUES (? , ? , ?);";
                    $r = $this->pdo->prepare($request);
                    $r->execute([$code, $this->id, time() + 1200]);

                    // envoi un mail avec le code
                    $mail = new MailerController(true);
                    $mail->forgetPasswordMail($checkOut, $base64, $this->email);
                    $this->setResponse(true, 'email envoyer', 200);
                } catch (\Throwable $th) {
                    $this->logError(date("d-m-Y H:i:s") . " Create Employe : " . $th, "controller");

                    $this->setResponse(false, "Une erreur c'est produite", 400);
                }
            }


            // a compléter
        }
    }


    /**
     * Verifie le code de l'url et l'id qui y est associé ainsi que la validité
     */
    public function verifyCodeForgetPassword($code)
    {
        try {
            $request = "SELECT f.* , a.email FROM forget_password f INNER JOIN boutique a ON a.id = f.user_id WHERE f.code = ? AND user_id = ?;";
            $r = $this->pdo->prepare($request);
            $r->execute([$code, $this->id]);
            $return = $r->fetch(\PDO::FETCH_OBJ);
            if ($return !== false && time() < $return->timeValidity) {
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create Employe : " . $th, "controller");
            return false;
        }
    }




    public function verifyCodeAlreadyExist()
    {
        try {
            $request = "SELECT * FROM forget_password WHERE user_id = ? AND timeValidity > ?";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->id, time()]);
            $result = $r->fetch(\PDO::FETCH_OBJ);

            if ($result === false) {
                return true;
            }
            $this->code = $result->code;

            return false;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create Employe : " . $th, "controller");
            return false;
        }
    }
}
