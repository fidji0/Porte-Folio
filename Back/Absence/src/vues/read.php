<?php

use App\Controller\AbsenceController;

if ($authenticate === false) {
    http_response_code(401);
    exit;
}

// Récupérez les données GET
$data = $_GET;
if (isset($token) && !empty($data["boutique_id"]) && !empty($data["id"])) {
    $controller = new AbsenceController();
    $response = $controller->readAbsence($data, $token);
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['result' => false, 'message' => 'Données manquantes ou invalides']);
}
