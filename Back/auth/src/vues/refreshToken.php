<?php


use App\Controller\AuthenticationController;

if (!empty($_POST) && isset($_POST['refresh_token'])) {
    $auth = new AuthenticationController();
    if($auth->refreshToken($_POST['refresh_token']) === false){
        http_response_code(401);
        return;
    }
    echo json_encode($auth->response);
   
    //echo json_encode($auth->response);
}else{
    http_response_code(400);
}






