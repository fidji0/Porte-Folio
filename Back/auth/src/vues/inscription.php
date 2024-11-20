<?php

use App\Controller\SignUpController;

$json = file_get_contents('php://input');
$data = json_decode($json);
//var_dump($data->username);

$data = $_POST;
if (!empty($_POST)  && isset(
    $data["email"],
    $data["password"],
    $data["social"],
    $data["boutiqueName"],
    $data["ste_code"],
    $data["siret"],
    $data["adress"],
    $data["zipCode"],
    $data["city"],
    $data["phoneNumber"],
    $data["ste_code"],
    $data["accept"]
)) {
    $signup = new SignUpController();
    if (strpos($_POST['password'], ' ') === false  && strpos($_POST['email'], ' ') === false) {        
        $return = $signup->createNewUser($_POST);
        
    }else{

       echo json_encode($signup->setResponse(false , 'Mot de passe ou identifiant invalide' , 400));
    }
    
    echo json_encode($return);
} else {
    http_response_code(400);
    echo json_encode([
        "result" => false , "message" => "Données manquantes"
    ]);
   
}

//$date = new DateTimeImmutable() ;
//echo $date->format('Y-m-d H:i:s');