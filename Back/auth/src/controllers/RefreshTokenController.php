<?php

namespace App\Controller;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class RefreshToken extends JWTController{
    
    public function VerifyRefreshToken($refresh_token){
        try {
            $decoded = JWT::decode($refresh_token, new Key($this->key, 'HS256'));
            
            $request = "SELECT refresh_token FROM refres_token WHERE user_id = (SELECT id FROM auth WHERE username = ?);";
            $r = $this->pdo->prepare($request);
            $r->execute([$decoded->sub]);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create Employe : " . $th, "controller");
            //throw $th;
        }
    }

    public function create2RefreshToken($subject){
        $this->setPlayload($subject , time() , time()+2592000 );
        $jwt = JWT::encode($this->playload, $this->key, 'HS256');
        try {
            $request = "INSERT INTO refresh_token (refresh_token , user_id) VALUES ( ? , (SELECT id FROM auth WHERE username = ?))";
            $r = $this->pdo->prepare($request);
            $r->execute([$jwt , $subject]);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create Employe : " . $th, "controller");
            
        }
        return $jwt;
    }
}