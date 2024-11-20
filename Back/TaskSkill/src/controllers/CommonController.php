<?php

namespace App\Controller;

use App\Controller\JWTController;
use PDO;

class CommonController
{
    public PDO $pdo;
    public array $response;
    protected object $decodedJWT;
    protected string | int $boutique_ids;
    protected int $user_id;

    public function __construct()
    {

        try {
            if (ENV === "prod") {
                $this->pdo = new \PDO('mysql:host=109.234.164.215;dbname=cytp0194_livePlanning;charset=utf8mb4', USER, PASSWORD);
            } else {
                $this->pdo = new \PDO('mysql:host=109.234.164.215;dbname=cytp0194_livePlanningTest;charset=utf8mb4', USER, PASSWORD);
            }
        } catch (\PDOException $th) {
            $this->logError(date("d-m-Y H:i:s") . " Connexion : " . $th , "controller");
            $this->setResponse(false, "une erreur c'est produite lors de la connexion à la base de donnée", 500);
            return;
        }
    }
    /**
     * verifie l'autorisation d'acces a la modification
     * @param $token token authentification
     * @param $boutique_id id de la boutique a controler
     */
    public function verifyAuthorization($token, $boutique_id): bool
    {
        $this->decodeToken($token);
        
        try {
            if ($this->user_id == $boutique_id) {
                return true;          
            }
            return false;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Authorization : " . $th , "controller");
            return false;
        }
    }

    public function verifyEmployeAuthorization($token): bool
    {
        $this->decodeEmployeToken($token);

        try {
            $request = "SELECT id FROM employe WHERE id = ?";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->user_id]);
            if (!$r->fetch(\PDO::FETCH_ASSOC)) {
                return false;
            }
            return true;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Authorization : " . $th , "controller");
            return false;
        }
    }



    protected function decodeToken($token)
    {

        $decodeToken = new JWTController();
        $decodeToken -> jwt = $token;
        $this->decodedJWT = $decodeToken->decodeJWT();
        $this->user_id = $this->decodedJWT->other->id;
    }
    protected function decodeEmployeToken($token)
    {

        $decodeToken = new JWTController();
        $decodeToken -> jwt = $token;
        $this->decodedJWT = $decodeToken->decodeJWT();
        $this->user_id = $this->decodedJWT->sub;
        $this->boutique_ids = $this->decodedJWT->other->boutique_id;
    }

    /**
     * Set the value of response
     * @param bool $response boolean qui donne le résultat de la requete
     * @param string $message message a envoyer avec la réponse
     * @param int $codeHttp code http de reponse 
     * @return  self
     */
    public function setResponse(bool $response, string $message, int $codeHttp)
    {
        $this->response = ["result" => $response, "message" => $message];
        http_response_code($codeHttp);
        return $this->response;
    }


    protected function logError(string $error , string $fichierName)
    {
        file_put_contents(DIR . "/error/$fichierName.log", $error . "\n", FILE_APPEND);
    }

    public function verifyEmployeBoutique($employe_id , $boutique_id){
        try {
            $request = "SELECT id FROM employe WHERE id = ? AND boutique_id = ?";
            $r = $this->pdo->prepare($request);
            $r->execute([$employe_id, (int) $boutique_id]);
            if (!$r->fetch(\PDO::FETCH_ASSOC)) {
                return false;
            }
            return true;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " verify employeBoutique : " . $th , "controller");
            return false;
        }
    }
}
