<?php

use App\Class\AbsenceClass;
use App\Controller\AbsenceController;

if ($authenticate === false) {
    http_response_code(401);
    exit;
}

$data = $_POST;

if (isset($token, $data['start_date'], $data['end_date'], $data['type'])) {
    $absStart = new DateTime($data['start_date']);
    $absEnd = new DateTime($data['end_date']);
    if ($absStart >= $absEnd) {
        http_response_code(400);
        echo json_encode(["result" => false , "message" => "Erreur de remplissage"]);
        exit();
    }
    $controller = new AbsenceController();
    $response = $controller->createEmployeAbsence($data, $token);
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['result' => false, 'message' => 'Données manquantes ou invalides']);
}
