<?php

use App\Controller\Authentication;


if (!empty($_POST) && isset($_POST['password']) && isset($_POST['email'])) {
    $auth = new Authentication($pdo);
    
    $auth->email =  $_POST['email'] ;
    $auth->password = $_POST['password'];
    $auth->AuthUser();
    echo json_encode($auth->response);
   
    //echo json_encode($auth->response);
}else{
    http_response_code(400);
}