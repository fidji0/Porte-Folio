<?php

use App\Controller\JWTController;
use App\Controller\ReplacePassword;

if (!empty($_POST)  && isset($_POST['password'] , $_POST['uniqid'] , $_POST['id'] )) {
    $tests = new ReplacePassword();
    if (strpos($_POST['password'] , ' ') === false) {
        $tests->ChangeForgetPassword($_POST['id'] ,$_POST['password'] , $_POST['uniqid'] );
    }else{
        $tests->setResponse(false , 'Mot de passe invalide', 400);
    }
    
    echo json_encode($tests->response);
} else {
    http_response_code(400);
}

//echo json_encode($tests->response);
