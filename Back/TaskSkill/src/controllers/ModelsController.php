<?php

namespace App\Controller;

use App\Class\ModelWeekClass;

class ModelsController extends CommonController{
    

    public function saveNewWeekModels(array $data , $token){
        try {
            if (
                
                empty($data['name']) ||
                empty($data['week_template']) ||
                empty($data['boutique_id']) ||
                empty($data['employe_id']) 

            ) {
                return $this->setResponse(false, "Donnée manquante", 401);
            }

            if (!$this->verifyAuthorization($token , $data["boutique_id"])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }
            if (!$this->verifyEmployeBoutique($data['employe_id'], $data['boutique_id'])) {
                return $this->setResponse(false, "Num employe non valide", 403);
            }

        
            $event = new ModelWeekClass();
            $event->setEmploye_id($data['employe_id']);
            $event->setName($data['name']);
            $event->setWeek_template($data['week_template']);

            return $event->createWeekTemplate();
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }
}