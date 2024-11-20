<?php

use App\Controller\JWTController;
use App\Controller\ReplacePassword;




if (!empty($_POST) && isset($_POST['email'] , $_POST['checkOut'])) {
    $tests = new ReplacePassword();
    $tests->createCodeForgetPassword($_POST['email'] , $_POST['checkOut'] );
    echo json_encode($tests->response);
}else{
    http_response_code(400);
}
