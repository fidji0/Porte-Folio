<?php

namespace App\Controller;

use App\Class\EventClass;
use App\Class\TaskClass;

class TaskController extends CommonController
{

    public function createTask(array $data, $token)
    {
        try {
            if (
                empty($data['boutique_id']) ||
                empty($data['start_date']) ||
                empty($data['end_date']) ||
                empty($data['objet']) 
            ) {
                return $this->setResponse(false, "Donnée manquante", 401);
            }

            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }
            

        
            $event = new TaskClass();
            $event->setBoutique_id($data['boutique_id']);
            $event->setStart_date($data['start_date']);
            $event->setEnd_date($data['end_date']);
            $event->setObjet($data['objet']);
            $event->setLieu($data['lieu'] ?? null) ;
            $event->setMinPerson($data['minPerson']?? null);
            $event->setMaxPerson($data['maxPerson']?? null);
            $event->setEventType($data['eventType']?? null);

            return $event->createNewTask();
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }
    
    public function updateTask(array $data, $token)
    {
        try {
            if (
                empty($data['id']) ||
                empty($data['boutique_id']) ||
                empty($data['start_date']) ||
                empty($data['end_date']) ||
                empty($data['objet']) 
            ) {
                return $this->setResponse(false, "Donnée manquante", 401);
            }

            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }
            
            $event = new TaskClass();
            $event->setId($data['id']);
            $event->setBoutique_id($data['boutique_id']);
            $event->setStart_date($data['start_date']);
            $event->setEnd_date($data['end_date']);
            $event->setObjet($data['objet']);
            $event->setLieu($data['lieu'] ?? null);
            $event->setMinPerson($data['minPerson']?? null);
            $event->setMaxPerson($data['maxPerson']?? null);
            $event->setEventType($data['eventType']?? null);

            if ($event->updateTask() > 0) {
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



    public function readTask(array $data, $token)
    {
        try {

            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }

            $event = new TaskClass();
            $event->setBoutique_id($data["boutique_id"]);
            $result = $event->readTask();
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


    public function deleteTask(array $data, $token)
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

            $event = new TaskClass();
            $event->setId($data["id"]);
            $event->setBoutique_id($data["boutique_id"]);
            $count = $event->deleteTask();
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
    public function deleteWeekTask(array $data, $token)
    {
        try {
            if (
                empty($data['start_date']) ||
                empty($data['boutique_id'])
            ) {
                return $this->setResponse(false, "Donnée Manquante", 401);
            }

            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }

            $event = new TaskClass();
            $event->setStart_date($data["start_date"]);
            $event->setBoutique_id($data["boutique_id"]);

            if ($event->deleteWeekTask() > 0) {
                return $this->setResponse(true, "Suppression réussie", 200);
            }

            return $this->setResponse(false, "Une erreur s'est produite", 500);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }

    
}
