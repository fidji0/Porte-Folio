<?php

namespace App\Class;

use App\Controller\CommonController;
use App\Controller\MailerController;
use App\Controller\NotifController;
use DateTime;

class SkillClass extends CommonController
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




    
    //Read
    public function readSkill()
    {
        try {
            $request = "SELECT 
            * FROM skills
            WHERE boutique_id = ?";

            $r = $this->pdo->prepare($request);
            $r->execute([$this->boutique_id]);
            $res = $r->fetchAll(\PDO::FETCH_ASSOC);
            
            return $res;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Event : " . $th, "class");
            return false;
        }
    }






    //Delete
    public function deleteSkill()
    {
        if (
            empty($this->id) ||
            empty($this->boutique_id)
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }

        try {
            $request = "DELETE FROM skills WHERE id = ? AND boutique_id = ? ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->id, $this->boutique_id]);
            return $r->rowCount();
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Delete Event : " . $th, "class");
            return false;
        }
    }

    public function deleteWeekSkill()
    {
        if (
            empty($this->start_date) ||
            empty($this->boutique_id)
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }
        try {
            $request = "DELETE FROM skills WHERE boutique_id = ? 
            AND start_date > ? AND start_date < DATE_ADD(?, INTERVAL 7 DAY) ";

            $r = $this->pdo->prepare($request);

            return $r->rowCount();
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Delete Event : " . $th, "class");
            return false;
        }
    }

    //Create
    public function createNewSkill()
    {
        if (
            empty($this->boutique_id) ||
            empty($this->name) 
        ) {
            return $this->setResponse(false, "Donnée manquante", 401);
        }
        try {
            $request = "INSERT INTO skills (boutique_id , name )
            VALUES (? , ?) ";
            $r = $this->pdo->prepare($request);
            $r->execute([
                $this->boutique_id,
                $this->name 
            ]);
            $this->id = $this->pdo->lastInsertId();
            http_response_code(201);
            $request = "SELECT * FROM skills WHERE id = ?";

            $r = $this->pdo->prepare($request);
            $r->execute([$this->id]);

            $res = $r->fetchAll(\PDO::FETCH_ASSOC);
            return $res;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create event : " . $th, "class");
            return $this->setResponse(false, "Une erreur c'est produite", 500);
        }
    }

    public function updateSkill()
    {
        if (
            empty($this->id) ||
            empty($this->boutique_id) ||
            empty($this->name) 
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }

        try {
            $request = "UPDATE skills SET  name = ?  WHERE id = ? AND  boutique_id = ?; ";
            $r = $this->pdo->prepare($request);
            $r->execute([
               
                $this->name,
                $this->id,
                $this->boutique_id,
            ]);
            $count = $r->rowCount();

            return $count;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Update Event : " . $th, "class");
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
}
