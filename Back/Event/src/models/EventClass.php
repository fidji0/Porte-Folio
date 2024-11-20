<?php

namespace App\Class;

use App\Controller\CommonController;
use App\Controller\MailerController;
use App\Controller\NotifController;
use DateTime;

class EventClass extends CommonController
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
     * @var dateTime
     */
    protected $start_date;
    /**
     * @var dateTime
     */
    protected $end_date;
    /**
     * @var string
     */
    protected $objet;
    /**
     * @var string
     */
    protected $lieu;
    /**
     * @var string
     */
    protected $detail;
    /**
     * @var string
     */
    protected $type;
    /**
     * @var int
     */
    protected $equivWorkTime;




    //Create
    public function createNewEvent()
    {
        if (
            empty($this->employe_id) ||
            empty($this->boutique_id) ||
            empty($this->start_date) ||
            empty($this->end_date)
        ) {
            return $this->setResponse(false, "Donnée manquante", 401);
        }
        try {
            $request = "INSERT INTO events (employe_id , boutique_id , start_date , end_date , objet , lieu , detail , type , equivWorkTime)
            VALUES (? , ? , ? , ? , ? , ? , ? , ? , ?) ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->employe_id, $this->boutique_id, $this->start_date, $this->end_date, $this->objet ?? null, $this->lieu ?? null, $this->detail ?? null, $this->type , $this->equivWorkTime ?? null]);
            $this->id = $this->pdo->lastInsertId();
            http_response_code(201);
            $request = "SELECT * FROM events WHERE id = ?";

            $r = $this->pdo->prepare($request);
            $r->execute([$this->id]);

            $res = $r->fetchAll(\PDO::FETCH_ASSOC);
            return $res;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create event : " . $th, "class");
            return $this->setResponse(false, "Une erreur c'est produite", 500);
        }
    }

    //Read
    public function readEvent()
    {
        try {
            $request = "SELECT e.* , em.name , em.surname , em.color FROM events e INNER JOIN employe em ON em.id = e.employe_id WHERE e.boutique_id = ?";

            $r = $this->pdo->prepare($request);
            $r->execute([$this->boutique_id]);
            $res = $r->fetchAll(\PDO::FETCH_ASSOC);
            return $res;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Event : " . $th, "class");
            return false;
        }
    }

    public function readAllEventBoutique()
    {
        if (
            empty($this->boutique_id)
        ) {
            $this->setResponse(false, "Id Manquant", 401);
            return false;
        }
        try {
            $request = "SELECT * FROM events WHERE boutique_id = ?";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->boutique_id]);
            $res = $r->fetchAll(\PDO::FETCH_ASSOC);
            return $res;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Event With BoutiqueId : " . $th, "class");
            return false;
        }
    }
    public function readAllEventEmploye()
    {
        if (
            empty($this->boutique_id)
        ) {
            $this->setResponse(false, "Id Manquant", 401);
            return false;
        }
        try {
            $request = "SELECT e.* , emp.name , emp.surname , emp.color  FROM events e INNER JOIN employe emp ON emp.id = e.employe_id WHERE e.boutique_id = ? AND e.validate = 1";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->boutique_id]);
            $res = $r->fetchAll(\PDO::FETCH_ASSOC);
            return $res;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Event With Employe_id : " . $th, "class");
            return false;
        }
    }

    //Update 
    public function updateEvent()
    {
        if (
            empty($this->employe_id) ||
            empty($this->id) ||
            empty($this->boutique_id) ||
            empty($this->start_date) ||
            empty($this->type) ||
            empty($this->end_date)
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }
        $data = $this->readUserIdsBeforeUpdatePlanningValidateWeek(1);

        try {
            $request = "UPDATE events e SET employe_id = ?  , start_date = ? , end_date = ? , objet = ? , lieu = ? , detail = ? , e.type = ?  , equivWorkTime = ? WHERE id = ? AND  boutique_id = ? AND locked = 0 ; ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->employe_id, $this->start_date, $this->end_date, $this->objet ?? null, $this->lieu ?? null, $this->detail ?? null, $this->type, $this->equivWorkTime ?? null, $this->id, $this->boutique_id ]);
            $count = $r->rowCount();
            if ($data !== false && is_array($data) && $count > 0) {
                $this->prepareAndSendNotifEmail($data, "Modification de votre planning", "Votre planning a été modifié pour la date du ");
            }
            $this->logError(date("d-m-Y H:i:s") . " Update Event : " . $count, "class");
            return $count;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Update Event : " . $th, "class");
            return false;
        }
    }
    
    private function readUserIdsBeforeUpdatePlanningValidateWeek(int $validate = null)
    {

        try {
            $request = "SELECT e.id , e.start_date , emp.id as user_id , e.boutique_id , emp.notif_phone_id , emp.email
             FROM events e INNER JOIN employe emp 
             ON e.employe_id = emp.id   
             WHERE e.boutique_id = ?  ";
            if (!is_null($validate)) {
                $request .= " AND e.validate = $validate ";
            }
            if ($this->start_date && empty($this->id)) {
                $request .= " AND e.start_date > '$this->start_date' 
             AND e.start_date < DATE_ADD('$this->start_date', INTERVAL 7 DAY) ";
            }
            if (!empty($this->employe_id) && empty($this->id)) {
                $request .= " AND e.employe_id = '$this->employe_id' ";
            }
            if (!empty($this->id)) {
                $request .= " AND e.id = '$this->id' ";
            }
            $request .= " GROUP BY emp.id;";

            $r = $this->pdo->prepare($request);
            $r->execute([$this->boutique_id]);


            return $r->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " data notif : " . $th, "class");
            return false;
        }
    }
    //Validation de l'event 
    public function validateEvent()
    {
        if (
            empty($this->id) ||
            empty($this->boutique_id)
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }
        $data = $this->readUserIdsBeforeUpdatePlanningValidateWeek(0);
        try {
            $request = "UPDATE events e SET validate = 1  WHERE id = ? AND  boutique_id = ?; ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->id, $this->boutique_id]);

            $count = $r->rowCount();
            if ($data !== false && is_array($data) && $count > 0) {
                $this->prepareAndSendNotifEmail($data, "Validation de votre planning", "Votre planning a été validé pour la date du ");
            }
            return $count;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Validate Event : " . $th, "class");
            return false;
        }
    }
    // Validation event sur la semaine complete
    public function validateWeekEvent()
    {
        if (
            empty($this->start_date) ||
            empty($this->boutique_id)
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }
        $data = $this->readUserIdsBeforeUpdatePlanningValidateWeek(0);

        try {
            $request = "UPDATE events SET validate = 1  WHERE boutique_id = ? AND start_date > ? AND start_date < DATE_ADD(?, INTERVAL 7 DAY)  ";
            if (!empty($this->employe_id)) {
                $request .= " AND employe_id = '$this->employe_id' ";
            }
            $r = $this->pdo->prepare($request);
            $r->execute([$this->boutique_id, $this->start_date, $this->start_date]);
            $count = $r->rowCount();

            if ($data !== false && is_array($data) && $count > 0) {
                $this->prepareAndSendNotifEmail($data, "Validation de votre planning", "Votre planning a été validé pour la semaine du ", $this->start_date);
            }

            return $count;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Validate Week Event : " . $th, "class");
            return false;
        }
    }
    //Delete
    public function deleteEvent()
    {
        if (
            empty($this->id) ||
            empty($this->boutique_id)
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }
        $data = $this->readUserIdsBeforeUpdatePlanningValidateWeek(1);

        try {
            $request = "DELETE FROM events WHERE id = ? AND boutique_id = ? AND locked = 0 ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->id, $this->boutique_id]);

            if ($data !== false && is_array($data)) {
                $this->prepareAndSendNotifEmail($data, "Suppression dans votre planning", "Un événement a été supprimé dans votre planning le ");
            }
            return $r->rowCount();
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Delete Event : " . $th, "class");
            return false;
        }
    }

    public function deleteWeekEvent()
    {
        if (
            empty($this->start_date) ||
            empty($this->boutique_id)
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }
        try {
            $request = "DELETE FROM events WHERE boutique_id = ? 
            AND start_date > ? AND start_date < DATE_ADD(?, INTERVAL 7 DAY) AND
            type != 'CONGES' && type != 'MALADIE' AND locked = 0 ";
            if (!empty($this->employe_id)) {
                $request .= " AND employe_id = '$this->employe_id' ";
            }

            $r = $this->pdo->prepare($request);
            $r->execute([$this->boutique_id, $this->start_date, $this->start_date]);
            return $r->rowCount();
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Delete Event : " . $th, "class");
            return false;
        }
    }
    private function prepareAndSendNotifEmail(array $data, $objet, $message, $date = null)
    {
        try {
            $notif = new NotifController();
            $mail = new MailerController();
            foreach ($data as $value) {
                if (isset($value["notif_phone_id"])) {
                    $prep = $notif->createNotif($objet, $message .  " " . (isset($date) ? $this->formatedDate($date) :  $this->formatedDate($value["start_date"])), 0);
                    $success = $notif->sendNotification($prep, [$value["notif_phone_id"]]);
                }
                $mail->sendMailModif($objet, $message . " " . (isset($date) ? $this->formatedDate($date) :  $this->formatedDate($value["start_date"])), $value["email"]);

                $notifClass = new NotifClass();
                $notifClass->setTitle($objet);
                $notifClass->setMessage($message . " " . (isset($date) ? $this->formatedDate($date) :  $this->formatedDate($value["start_date"])));
                $notifClass->setSuccesSend($success ?? 0);
                $notifClass->setEmploye_id($value["user_id"]);
                $notifClass->setBoutique_id($value["boutique_id"]);
                $notifClass->createNewNotif();
            }
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Prepare Notif Event : " . $value["start_date"] . $value["end_date"] . $th, "class");
            return false;
        }
    }

    protected function formatedDate($date): string
    {
        $dateTime = new DateTime($date);

        // Formatage de la date en J-M-Y
        $dateFormatted = $dateTime->format('d-m-Y');
        return $dateFormatted;
    }



    /**
     * Get the value of type
     *
     * @return  string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @param  string  $type
     *
     * @return  self
     */
    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }


    /**
     * Get the value of end_date
     *
     * @return  string
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

        $this->end_date = $end_date;

        return $this;
    }

    /**
     * Get the value of start_date
     *
     * @return  string
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
     * Get the value of objet
     *
     * @return  string
     */
    public function getObjet()
    {
        return $this->objet;
    }

    /**
     * Set the value of objet
     *
     * @param  string  $objet
     *
     * @return  self
     */
    public function setObjet(string | null $objet)
    {
        $this->objet = $objet;

        return $this;
    }

    /**
     * Get the value of lieu
     *
     * @return  string
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
    public function setLieu(string |null $lieu)
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get the value of detail
     *
     * @return  string
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Set the value of detail
     *
     * @param  string|null  $detail
     *
     * @return  self
     */
    public function setDetail(string | null $detail)
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * Get the value of equivWorkTime
     *
     * @return  int
     */ 
    public function getEquivWorkTime()
    {
        return $this->equivWorkTime;
    }

    /**
     * Set the value of equivWorkTime
     *
     * @param  int  $equivWorkTime
     *
     * @return  self
     */ 
    public function setEquivWorkTime(int | string |null $equivWorkTime)
    {
        $this->equivWorkTime = (int) $equivWorkTime;

        return $this;
    }
}
