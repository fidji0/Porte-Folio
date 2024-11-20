<?php

namespace App\Controller;

use App\Controller\CommonController;
use Exception;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Throwable;

class JWTController extends CommonController
{
    private $key;
    public $jwt;
    public array $playload;
    public $decodedToken;
    public $refreshToken;



    public function __construct()
    {
        parent::__construct();
        $this->key = file_get_contents(__DIR__ . "/../../jwt/private.pem");
        
    }
    public function decodeJwt()
    {
        if ($this->jwt) {
            $jwt = str_replace("Bearer ", "", $this->jwt);
            $decoded = JWT::decode($jwt, new Key($this->key, 'HS256'));
            $this->decodedToken = $decoded;
            return $decoded;
        }
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

    public function createNewToken(string $subject, int $iat, $exp, array $other = null)
    {
        // $refreshToken = new RefreshToken($this->pdo);
        // $refreshToken->createRefreshToken($subject);
        $this->setPlayload($subject, $iat, $exp, $other);
        $jwt = JWT::encode($this->playload, $this->key, 'HS256');
        return $jwt;
    }

    public function verifyToken($tokenRefresh = null)
    {
        try {


            if ($this->jwt) {
                $jwt = str_replace("Bearer ", "", $this->jwt);
            }

            $decoded = JWT::decode($jwt, new Key($this->key, 'HS256'));
            $this->decodedToken = $decoded;


            return true;
        } catch (ExpiredException $th) {

            $this->setResponse(false, "Le token est expiré", 401);
            $this->logError(date("d-m-Y H:i:s") . " Token : " . $th, "token");
        } catch (BeforeValidException $th) {
            $this->setResponse(false, "Le token n'est pas encore valide", 401);
            $this->logError(date("d-m-Y H:i:s") . " token : " . $th, "token");
        } catch (SignatureInvalidException $th) {
            $this->setResponse(false, "La signature du token est invalide", 401);
            $this->logError(date("d-m-Y H:i:s") . " token : " . $th, "token");
        } catch (Exception $th) {
            $this->logError(date("d-m-Y H:i:s") . " token : " . $th, "token");

            $this->setResponse(false, "Token invalide", 401);
        } catch (Throwable $th) {
        }
        $this->logError(date("d-m-Y H:i:s") . " token : " . $th, "token");

        return false;
    }
}
