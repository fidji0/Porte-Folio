<?php

use App\Controller\Curl;

if (isset($_POST, $_POST["password"], $_GET["token"], $_POST["copyPassword"]) && $_POST["password"] === $_POST["copyPassword"]) {
    if (isset(
        json_decode(base64_decode($_GET["token"]), true)['id'],
        json_decode(base64_decode($_GET["token"]), true)['code']
    )) {
        $curl = new Curl;
        $data = [
            'password' => $_POST["password"],
            'uniqid' => json_decode(base64_decode($_GET["token"]), true)['code'],
            'id' => json_decode(base64_decode($_GET["token"]), true)['id']
        ];
        $result = $curl->commonCurlPOST($data, URLAUTH."/changePasswordForget", 200);
        if ($result == true) {
            $success = "Changement de mot de passe réussi";
        }else {
            $error = "Une erreur c'est produite";
        }
        
    }
}


require_once DIRVUE . "/template/Auth/reinsMdp.php";
