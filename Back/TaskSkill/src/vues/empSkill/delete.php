<?php

use App\Controller\EmpSkillController;
use App\Controller\TaskSkillController;

if ($authenticate === false) {
    http_response_code(401);
    exit;
}

$data = $_GET;

if (isset($token, $data['employe_id'], $data['skill_id']  , $data['boutique_id'])) {
    $controller = new EmpSkillController();
    $response = $controller->deleteEmpSkill($data, $token);
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['result' => false, 'message' => 'Id manquant ou invalide']);
}
