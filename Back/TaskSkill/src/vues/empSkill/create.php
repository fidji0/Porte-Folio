<?php

use App\Controller\EmpSkillController;
use App\Controller\TaskSkillController;

if ($authenticate === false) {
    http_response_code(401);
    exit;
}

// Récupérez les données POST
$data = $_POST;

if (isset($token, $data['boutique_id'], $data['skill_id'], $data['employe_id'])) {
    $controller = new EmpSkillController();
    $response = $controller->createEmpSkill($data, $token);
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['result' => false, 'message' => 'Données manquantes ou invalides']);
}
