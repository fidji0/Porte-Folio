<?php

namespace App\Controller;

use App\Class\AbsenceClass;

class AbsenceController extends CommonController
{


    public function createAbsence(array $data, $token)
    {
        try {
            if (
                empty($data['employe_id']) ||
                empty($data['boutique_id']) ||
                empty($data['start_date']) ||
                empty($data['end_date']) ||
                empty($data['objet']) ||
                empty($data['type'])
            ) {
                return $this->setResponse(false, "Donnée manquante", 401);
            }

            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }

            $absence = new AbsenceClass();
            $absence->setEmploye_id($data['employe_id']);
            $absence->setBoutique_id($data['boutique_id']);
            $absence->setStart_date($data['start_date']);
            $absence->setObjet($data['objet']);
            $absence->setEnd_date($data['end_date']);
            $absence->setType($data['type']);
            $absence->setValidate($data['validate'] ?? 0);

            return $absence->createNewAbsence();
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }
    public function updateAbsence(array $data, $token)
    {
        try {
            if (
                empty($data['id']) ||
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

            $absence = new AbsenceClass();
            $absence->setId($data['id']);
            $absence->setEmploye_id($data['employe_id']);
            $absence->setBoutique_id($data['boutique_id']);
            $absence->setStart_date($data['start_date']);
            $absence->setEnd_date($data['end_date']);
            $absence->setType($data['type']);
            $absence->setValidate($data['validate']);

            if ($absence->updateAbsence()) {
                return $this->setResponse(true, "Mis à jour avec succès", 200);
            }

            return $this->setResponse(false, "Une erreur s'est produite", 500);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }
    public function validateAbsence(array $data, $token)
    {
        try {
            if (
                empty($data['id']) ||
                empty($data['etat']) 
            ) {
                return $this->setResponse(false, "Donnée manquante", 401);
            }

            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }

            $absence = new AbsenceClass();
            $absence->setId($data['id']);
            $absence->setEtat($data['etat']);
            $absence->setBoutique_id($data['boutique_id']);
            

            if ($absence->validateAbsence() > 0) {
                $absence->activateDesactivateEvent();
                return $this->setResponse(true, "Mis à jour avec succès", 200);
            }

            return $this->setResponse(false, "Une erreur s'est produite", 500);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }

    public function readAbsence($data, $token)
    {
        try {
            if (empty($data['id'])) {
                return $this->setResponse(false, "Id Manquant", 401);
            }

            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }

            $absence = new AbsenceClass();
            $absence->setId($data["id"]);
            $result = $absence->readAbsence();

            if ($result) {
                return $result;
            }

            return $this->setResponse(false, "Aucune donnée trouvée", 404);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }

    public function readAllAbsenceByBoutique($data, $token)
    {
        try {
            if (empty($data['boutique_id'])) {
                return $this->setResponse(false, "Id Boutique Manquant", 401);
            }

            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }

            $absence = new AbsenceClass();
            $absence->setBoutique_id($data['boutique_id']);
            $result = $absence->readAllAbsenceBoutique();

            if ($result) {
                return $result;
            }

            return $this->setResponse(false, "Aucune donnée trouvée", 404);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }


    public function deleteAbsence(array $data, $token)
    {
        try {
            if (empty($data['id'])) {
                return $this->setResponse(false, "Id Manquant", 401);
            }

            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }

            $absence = new AbsenceClass();
            $absence->setId($data["id"]);

            if ($absence->deleteAbsence()) {
                return $this->setResponse(true, "Suppression réussie", 200);
            }

            return $this->setResponse(false, "Une erreur s'est produite", 500);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }

    // permet a un employer de faire une demande d'absence 
    public function createEmployeAbsence(array $data, $token)
    {
        try {
            $this->decodeEmployeToken($token);
            if (
                empty($this->user_id) ||
                empty($data['start_date']) ||
                empty($data['end_date']) ||
                empty($data['type'])||
                empty($data['objet'])
            ) {
                return $this->setResponse(false, "Donnée manquante", 401);
            }

            // read boutiqueId associe au user

            $request = "SELECT boutique_id FROM employe WHERE id = ?";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->user_id]);
            $res = $r->fetch(\PDO::FETCH_ASSOC);
            $boutique_id = $res["boutique_id"];


            $absence = new AbsenceClass();
            $absence->setEmploye_id($this->user_id);
            $absence->setBoutique_id($boutique_id);
            $absence->setStart_date($data['start_date']);
            $absence->setObjet($data['details'] ?? $data['objet'] );
            $absence->setEnd_date($data['end_date']);
            $absence->setType($data['type']);
            $absence->setValidate($data['validate'] ?? 0);

            return $absence->createNewAbsence();

        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }
    

    public function readAllAbsenceByUser($data, $token)
    {
        try {
            
            $this->decodeEmployeToken($token);

            $absence = new AbsenceClass();
            $absence->setEmploye_id($this->user_id);
            $result = $absence->readAllAbsenceUser();

            if ($result) {
                return $result;
            }

            return $this->setResponse(false, "Aucune donnée trouvée", 404);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }


    public function updateAbsenceEmploye(array $data, $token)
    {
        try {
            $this->decodeEmployeToken($token);
            if (
                empty($data['id']) ||
                empty($data['start_date']) ||
                empty($data['end_date']) ||
                empty($data['type'])
            ) {
                return $this->setResponse(false, "Donnée manquante", 401);
            }

            $absence = new AbsenceClass();
            $absence->setId($data['id']);
            $absence->setEmploye_id($this->user_id);
            $absence->setStart_date($data['start_date']);
            $absence->setEnd_date($data['end_date']);
            $absence->setType($data['type']);

            if ($absence->updateAbsenceEmploye()) {
                return $this->setResponse(true, "Mis à jour avec succès", 200);
            }

            return $this->setResponse(false, "Une erreur s'est produite", 500);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }


    public function deleteAbsenceEmploye(array $data, $token)
    {
        try {
            if (empty($data['id'])) {
                return $this->setResponse(false, "Id Manquant", 401);
            }
            $this->decodeEmployeToken($token);

            $absence = new AbsenceClass();
            $absence->setId($data["id"]);
            $absence->setEmploye_id($this->user_id);

            if ($absence->deleteAbsenceEmploye() > 0) {
                return $this->setResponse(true, "Suppression réussie", 200);
            }

            return $this->setResponse(false, "Une erreur s'est produite", 500);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }
    
}
