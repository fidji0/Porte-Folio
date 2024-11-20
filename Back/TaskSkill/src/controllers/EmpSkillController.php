<?php

namespace App\Controller;

use App\Class\EmpSkillClass;

class EmpSkillController extends CommonController
{

    public function createEmpSkill(array $data, $token)
    {
        try {
            if (
                empty($data['boutique_id']) ||
                empty($data['skill_id']) ||
                empty($data['employe_id']) 
            ) {
                return $this->setResponse(false, "Donnée manquante", 401);
            }

            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }
            if (!$this->verifyEmployeBoutique($data['employe_id'], $data['boutique_id'])) {
                return $this->setResponse(false, "Num employe non valide", 403);
            }

        
            $event = new EmpSkillClass();
            $event->setSkill_id($data['skill_id']);
            $event->setEmp_id($data['employe_id']);

            return $event->createNewEmpSkills();
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }
    



    public function readEmpSkill(array $data, $token)
    {
        try {

            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }

            $event = new EmpSkillClass();
            $event->setSkill_id($data["skill_id"]);
            $event->setEmp_id($data["employe_id"]);
            $result = $event->readEmpSkills();
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


    public function deleteEmpSkill(array $data, $token)
    {
        try {
            if (
                empty($data['boutique_id']) ||
                empty($data['employe_id']) ||
                empty($data['skill_id'])
            ) {
                return $this->setResponse(false, "Id Manquant", 401);
            }

            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }
            if (!$this->verifyEmployeBoutique($data['employe_id'], $data['boutique_id'])) {
                return $this->setResponse(false, "Num employe non valide", 403);
            }
            $event = new EmpSkillClass();
            $event->setEmp_id($data["employe_id"]);
            $event->setskill_id($data["skill_id"]);
            $count = $event->deleteEmpSkills();
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
