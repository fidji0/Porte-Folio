<?php

namespace App\Class;

use App\Controller\CommonController;

class TeamEmployeClass extends CommonController
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var int
     */
    protected $employe_id;
    /**
     * @var int
     */
    protected $boutique_id;
    /**
     * @var int
     */
    protected $team_id;
    /**
     * @var array
     */
    protected $role;




    // create
    public function associateTeamToEmploye()
    {
        if (
            empty($this->employe_id) ||
            empty($this->team_id)
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }

        try {
            $request = "INSERT INTO team_employe (employe_id, team_id, role)
                SELECT ?, ?, ?
                FROM team
                WHERE id = ?
                AND boutique_id = ?;";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->employe_id, $this->team_id, json_encode($this->role) , $this->team_id , $this->boutique_id]);
            $this->id = $this->pdo->lastInsertId();
            http_response_code(201);
            return $this->readassociateTeam();
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create Employe : " . $th, "class");
            return false;
        }
    }


    //Update
    public function updateassociateTeam()
    {
        if (
            empty($this->name) ||
            empty($this->description) ||
            empty($this->boutique_id) ||
            empty($this->id)
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }
        try {
            $request = "UPDATE team_employe te
            JOIN employe e ON te.employe_id = e.id
            SET te.role = ?
            WHERE te.id = ?
            AND te.employe_id = ?
            AND e.boutique_id = ?;
                ";

            $r = $this->pdo->prepare($request);
            $r->execute([json_encode($this->role), $this->id]);
            return true;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Update Employe : " . $th, "class");
            return false;
        }
    }


    // Read
    public function readassociateTeam()
    {
        if (
            empty($this->id) ||
            empty($this->boutique_id)

        ) {
            return $this->setResponse(false, "Id Manquant", 401);
        }
        try {
            $request = "SELECT * FROM team_employe WHERE id = ?";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->id, $this->boutique_id]);
            $res = $r->fetchAll(\PDO::FETCH_ASSOC);
            return $res;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Employe : " . $th, "class");
            return false;
        }
    }

    public function readAllassociateTeamBoutique()
    {
        if (
            empty($this->boutique_id)
        ) {
            $this->setResponse(false, "Id Manquant", 401);
            return false;
        }
        try {
            $request = "SELECT te.* , e.* FROM team_employe te INNER JOIN employe e ON te.employe_id = e.id
            WHERE te.team_id = ?";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->boutique_id]);
            $res = $r->fetchAll(\PDO::FETCH_ASSOC);
            return $res;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Employe With BoutiqueId : " . $th, "class");
            return false;
        }
    }

    // Delete
    public function deleteassociateTeam()
    {
        if (
            empty($this->id)||
            empty($this->employe_id)

        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }
        try {
            $request = "DELETE FROM team_employe WHERE id = ? AND employe_id = ? ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->id, $this->boutique_id]);
            return true;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Delete Employe : " . $th, "class");
            return false;
        }
    }

    /**
     * Get the value of boutique_id
     *
     * @return  int
     */ 
    public function getBoutique_id()
    {
        return $this->boutique_id;
    }

    /**
     * Set the value of boutique_id
     *
     * @param  int  $boutique_id
     *
     * @return  self
     */ 
    public function setBoutique_id(int $boutique_id)
    {
        $this->boutique_id = $boutique_id;

        return $this;
    }

    /**
     * Get the value of employe_id
     *
     * @return  int
     */ 
    public function getEmploye_id()
    {
        return $this->employe_id;
    }

    /**
     * Set the value of employe_id
     *
     * @param  int  $employe_id
     *
     * @return  self
     */ 
    public function setEmploye_id(int $employe_id)
    {
        $this->employe_id = $employe_id;

        return $this;
    }

    /**
     * Get the value of id
     *
     * @return  int
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param  int  $id
     *
     * @return  self
     */ 
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of team_id
     *
     * @return  int
     */ 
    public function getTeam_id()
    {
        return $this->team_id;
    }

    /**
     * Set the value of team_id
     *
     * @param  int  $team_id
     *
     * @return  self
     */ 
    public function setTeam_id(int $team_id)
    {
        $this->team_id = $team_id;

        return $this;
    }

    /**
     * Get the value of role
     *
     * @return  array
     */ 
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set the value of role
     *
     * @param  array  $role
     *
     * @return  self
     */ 
    public function setRole(array $role)
    {
        $this->role = $role;

        return $this;
    }
}
