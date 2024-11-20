<?php

namespace App\Controller;

use DateTime;

class SignUpController extends CommonController
{

    public int $id;

    public string $password;
    public string $hashPassword;
    public string $email = '';
    public DateTime $connectedAt;
    public bool | null $actif;
    public string | null $activationToken;
    public string | null $checkOut;
    public array $role = ['user'];
    public string $social;
    public string $adress;
    public string $zipCode;
    public string $city;
    public string $phoneNumber;
    public bool $accept;
    public string $siret;
    public string $boutiqueName;
    public string $ste_code;


    public int | null $nbTentativeCo;



    /**
     * vérifie la variable email
     */
    public function VerifyData()
    {
        // Verification email
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->setResponse(false, "Format de l'email incorrect", 400);
        } else {
            // Vérification du mot de passe min 8 , 1min , 1MAJ , 1 chiffre , 1 spécial
            if (filter_var(
                $this->password,
                FILTER_VALIDATE_REGEXP,
                ["options" => ["regexp" => "^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$^"]]
            )) {
                return true;
            } else {
                $this->setResponse(false, "Format du mot de passe invalide", 400);
            }
        }

        return false;
    }
    public function verifyPasswordOk($password)
    {
        if (filter_var(
            $password,
            FILTER_VALIDATE_REGEXP,
            ["options" => ["regexp" => "^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$^"]]
        )) {
            return true;
        }
    }






    public function createNewUser(array $data)
    //verifie les données entrentes
    {
        try {
            $this->password = $data["password"];
        $this->email = $data["email"];
        $this->social = $data["social"];
        $this->adress = $data["adress"];
        $this->zipCode = $data["zipCode"];
        $this->city = $data["city"];
        $this->phoneNumber = $data["phoneNumber"];
        $this->accept = $data["accept"];
        $this->siret = $data["siret"];
        $this->boutiqueName = $data["boutiqueName"];
        $this->ste_code = $data["ste_code"];
        $this->checkOut = $data["checkOut"] ?? null;

        if ($this->VerifyData()) {

            if (!$this->VerifyUserExist()) {

                
                return $this->setResponse(false, "L'utilisateur ou l'email existe déjà", 400);
            } else {
                $this->setHashPassword($this->password);
                if ($this->insertNewUser()) {
                    $mail = new MailerController();
                    $mail->validateUser($this->checkOut, $this->activationToken, $this->email);
                    return $this->setResponse(true, $this->id, 201, null);
                }
            }
        }else{
            return $this->response;
        }
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " create Boutique  : " . $th, "controller");
            return  $this->setResponse(false, "Une erreur c'est produite", 500, null);        }
        
    }
    /**
     * récupère en base si un utilisateur a le meme identifiant.
     */
    public function VerifyUserExist()
    {
        try {
            $request = "SELECT * FROM boutique WHERE email = ?";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->email]);
            $result = $r->fetchAll();
            if (count($result) > 0) {
                return false;
            }
            return true;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Verify exist : " . $th, "controller");
            return false;
        }
    }

    public function insertNewUser()
    {
        $this->activationToken = uniqid() . uniqid() . uniqid();
        try {
            $request = "INSERT INTO boutique ( password , email , token_activation ,social , role , boutiqueName , siret , adress , zipCode, city, phoneNumber , accept , ste_code) VALUES (? ,? ,? , ? , ? ,?,? ,? ,? , ? , ? ,? ,?)";
            $r = $this->pdo->prepare($request);
            $r->execute([
                $this->hashPassword,
                $this->email,
                $this->activationToken,
                $this->social,
                json_encode($this->role),
                $this->boutiqueName,
                $this->siret,
                $this->adress,
                $this->zipCode,
                $this->city,
                $this->phoneNumber,
                $this->accept,
                $this->ste_code
            ]);
            $this->id = $this->pdo->lastInsertId();
            return true;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create Employe : " . $th, "controller");
            $this->setResponse(true, "Une erreur c'est produite", 500, null);
            return false;
        }
    }


    /**
     * Set the value of hashPassword
     *
     * @return  self
     */
    public function setHashPassword($hashPassword)
    {
        $this->hashPassword = password_hash($hashPassword, PASSWORD_BCRYPT);
        return $this;
    }
}
