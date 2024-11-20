<?php

use App\Controller\Curl;
use App\Controller\JWTController;

ini_set('session.cookie_secure', true);
ini_set('session.cookie_httponly', true);


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization , X-AUTH-TOKEN , Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS ,  PUT, DELETE");
header("Allow: GET, POST , PUT, DELETE");
header("Access-Control-Allow-Credentials: true ");

session_start();
try {
    require_once __DIR__ . "/../constanteDefined.php";
    require __DIR__ . "/../vendor/autoload.php";

    //Vérifie si connecter
    $connected = false;
    $connexion = new Curl;
    if (isset($_SESSION, $_SESSION["token"]) && !empty($_SESSION["token"])) {
        $jwt = new JWTController();
        $connected = $jwt->verifyConnexion($_SESSION["token"], $_SESSION["refresh_token"]);
    } elseif ($connexion->decodeCookieConnexion()) {
        $jwt = new JWTController();
        $connected = $jwt->verifyConnexion($_SESSION["token"], $_SESSION["refresh_token"]);
    }




    $router = new AltoRouter();
    require "./route.php";

    $match = $router->match();


    if (is_array($match)) {

        if (is_callable($match['target'])) {
            call_user_func_array($match['target'], $match['params']);
        } else {
            $params = $match['params'];
            require DIR . "/src/appRoute/{$match['target']}.php";
        }
    } else {

        http_response_code(404);
    }
} catch (\Throwable $th) {
    //echo $th;
    file_put_contents(DIR . "/error/fichier.log", date("d-m-Y H:i:s") .  $th . "\n", FILE_APPEND);
}
