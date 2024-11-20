<?php

use App\Controller\AbsenceController;

if ($authenticate === false) {
    http_response_code(401);
    exit;
}


$data = $_GET;

if (isset($token) && !empty($data["boutique_id"])) {
    $controller = new AbsenceController();
    $response = $controller->readAllAbsenceByBoutique($data, $token);
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['result' => false, 'message' => 'Données manquantes ou invalides']);
}
