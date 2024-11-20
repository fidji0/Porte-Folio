<?php

namespace App\Class;

use App\Controller\CommonController;
use App\Controller\MailerController;
use App\Controller\NotifController;
use DateTime;

class TaskClass extends CommonController
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
     * @var dateTime
     */
    protected $start_date;
    /**
     * @var dateTime
     */
    protected $end_date;
    /**
     * @var string|null
     */
    protected $objet;
    /**
     * @var string|null
     */
    protected $lieu;

    /**
     * @var int|null
     */
    protected $minPerson;
    /**
     * @var int|null
     */
    protected $maxPerson;
    /**
     * @var string|null
     */
    protected $eventType;






    //Read
    public function readTask()
    {
        try {
            $request = "SELECT 
            t.*, 
            CASE 
                WHEN COUNT(s.id) = 0 THEN NULL
                ELSE JSON_ARRAYAGG(
                    JSON_OBJECT(
                        'id', s.id, 
                        'skill_name', s.name
                    )
                            )
                END AS skills
            FROM 
                tasks t
            LEFT JOIN 
                taskSkills ts ON t.id = ts.task_id
            LEFT JOIN 
                skills s ON ts.skill_id = s.id
            WHERE 
                t.boutique_id = ?
            GROUP BY 
                t.id;";

            $r = $this->pdo->prepare($request);
            $r->execute([$this->boutique_id]);
            $res = $r->fetchAll(\PDO::FETCH_ASSOC);
            if (is_array($res)) {
                foreach ($res as &$resp) {
                    if (!empty($resp["skills"])) {
                        $resp["skills"] = json_decode($resp["skills"], true);
                    }
                }
            }
            return $res;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Event : " . $th, "class");
            return false;
        }
    }






    //Delete
    public function deleteTask()
    {
        if (
            empty($this->id) ||
            empty($this->boutique_id)
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }

        try {
            $request = "DELETE FROM tasks WHERE id = ? AND boutique_id = ? ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->id, $this->boutique_id]);
            return $r->rowCount();
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Delete Event : " . $th, "class");
            return false;
        }
    }

    public function deleteWeekTask()
    {
        if (
            empty($this->start_date) ||
            empty($this->boutique_id)
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }
        try {
            $request = "DELETE FROM tasks WHERE boutique_id = ? 
            AND start_date > ? AND start_date < DATE_ADD(?, INTERVAL 7 DAY) ";

            $r = $this->pdo->prepare($request);
            $r->execute([$this->boutique_id, $this->start_date, $this->start_date]);
            return $r->rowCount();
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Delete Event : " . $th, "class");
            return false;
        }
    }

    //Create
    public function createNewTask()
    {
        if (
            empty($this->boutique_id) ||
            empty($this->start_date) ||
            empty($this->end_date) ||
            empty($this->objet)
        ) {
            return $this->setResponse(false, "Donnée manquante", 401);
        }
        try {
            $request = "INSERT INTO tasks (boutique_id , start_date , end_date , objet , lieu , eventType , minPerson, maxPerson)
            VALUES (? , ? , ? , ? , ? , ? , ? , ?) ";
            $r = $this->pdo->prepare($request);
            $r->execute([
                $this->boutique_id,
                $this->start_date,
                $this->end_date,
                $this->objet,
                $this->lieu ?? null,
                $this->eventType ?? null,
                $this->minPerson ?? null,
                $this->maxPerson ?? null
            ]);
            $this->id = $this->pdo->lastInsertId();
            http_response_code(201);
            $request = "SELECT * FROM tasks WHERE id = ?";

            $r = $this->pdo->prepare($request);
            $r->execute([$this->id]);

            $res = $r->fetchAll(\PDO::FETCH_ASSOC);
            return $res;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create event : " . $th, "class");
            return $this->setResponse(false, "Une erreur c'est produite", 500);
        }
    }

    public function updateTask()
    {
        if (
            empty($this->id) ||
            empty($this->boutique_id) ||
            empty($this->start_date) ||
            empty($this->end_date)
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }

        try {
            $request = "UPDATE tasks e SET  start_date = ? , end_date = ? , objet = ? , lieu = ?
            ,e.minPerson = ?,e.maxPerson = ?,e.eventType = ? WHERE id = ? AND  boutique_id = ?; ";
            $r = $this->pdo->prepare($request);
            $r->execute([
                $this->start_date,
                $this->end_date,
                $this->objet ?? null,
                $this->lieu ?? null,
                $this->minPerson ?? null,
                $this->maxPerson ?? null,
                $this->eventType ?? null,
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
     * Get the value of start_date
     *
     * @return  dateTime
     */
    public function getStart_date()
    {
        return $this->start_date;
    }

    /**
     * Set the value of start_date
     *
     * @param  string  $start_date
     *
     * @return  self
     */
    public function setStart_date(string $start_date)
    {
        $this->start_date = DateTime::createFromFormat('Y-m-d\TH:i', $start_date)->format('Y-m-d H:i:s');

        return $this;
    }

    /**
     * Get the value of end_date
     *
     * @return  dateTime
     */
    public function getEnd_date()
    {
        return $this->end_date;
    }

    /**
     * Set the value of end_date
     *
     * @param  string  $end_date
     *
     * @return  self
     */
    public function setEnd_date(string $end_date)
    {
        $this->end_date = DateTime::createFromFormat('Y-m-d\TH:i', $end_date)->format('Y-m-d H:i:s');

        return $this;
    }

    /**
     * Get the value of objet
     *
     * @return  string|null
     */
    public function getObjet()
    {
        return $this->objet;
    }

    /**
     * Set the value of objet
     *
     * @param  string|null  $objet
     *
     * @return  self
     */
    public function setObjet($objet)
    {
        $this->objet = $objet;

        return $this;
    }

    /**
     * Get the value of lieu
     *
     * @return  string|null
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * Set the value of lieu
     *
     * @param  string|null  $lieu
     *
     * @return  self
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get the value of minPerson
     *
     * @return  int|null
     */
    public function getMinPerson()
    {
        return $this->minPerson;
    }

    /**
     * Set the value of minPerson
     *
     * @param  int|null  $minPerson
     *
     * @return  self
     */
    public function setMinPerson($minPerson)
    {
        $this->minPerson = $minPerson;

        return $this;
    }

    /**
     * Get the value of maxPerson
     *
     * @return  int|null
     */
    public function getMaxPerson()
    {
        return $this->maxPerson;
    }

    /**
     * Set the value of maxPerson
     *
     * @param  int|null  $maxPerson
     *
     * @return  self
     */
    public function setMaxPerson($maxPerson)
    {
        $this->maxPerson = $maxPerson;

        return $this;
    }

    /**
     * Get the value of eventType
     *
     * @return  string|null
     */
    public function getEventType()
    {
        return $this->eventType;
    }

    /**
     * Set the value of eventType
     *
     * @param  string|null  $eventType
     *
     * @return  self
     */
    public function setEventType($eventType)
    {
        $this->eventType = $eventType;

        return $this;
    }
}
