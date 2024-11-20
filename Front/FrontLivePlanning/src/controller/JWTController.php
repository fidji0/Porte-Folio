<?php

namespace App\Controller;

use Exception;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;

class JWTController
{
    public $response;
    protected $key;
    public array $playload;
    public $decodedToken;
    public $refreshToken;
    protected object $pdo;


    public function __construct()
    {
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

    public function decodeJwt($jwt)
    {
        $decoded = JWT::decode($jwt, new Key($this->key, 'HS256'));
        $this->decodedToken = $decoded;
        return $decoded;
    }

    public function verifyToken($tokenRefresh = null)
    {
        try {
            $this->decodeJwt($tokenRefresh);
            if (!isset($_COOKIE["user_data"])) {
                $cookie = new Curl();
                $cookie->createCookieConnexion($_SESSION);
            }

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
        file_put_contents(DIR . "/error/fichier.txt", $th . "\n", FILE_APPEND);
        return false;
    }

    public function setResponse(bool $response, string $message, int $code)
    {
        $this->response = [
            "response" => $response,
            "message" => $message,
            "code" => $code
        ];
        http_response_code($code);
    }

    public function VerifyRefreshToken($refresh_token)
    {
        try {
            $decoded = JWT::decode($refresh_token, new Key($this->key, 'HS256'));

            $request = "SELECT refresh_token FROM refresh_token WHERE user_id = (SELECT id FROM auth WHERE username = ?) AND refresh_token = ?;";
            $r = $this->pdo->prepare($request);
            $r->execute([$decoded->sub, $refresh_token]);
            $result = $r->fetch(\PDO::FETCH_OBJ);
            if ($result !== false) {
                if ($result->refresh_token !== false && $result->refresh_token == $refresh_token) {

                    return true;
                }
            }

            return false;
        } catch (\Throwable $th) {
            //throw $th;

            return false;
        }
    }



    public function refreshToken($refreshToken)
    {

        if ($this->verifyToken($refreshToken)) {
            if ($this->VerifyRefreshToken($refreshToken)) {

                return ($this->decodeJwt($refreshToken));
            }
        }

        return false;
    }
    public function verifyConnexion($token, $refreshToken)
    {

        if ($this->verifyToken($token) === true) {

            return true;
        }
        if ($this->verifyToken($refreshToken) === true) {
            $curl = new Curl();
            if ($curl->refreshConnexion($refreshToken) === true) {
                return true;
            }
        }

        session_unset();
        session_destroy();
        setcookie('user_data', '', time() - 3600, '/');
        return false;
    }
}
