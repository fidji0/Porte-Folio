<?php

namespace App\Controller;

use Exception;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;

class JWTController extends CommonController
{

    protected $key;
    public array $playload;
    public $decodedToken;
    public $refreshToken;
    public $jwt;



    public function __construct()
    {
        parent::__construct();
        $this->key = file_get_contents("./../jwt/private.pem");
        
    }

    public function createNewToken(string $subject, int $iat, $exp, array $other = null)
    {
        // $refreshToken = new RefreshToken($this->pdo);
        // $refreshToken->createRefreshToken($subject);
        $this->setPlayload($subject, $iat, $exp, $other);
        $jwt = JWT::encode($this->playload, $this->key, 'HS256');
        return $jwt;
    }

    public function setPlayload(string $subject, int $iat, $exp, array $other = null)
    {
        $this->playload = [
            'iat' => $iat,
            'exp' => $exp,
            'sub' => $subject,
            'other' => $other
        ];
    }

    public function decodeJwt($jwt = null)
    {
        if ($jwt === null) {
            $jwt = str_replace("Bearer ", "", apache_request_headers()["Authorization"]);
        }
        $decoded = JWT::decode($jwt, new Key($this->key, 'HS256'));
        $this->decodedToken = $decoded;
        return $decoded;
    }

    public function verifyToken($tokenRefresh = null)
    {
        try {
            $this->decodeJwt($tokenRefresh);


            return true;
        } catch (ExpiredException $th) {

            $this->setResponse(false, "Le token est expiré", 401);
        } catch (BeforeValidException $th) {
            $this->setResponse(false, "Le token n'est pas encore valide", 401);
        } catch (SignatureInvalidException $th) {
            $this->setResponse(false, "La signature du token est invalide", 401);
        } catch (Exception $th) {
            $this->setResponse(false, "Token invalide", 401);
        }
        $this->logError(date("d-m-Y H:i:s") . " Create Employe : " . $th, "controller");
        return false;
    }

    

    public function VerifyRefreshToken($refresh_token)
    {
        try {
            $decoded = JWT::decode($refresh_token, new Key($this->key, 'HS256'));
            
            $request = "SELECT refresh_token FROM refresh_token WHERE user_id = (SELECT id FROM boutique WHERE id = ?) AND refresh_token = ?;";
            $r = $this->pdo->prepare($request);
            $r->execute([$decoded->sub , $refresh_token]);
            $result = $r->fetch(\PDO::FETCH_OBJ);
            if ($result !== false) {
                if ($result->refresh_token !== false && $result->refresh_token == $refresh_token) {
                    
                return true;
             }
            }
            
            return false;
        } catch (\Throwable $th) {
            //throw $th;
            $this->logError(date("d-m-Y H:i:s") . " Create Employe : " . $th, "controller");
            return false;
        }
    }

    public function createRefreshToken($subject , $email , $id)
    {
        $this->setPlayload($subject, time(), time() + 2592000 , ["email" => $email , "id" => $id] );
        $jwt = JWT::encode($this->playload, $this->key, 'HS256');
        try {
            $request = "INSERT INTO refresh_token (refresh_token , user_id ) VALUES ( ? , (SELECT id FROM boutique WHERE id = ?)  )";
            $r = $this->pdo->prepare($request);
            $r->execute([$jwt, $subject]);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create Employe : " . $th, "controller");
        }
        return $jwt;
    }

    public function refreshToken($refreshToken){

        if ($this->verifyToken($refreshToken)) {
            if ($this->VerifyRefreshToken($refreshToken)) {

                return ($this->decodeJwt($refreshToken));
                
            }
        } 
        
        return false;
    }
}
