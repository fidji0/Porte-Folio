<?php

namespace App\Controller;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use DateTime;
use DateTimeImmutable;

class AuthenticationController extends SignUpController
{
    public $actualPasswordHash;
    public string $token;
    public string $refreshToken;
    public $dataBoutique;
    //Temps de blocage en fonction du nombre d'erreur de connexion
    public int | null $securiteTime;

    

    public function AuthUser()
    {
        $resultRequest = $this->RecupUser();

        if ($resultRequest !== false) {
            if ($this->VerifyPassword($resultRequest)) {
                if (!$this->actif) {
                    $this->setResponse(false, "compte inactif", 401);
                    return;
                }
                $this->resetError();
                $this->createToken();
                $this->createRefreshToken();
                $this->setResponse(true, "connexion ok", 200, $this->token, $this->refreshToken , $this->dataBoutique ?? null);
                return;
            } else {
                $this->errorConnexion();
                return;
            }
        }
    }

    protected function createToken()
    {
        $other = [
            "email" => $this->email,
             "id" => $this->id , 
             "role" => $this->role
        ];
        $token = new JWTController($this->pdo);
        $this->token = $token->createNewToken($this->id, time(), time() + 3600, $other);
    }
    protected function createRefreshToken()
    {
        $refreshToken = new JWTController($this->pdo);
        $this->refreshToken = $refreshToken->createRefreshToken($this->id, $this->email, $this->id);
    }

    protected function errorConnexion()
    {
        try {
            $request = "UPDATE boutique SET nbTentativeCo = nbTentativeCo + 1 WHERE   email = ?; ";
            $r = $this->pdo->prepare($request);
            $r->execute([ $this->email]);
            if ($this->RecupUser()) {
                if ($this->nbTentativeCo >= 3) {
                    $blocage = time() + 15 * 60;
                    $request = "UPDATE boutique SET securiteTime = $blocage   WHERE   email = ?; ";
                    $r = $this->pdo->prepare($request);
                    $r->execute([$this->email]);
                    $this->setResponse(false , 'votre compte est bloquer 15 minutes' , 400);
                }else{
                    $this->setResponse(false , 'Mot de passe ou identifiant incorrect' , 400);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
            $this->logError(date("d-m-Y H:i:s") . " Create Employe : " . $th, "controller");
        }
    }
    protected function resetError(){
        try {
            $request = "UPDATE boutique SET nbTentativeCo = 0 , securiteTime = 0 , connected_at = NOW() WHERE email = ?; ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->email]);
           return;
                
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create Employe : " . $th, "controller");
        }
    }
    protected function resetErrorWithId(){
        try {
            $request = "UPDATE boutique SET nbTentativeCo = 0 , securiteTime = 0 , connected_at = NOW() WHERE  id = ?; ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->id]);
           return;
                
        } catch (\Throwable $th) {
            //throw $th;
            echo 'une erreur est survenu';
        }
    }
    /**
     * 
     */
    public function RecupUser()
    {
        try {
            $request = "SELECT * FROM boutique WHERE email = ? ;";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->email]);
            $result = $r->fetch(\PDO::FETCH_ASSOC);
            $this->dataBoutique = $result;
            if ($result !== false) {
                $this->id = $result["id"];
                $this->actualPasswordHash = $result["password"];
                $this->nbTentativeCo = $result["nbTentativeCo"];
                $this->email = $result["email"];
                $this->social = $result["social"];
                $this->adress = $result["adress"];
                $this->zipCode = $result["zipCode"];
                $this->city = $result["city"];
                $this->actif = $result["actif"];
                $this->phoneNumber = $result["phoneNumber"];
                $this->accept = $result["accept"];
                $this->siret = $result["siret"];
                $this->boutiqueName = $result["boutiqueName"];
                $this->ste_code = $result["ste_code"];
                $this->securiteTime = $result["securiteTime"];
                $this->activationToken = $result["token_activation"];
                if ($this->securiteTime < time()) {
                    return true;
                }
                $this->setResponse(false , 'compte bloqué' , 401);
                return false;
            }
            $this->setResponse(false, "No result", 401);
            return false;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create Employe : " . $th, "controller");
            
            $this->setResponse(false, "connexion error", 401);
            return false;
        }
    }
    public function VerifyPassword()
    {

        return password_verify($this->password, $this->actualPasswordHash);
    }

    /**
     * activation de l'utilisateur
     */
    public function activationProfile(string $token)
    {
        if (!$this->RecupUser()) {

            return;
        }
        
        if ($token == $this->activationToken) {
            try {
                $request = "UPDATE boutique SET actif = 1 , token_activation = null WHERE email = ?";
                $r = $this->pdo->prepare($request);
                $r->execute([$this->email]);

                $this->setResponse(true, "activation ok", 200);
                return;
            } catch (\Throwable $th) {
                
                $this->logError(date("d-m-Y H:i:s") . " Create Employe : " . $th, "controller");
                $this->setResponse(false, "error", 403);
                return;
            }
        }
        
        $this->setResponse(false, "error", 403);
    }

    public function refreshToken($refreshToken)
    {   
        $JWT = new JWTController();
        $return = $JWT->refreshToken($refreshToken);

        $this->email = $return->other->email;
        $this->id = $return->other->id;

        if (!empty($this->email)) {
            $this->createToken();
            $this->setResponse(true, "", 200, $this->token);

            return true;
        }
        $this->setResponse(false, "connexion impossible", 401);
        return false;
    }
}
