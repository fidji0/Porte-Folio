<?php

use App\Controller\NotifController;

if ($authenticate === false) {
    http_response_code(401);
    exit;
}
$data = $_POST;

if (isset($token )) {
    $controller = new NotifController();
    $response = $controller->updateEmployeNotif($token);

} else {
    http_response_code(400);
    echo json_encode(['result' => false, 'message' => 'Id manquant ou invalide']);
}
