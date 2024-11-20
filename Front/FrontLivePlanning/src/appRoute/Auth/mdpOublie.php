<?php

use App\Controller\Curl;

if (isset($_POST["username"])) {
    $auth = new Curl;
    $r = $auth->forgetPassword($_POST["username"]);
    if ($r == false) {
        $error = "Une erreur c'est produite";
    }
    if ($r == true) {
        $success = "Email envoyer";
    }
}
require_once DIRVUE . "/template/Auth/mdpOublie.php";
