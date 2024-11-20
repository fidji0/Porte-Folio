<?php

use App\Controller\Curl;

if (isset($_POST["username"], $_POST["password"])) {
    $auth = new Curl;
    $authentification = $auth->authUser($_POST["username"], $_POST["password"]);
    if ($authentification === true) {
        echo '<script>window.location.href = "/planning";</script>';
        return;
    }
    if (isset($auth->returnServer) && strlen($auth->returnServer) > 10) {
        $return = json_decode($auth->returnServer , true);
       if (isset($return["response"]) && $return["response"] == false) {
        $error = "Votre compte est bloqué pendant 15 minutes <br> Vous pouvez changer de mot de passe pour débloquer votre compte";
       }
    }
    $error = true;
}
require_once DIRVUE . "/template/Auth/login.php";
