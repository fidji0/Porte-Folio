<?php

namespace App\Class;

use App\Controller\CommonController;

class TeamClass extends CommonController
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var int
     */
    protected $boutique_id;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $description;
    
 

    // create
    public function createNewTeam()
    {
        if (
            empty($this->name) ||
            empty($this->description) ||
            empty($this->boutique_id) 
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }
        
        try {
            $request = "INSERT INTO team (name , description , boutique_id )
            VALUES (? , ? , ? ) ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->name,$this->description, $this->boutique_id]);
            $this->id = $this->pdo->lastInsertId();
            http_response_code(201);
            return $this->readTeam();
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create Employe : " . $th , "class");
            return false;
        }
    }


    //Update
    public function updateTeam()
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
            $request = "UPDATE team SET name = ? , description = ?  WHERE id = ? AND boutique_id = ? ;";

            $r = $this->pdo->prepare($request);
            $r->execute([$this->name,  $this->description , $this->id , $this->boutique_id]);
            return true;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Update Employe : " . $th , "class");
            return false;
        }
    }


    // Read
    public function readTeam()
    {
        if (
            empty($this->id) ||
            empty($this->boutique_id)

        ) {
            return $this->setResponse(false, "Id Manquant", 401);
        }
        try {
            $request = "SELECT * FROM team WHERE id = ? AND boutique_id = ?";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->id , $this->boutique_id]);
            $res = $r->fetchAll(\PDO::FETCH_ASSOC);
            return $res;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Employe : " . $th , "class");
            return false;
        }
    }

    public function readAllTeamBoutique()
    {
        if (
            empty($this->boutique_id)
        ) {
            $this->setResponse(false, "Id Manquant", 401);
            return false;
        }
        try {
            $request = "SELECT * FROM team WHERE boutique_id = ?";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->boutique_id]);
            $res = $r->fetchAll(\PDO::FETCH_ASSOC);
            return $res;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Employe With BoutiqueId : " . $th , "class");
            return false;
        }
    }

    // Delete
    public function deleteTeam()
    {
        if (
            empty($this->id)
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }
        try {
            $request = "DELETE FROM team WHERE id = ? AND boutique_id = ? ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->id , $this->boutique_id]);
            return true;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Delete Employe : " . $th , "class");
            return false;
        }
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
     * Get the value of name
     *
     * @return  string
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     *
     * @return  self
     */ 
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of description
     *
     * @return  string
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @param  string  $description
     *
     * @return  self
     */ 
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }
}
