<?php

namespace App\Class;

use App\Controller\CommonController;


class TaskSkillClass extends CommonController
{

    /**
     * @var int|string
     */
    protected $task_id;
    /**
     * @var int|string
     */
    protected $skill_id;
    




    
    //Read
    public function readTaskSkills()
    {
        if (
            empty($this->task_id) ||
            empty($this->skill_id)
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }
        try {
            $request = "SELECT 
            * FROM taskSkills
            WHERE skill_id = ? AND task_id = ? ";

            $r = $this->pdo->prepare($request);
            $r->execute([$this->skill_id , $this->task_id]);
            $res = $r->fetchAll(\PDO::FETCH_ASSOC);
            
            return $res;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Event : " . $th, "class");
            return false;
        }
    }






    //Delete
    public function deleteTaskSkills()
    {
        if (
            empty($this->task_id) ||
            empty($this->skill_id)
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }

        try {
            $request = "DELETE FROM taskSkills WHERE task_id = ? AND skill_id = ? ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->task_id, $this->skill_id]);
            return $r->rowCount();
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Delete Event : " . $th, "class");
            return false;
        }
    }

    //Create
    public function createNewTaskSkills()
    {
        if (
            empty($this->skill_id) ||
            empty($this->task_id) 
        ) {
            return $this->setResponse(false, "Donnée manquante", 401);
        }
        try {
            $request = "INSERT INTO taskSkills (task_id , skill_id )
            VALUES (? , ?) ";
            $r = $this->pdo->prepare($request);
            $r->execute([
                $this->task_id,
                $this->skill_id 
            ]);
            
            http_response_code(201);
            $request = "SELECT * FROM taskSkills WHERE task_id = ? AND skill_id = ?;";

            $r = $this->pdo->prepare($request);
            $r->execute([$this->task_id , $this->skill_id]);

            $res = $r->fetchAll(\PDO::FETCH_ASSOC);
            return $res;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create event : " . $th, "class");
            return $this->setResponse(false, "Une erreur c'est produite", 500);
        }
    }



    

    
    

    /**
     * Get the value of task_id
     *
     * @return  int
     */ 
    public function getTask_id()
    {
        return $this->task_id;
    }

    /**
     * Set the value of task_id
     *
     * @param  int  $task_id
     *
     * @return  self
     */ 
    public function setTask_id(int | string $task_id)
    {
        $this->task_id = $task_id;

        return $this;
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
    public function setSkill_id(int | string $skill_id)
    {
        $this->skill_id = $skill_id;

        return $this;
    }
}
