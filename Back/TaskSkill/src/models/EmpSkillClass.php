<?php

namespace App\Class;

use App\Controller\CommonController;


class EmpSkillClass extends CommonController
{

    /**
     * @var int
     */
    protected $emp_id;
    /**
     * @var int
     */
    protected $skill_id;
    




    
    //Read
    public function readEmpSkills()
    {
        if (
            empty($this->emp_id) ||
            empty($this->skill_id)
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }
        try {
            $request = "SELECT 
            * FROM empSkills
            WHERE skill_id = ? AND employe_id = ? ";

            $r = $this->pdo->prepare($request);
            $r->execute([$this->skill_id , $this->emp_id]);
            $res = $r->fetchAll(\PDO::FETCH_ASSOC);
            
            return $res;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Event : " . $th, "class");
            return false;
        }
    }






    //Delete
    public function deleteEmpSkills()
    {
        if (
            empty($this->emp_id) ||
            empty($this->skill_id)
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }

        try {
            $request = "DELETE FROM empSkills WHERE employe_id = ? AND skill_id = ? ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->emp_id, $this->skill_id]);
            return $r->rowCount();
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Delete Event : " . $th, "class");
            return false;
        }
    }

    //Create
    public function createNewEmpSkills()
    {
        if (
            empty($this->skill_id) ||
            empty($this->emp_id) 
        ) {
            return $this->setResponse(false, "Donnée manquante", 401);
        }
        try {
            $request = "INSERT INTO empSkills (employe_id , skill_id )
            VALUES (? , ?) ";
            $r = $this->pdo->prepare($request);
            $r->execute([
                $this->emp_id,
                $this->skill_id 
            ]);
            
            http_response_code(201);
            $request = "SELECT * FROM empSkills WHERE employe_id = ? AND skill_id = ?;";

            $r = $this->pdo->prepare($request);
            $r->execute([$this->emp_id , $this->skill_id]);

            $res = $r->fetchAll(\PDO::FETCH_ASSOC);
            return $res;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create event : " . $th, "class");
            return $this->setResponse(false, "Une erreur c'est produite", 500);
        }
    }



    

    
   
    /**
     * Get the value of skill_id
     *
     * @return  int
     */ 
    public function getSkill_id()
    {
        return $this->skill_id;
    }

    /**
     * Set the value of skill_id
     *
     * @param  int  $skill_id
     *
     * @return  self
     */ 
    public function setSkill_id(int $skill_id)
    {
        $this->skill_id = $skill_id;

        return $this;
    }

    /**
     * Get the value of emp_id
     *
     * @return  int
     */ 
    public function getEmp_id()
    {
        return $this->emp_id;
    }

    /**
     * Set the value of emp_id
     *
     * @param  int  $emp_id
     *
     * @return  self
     */ 
    public function setEmp_id(int $emp_id)
    {
        $this->emp_id = $emp_id;

        return $this;
    }
}
