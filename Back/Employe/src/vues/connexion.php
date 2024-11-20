<?php
use App\Controller\EmployeController;


/**
 * en post
 * @ sct_code code de la societe
 * @ $email email de connexion
 * @ password mot de pass de connexion
 * @ deviceId identification notif smartphone
*/
$data = $_POST;
if (isset($data['email'], $data['password'] , $data['sct_code'])) {
    $controller = new EmployeController();
    $response = $controller->connexion($data);
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['result' => false, 'message' => 'Email ou mot de passe manquant']);
}
