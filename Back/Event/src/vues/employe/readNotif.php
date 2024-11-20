<?php

use App\Controller\NotifController;

if ($authenticate === false) {
    http_response_code(401);
    exit;
}


if (isset($token)) {
    $controller = new NotifController();
    $response = $controller->readEmployeNotif($token);
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['result' => false, 'message' => 'Id manquant ou invalide']);
}
