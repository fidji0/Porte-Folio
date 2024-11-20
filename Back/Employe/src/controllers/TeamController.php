<?php

namespace App\Controller;

use App\Class\TeamClass;

class TeamController extends CommonController
{

    public string $token;


    //create
    public function createTeam(array $data, $token)
    {

        try {

            if (
                empty($data['name']) ||
                empty($data['description']) ||
                empty($data['boutique_id']) 
            ) {
                return $this->setResponse(false, "Donnée manquante", 401);
            }
            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }
           
            $team = new TeamClass();
            $team->setName($data['name']);
            $team->setBoutique_id($data['boutique_id']);
            $team->setDescription($data['description']);

            return $team->createNewTeam();
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create Employe : " . $th, "controller");
            return false;
        }
    }

    /**
     * 
     * read employe
     */
    public function readTeam(array $data, $token)
    {

        try {

            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }
            $team = new TeamClass();
            $team->setBoutique_id($data['boutique_id']);
            $team->setId($data['id']);

            return $team->readTeam();
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Employe : " . $th, "controller");
            return false;
        }
    }
    /**
     * 
     * read All employe
     */
    public function readAllTeam(array $data, $token)
    {

        try {

            if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }
            $team = new TeamClass();
            $team->setBoutique_id($data['boutique_id']);

            return $team->readAllTeamBoutique();
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create Employe : " . $th, "controller");
            return false;
        }
    }
    /**
     * mise a jour des données du salarié
     */
    public function updateTeam(array $data, $token)
    {
        if (
            empty($data['id']) ||
            empty($data['name']) ||
            empty($data['description']) ||
            empty($data['boutique_id']) 
        ) {
            return $this->setResponse(false, "Donnée manquante", 401);
        }

        //Vérifie autorisation de l'app
        if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
            return $this->setResponse(false, "Non Autorisé", 403);
        }

       

        $team = new TeamClass();

        $team->setId($data['id']);
        $team->setName($data['name']);
        $team->setBoutique_id($data['boutique_id']);
        $team->setDescription($data['description']);

        if ($team->updateTeam()) {
            return $this->readAllTeam($data, $token);
        }

        return $this->setResponse(false, "Une erreur c'est produite", 500);

        try {
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create Employe : " . $th, "controller");
            return false;
        }
    }
    /**
     * mise a jour des données du salarié
     */
    public function deleteTeam(array $data, $token)
    {
        if (
            empty($data['boutique_id']) ||
            empty($data['id'])
        ) {
            return $this->setResponse(false, "Donnée manquante", 401);
        }

        //Vérifie autorisation de l'app
        if (!$this->verifyAuthorization($token, $data['boutique_id'])) {
            return $this->setResponse(false, "Non Autorisé", 403);
        }


        $employe = new TeamClass();

        $employe->setId($data['id']);
        $employe->setBoutique_id($data['boutique_id']);

        if ($employe->deleteTeam()) {
            return $this->setResponse(true, "Supprimer", 200);
        }

        return $this->setResponse(false, "Une erreur c'est produite", 500);

        try {
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Delete Employe : " . $th, "controller");
            return false;
        }
    }
    
    
}
