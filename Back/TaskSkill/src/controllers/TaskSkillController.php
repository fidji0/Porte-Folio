<?php

namespace App\Controller;

use App\Class\TaskSkillClass;

class TaskSkillController extends CommonController
{

    public function createTaskSkill(array $data, $token)
    {
        try {
            if (
                empty($data['boutique_id']) ||
                empty($data['skill_id']) ||
                empty($data['task_id']) 
            ) {
                return $this->setResponse(false, "Donnée manquante", 401);
            }

            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }
            

        
            $event = new TaskSkillClass();
            $event->setSkill_id($data['skill_id']);
            $event->setTask_id($data['task_id']);

            return $event->createNewTaskSkills();
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }
    



    public function readTaskSkill(array $data, $token)
    {
        try {

            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }

            $event = new TaskSkillClass();
            $event->setSkill_id($data["skill_id"]);
            $event->setTask_id($data["task_id"]);
            $result = $event->readTaskSkills();
            if ($result) {
                return $result;
            }
            if ($result == false) {
                return [];
            }

            return $this->setResponse(false, "Aucune donnée trouvée", 404);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }


    public function deleteTaskSkill(array $data, $token)
    {
        try {
            if (
                empty($data['boutique_id']) ||
                empty($data['task_id']) ||
                empty($data['skill_id'])
            ) {
                return $this->setResponse(false, "Id Manquant", 401);
            }

            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }

            $event = new TaskSkillClass();
            $event->setTask_id($data["task_id"]);
            $event->setskill_id($data["skill_id"]);
            $count = $event->deleteTaskSkills();
            if ($count > 0) {
                return $this->setResponse(true, "Suppression réussie", 200);
            }
            if ($count == 0) {
                return $this->setResponse(true, "Element introuvable", 400);
            }
            return $this->setResponse(false, "Une erreur s'est produite", 500);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }
    

    
}
