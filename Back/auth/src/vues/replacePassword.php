<?php

use App\Controller\JWTController;
use App\Controller\ReplacePassword;

$token = new JWTController($pdo);
if (!$token-> verifyToken()) {
    echo json_encode($token->response);
    exit;
}else {   
    if (!empty($_POST)  && isset($_POST['oldPassword']) && isset($_POST['newPassword'])) {
        $tests = new ReplacePassword($pdo);
        $tests->resetPAsswordWhithConnexionOk($token->decodedToken->sub , $_POST['oldPassword'] , $_POST['newPassword']);
        echo json_encode($tests->response);
    }else {
        http_response_code(400);
    }

//echo json_encode($tests->response);





}
