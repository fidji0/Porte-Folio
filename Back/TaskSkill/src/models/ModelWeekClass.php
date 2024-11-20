<?php

namespace App\Class;

use App\Controller\CommonController;

class ModelWeekClass extends CommonController{



/**
     * @var int
     */
    protected $id;
    /**
     * @var int
     */
    protected $employe_id;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $week_template;
    

    public function createWeekTemplate()
    {


        if (
            empty($this->employe_id) ||
            empty($this->name) ||
            empty($this->week_template) 
        ) {
            return;
        }
        try {
            $request = "INSERT INTO week_templates (employe_id , name , week_template  )
            VALUES (? , ? , ? ) ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->employe_id, $this->name, $this->week_template]);
            $this->id = $this->pdo->lastInsertId();
            http_response_code(201);
            return $this->readUniqueWeekTemplate();
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create notif : " . $th, "class");
            return false;
        }
    }

    public function updateWeekTemplate()
    {


        if (
            empty($this->id) ||
            empty($this->name) ||
            empty($this->week_template) 
        ) {
            return;
        }
        try {
            $request = "UPDATE week_templates SET name = ? , week_template = ? WHERE id = ? ";
            $r = $this->pdo->prepare($request);
            $r->execute([ $this->name, $this->week_template , $this->id]);

            return true;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create notif : " . $th, "class");
            return false;
        }
    }
    public function readWeekTemplate()
    {


        if (
            empty($this->employe_id) 
        ) {
            return;
        }
        try {
            $request = "SELECT * FROM week_templates WHERE employe_id = ? ";
            $r = $this->pdo->prepare($request);
            $r->execute([ $this->employe_id]);
            $res = $r->fetchAll(\PDO::FETCH_ASSOC);
            if ($res != false) {
                return $res;
            }
            return false;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create notif : " . $th, "class");
            return false;
        }
    }

    public function readUniqueWeekTemplate()
    {


        if (
            empty($this->id) 
        ) {
            return;
        }
        try {
            $request = "SELECT * FROM week_templates WHERE id = ? ";
            $r = $this->pdo->prepare($request);
            $r->execute([ $this->id]);
            $res = $r->fetchAll(\PDO::FETCH_ASSOC);
            if ($res != false) {
                return $res;
            }
            return false;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create notif : " . $th, "class");
            return false;
        }
    }


    public function deleteWeekTemplate()
    {
        if (
            empty($this->id) 
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }
        
        try {
            $request = "DELETE FROM week_templates WHERE id = ?";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->id]);
            return $r->rowCount();
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Delete Event : " . $th, "class");
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
     * Get the value of week_template
     *
     * @return  string
     */ 
    public function getWeek_template()
    {
        return $this->week_template;
    }

    /**
     * Set the value of week_template
     *
     * @param  string  $week_template
     *
     * @return  self
     */ 
    public function setWeek_template(string $week_template)
    {
        $this->week_template = $week_template;

        return $this;
    }
}