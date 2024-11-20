<?php

use App\Controller\JWTController;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization , X-AUTH-TOKEN , Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS ,  PUT, DELETE");
header("Allow: GET, POST , PUT, DELETE");
header("Access-Control-Allow-Credentials: true ");
header('Content-type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}
require __DIR__."/../constante.php";
// récupération de l'envoie des données en json
if ($json = file_get_contents('php://input'))  {
    $data = json_decode($json , true);
    if ($data) {
        foreach ($data as $key => $value) {
            $_POST[$key] = $value;
        }
    }
}






require "../vendor/autoload.php";
$authenticate = false;
if (isset(apache_request_headers()['Authorization'])) {
    $token = (apache_request_headers()['Authorization']);
    
    if ($token) {
        $jwtControl = new JWTController();
        $jwtControl->jwt = $token;
        if ($jwtControl->verifyToken()) {
            $authenticate = true;
        }
    }
}

$router = new AltoRouter();
require "./route.php";

$match = $router->match();


if (is_array($match)) {

    if (is_callable($match['target'])) {
        call_user_func_array($match['target'], $match['params']);
    } else {
        $params = $match['params'];
        require "../src/vues/{$match['target']}.php";
    }
} else {
    http_response_code(404);
}
