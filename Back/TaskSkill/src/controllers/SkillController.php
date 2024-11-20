<?php

namespace App\Controller;


use App\Class\SkillClass;


class SkillController extends CommonController
{

    public function createSkill(array $data, $token)
    {
        try {
            if (
                empty($data['boutique_id']) ||
                empty($data['name']) 
            ) {
                return $this->setResponse(false, "Donnée manquante", 401);
            }

            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }
            

        
            $event = new SkillClass();
            $event->setBoutique_id($data['boutique_id']);
            $event->setName($data['name']);

            return $event->createNewSkill();
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }
    
    public function updateSkill(array $data, $token)
    {
        try {
            if (
                empty($data['id']) ||
                empty($data['boutique_id']) ||
                empty($data['name'])
                
            ) {
                return $this->setResponse(false, "Donnée manquante", 401);
            }

            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }
            
            $event = new SkillClass();
            $event->setId($data['id']);
            $event->setBoutique_id($data['boutique_id']);
            $event->setName($data['name']);
            

            if ($event->updateSkill() > 0) {
                return $this->setResponse(true, "Mis à jour avec succès", 200);
            }else{
                return $this->setResponse(true, "Aucun élément à mettre à jour", 200);
            }
            
            
            return $this->setResponse(false, "Une erreur s'est produite", 500);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }



    public function readSkill(array $data, $token)
    {
        try {

            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }

            $event = new SkillClass();
            $event->setBoutique_id($data["boutique_id"]);
            $result = $event->readSkill();
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


    public function deleteSkill(array $data, $token)
    {
        try {
            if (
                empty($data['id']) ||
                empty($data['boutique_id'])
            ) {
                return $this->setResponse(false, "Id Manquant", 401);
            }

            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }

            $event = new SkillClass();
            $event->setId($data["id"]);
            $event->setBoutique_id($data["boutique_id"]);
            $count = $event->deleteSkill();
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
