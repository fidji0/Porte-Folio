<?php

namespace App\Controller;

use App\Class\EventClass;

class EventController extends CommonController
{

    public function createEvent(array $data, $token)
    {
        try {
            if (
                empty($data['employe_id']) ||
                empty($data['boutique_id']) ||
                empty($data['start_date']) ||
                empty($data['end_date']) ||
                empty($data['type']) 

            ) {
                return $this->setResponse(false, "Donnée manquante", 401);
            }

            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }
            if (!$this->verifyEmployeBoutique($data['employe_id'], $data['boutique_id'])) {
                return $this->setResponse(false, "Num employe non valide", 403);
            }

        
            $event = new EventClass();
            $event->setEmploye_id($data['employe_id']);
            $event->setBoutique_id($data['boutique_id']);
            $event->setStart_date($data['start_date']);
            $event->setEnd_date($data['end_date']);
            $event->setObjet($data['objet'] ?? null);
            $event->setLieu($data['lieu'] ?? null);
            $event->setType($data['type']);
            $event->setDetail($data['detail'] ?? null);
            $event->setEquivWorkTime($data['equivWorkTime'] ?? null);

            return $event->createNewEvent();
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }
    public function createEmployeEvent(array $data, $token)
    {
        try {
            if (
                (
                empty($data['start_date']) ||
                empty($data['end_date']) ||
                empty($data['objet']) ||
                empty($data['type']) )||
                $data['type'] == "TRAVAIL"
            ) {
                return $this->setResponse(false, "Donnée manquante", 401);
            }

            if (!$this->verifyEmployeAuthorization($token)) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }
            if (!$this->verifyEmployeBoutique($this->user_id, $this->boutique_ids)) {
                return $this->setResponse(false, "Num employe non valide", 403);
            }

        
            $event = new EventClass();
            $event->setEmploye_id($this->user_id);
            $event->setBoutique_id($this->boutique_ids);
            $event->setStart_date($data['start_date']);
            $event->setEnd_date($data['end_date']);
            $event->setObjet($data['objet']);
            $event->setLieu($data['lieu'] ?? "inconnu");
            $event->setType($data['type']);
            $event->setDetail($data['detail'] ?? null);

            return $event->createNewEvent();
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }
    public function updateEvent(array $data, $token)
    {
        try {
            if (
                empty($data['id']) ||
                empty($data['employe_id']) ||
                empty($data['boutique_id']) ||
                empty($data['start_date']) ||
                empty($data['end_date']) 
            ) {
                return $this->setResponse(false, "Donnée manquante", 401);
            }

            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }
            if (!$this->verifyEmployeBoutique($data['employe_id'], $data['boutique_id'])) {
                return $this->setResponse(false, "Num employe non valide", 403);
            }
            $event = new EventClass();
            $event->setId($data['id']);
            $event->setEmploye_id($data['employe_id']);
            $event->setBoutique_id($data['boutique_id']);
            $event->setStart_date($data['start_date']);
            $event->setEnd_date($data['end_date']);
            $event->setObjet($data['objet']??null);
            $event->setLieu($data['lieu']??null);
            $event->settype($data['type']);
            $event->setDetail($data['detail'] ?? null);
            $event->setEquivWorkTime($data['equivWorkTime'] ?? null);

            if ($event->updateEvent() > 0) {
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
    public function validateEvent(array $data, $token)
    {
        try {
            if (
                empty($data['id']) ||
                empty($data['boutique_id'])
            ) {
                return $this->setResponse(false, "Donnée manquante", 401);
            }

            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }
            $event = new EventClass();
            $event->setId($data['id']);
            $event->setBoutique_id($data['boutique_id']);

            if ($event->validateEvent() > 0) {
                return $this->setResponse(true, "Activé avec succès", 200);
            }else{
                return $this->setResponse(true, "Aucun élément à mettre à jour", 200);
            }
            
            
            return $this->setResponse(false, "Une erreur s'est produite", 500);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }
    public function validateWeekEvent(array $data, $token)
    {
        try {
            if (
                
                empty($data['start_date']) ||
                empty($data['boutique_id'])
            ) {
                return $this->setResponse(false, "Donnée manquante", 401);
            }

            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }
            $event = new EventClass();
            isset($data["employe_id"]) ? $event->setEmploye_id($data["employe_id"]) : null;
            $event->setStart_date($data['start_date']);
            $event->setBoutique_id($data['boutique_id']);

            if ($event->validateWeekEvent() > 0) {
                return $this->setResponse(true, "Activé avec succès", 200);
            }else{
                return $this->setResponse(false, "Aucun élément à mettre à jour", 400);
            }
            
            
            return $this->setResponse(false, "Une erreur s'est produite", 500);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }



    public function readEvent(array $data, $token)
    {
        try {

            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }

            $event = new EventClass();
            $event->setBoutique_id($data["boutique_id"]);
            $result = $event->readEvent();
            if ($result) {
                return $result;
            }

            return $this->setResponse(false, "Aucune donnée trouvée", 404);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }
    public function readEmployeEvent($token)
    {
        try {

            if (!$this->verifyEmployeAuthorization($token)) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }
            if (!isset($this->boutique_ids , $this->user_id)) {
                return $this->setResponse(false, "Donnée Manquante", 403);
                
            }
            $event = new EventClass();
            $event->setBoutique_id((int) $this->boutique_ids);
            $result = $event->readAllEventEmploye();
            if ($result) {
                return $result;
            }

            return $this->setResponse(false, "Aucune donnée trouvée", 404);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }


    public function readAllEventsByBoutique($boutique_id, $token)
    {
        try {
            if (empty($boutique_id)) {
                return $this->setResponse(false, "Id Boutique Manquant", 401);
            }

            if (!$this->verifyAuthorization($token, $boutique_id)) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }

            $event = new EventClass();
            $event->setBoutique_id($boutique_id);
            $result = $event->readAllEventBoutique();

            if ($result) {
                return $result;
            }

            return $this->setResponse(false, "Aucune donnée trouvée", 404);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }


    public function deleteEvent(array $data, $token)
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

            $event = new EventClass();
            $event->setId($data["id"]);
            $event->setBoutique_id($data["boutique_id"]);

            if ($event->deleteEvent() > 0) {
                return $this->setResponse(true, "Suppression réussie", 200);
            }

            return $this->setResponse(false, "Une erreur s'est produite", 500);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }
    public function deleteWeekEvent(array $data, $token)
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

            $event = new EventClass();
            isset($data["employe_id"]) ? $event->setEmploye_id($data["employe_id"]) : null;
            $event->setStart_date($data["start_date"]);
            $event->setBoutique_id($data["boutique_id"]);

            if ($event->deleteWeekEvent() > 0) {
                return $this->setResponse(true, "Suppression réussie", 200);
            }

            return $this->setResponse(false, "Une erreur s'est produite", 500);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }

    
}
