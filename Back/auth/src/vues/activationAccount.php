<?php

use App\Controller\Authentication;
use App\Controller\AuthenticationController;

if (!empty($_GET) && isset($_GET["email"] , $_GET["token"])) {
    $auth = new AuthenticationController();
    $auth->email = $_GET["email"];

    $auth->activationProfile($_GET["token"]);
    echo json_encode($auth->response);
    return;
}
http_response_code(400);