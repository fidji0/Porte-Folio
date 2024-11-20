<?php

namespace App\Class;

use App\Controller\CommonController;
use App\Controller\MailerController;
use App\Controller\NotifController;
use DateTime;
use InvalidArgumentException;

class AbsenceClass extends CommonController
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
    protected $type;
    /**
     * @var string
     */
    protected $objet;
    /**
     * @var int
     */
    protected $validate;
    /**
     * @var ENUM
     */
    protected $etat;




    //Create
    public function createNewAbsence()
    {
        if (
            empty($this->employe_id) ||
            empty($this->boutique_id) ||
            empty($this->start_date) ||
            empty($this->end_date) ||
            empty($this->type) ||
            empty($this->objet) ||
            !isset($this->validate)
        ) {
            return $this->setResponse(false, "Donnée manquante", 401);
        }
        try {
            if (!$this->verifyAbsenceNotExist()) {
                return $this->setResponse(false, "La demande existe déja", 400);
            }
            $request = "INSERT INTO absence (employe_id , boutique_id , start_date , end_date , type , validate , objet)
            VALUES (? , ? , ? , ? , ? , ? , ? ) ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->employe_id, $this->boutique_id, $this->start_date, $this->end_date, $this->type, $this->validate, $this->objet]);
            $this->id = $this->pdo->lastInsertId();
            if ($this->createNewEventWithAbsence() == true) {
                http_response_code(201);
                $this->readBoutiqueAndSendMail();
                return $this->readAbsence();
            }

            return $this->setResponse(false, "erreur lors de la création", 500);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create Absence : " . $th, "class");
            return $this->setResponse(false, "erreur lors de la création", 500);
        }
    }
    private function createNewEventWithAbsence(): bool
    {
        try {
            $request = "INSERT INTO events (employe_id , boutique_id , start_date , end_date ,type  , lieu  , objet , absence_id)
            VALUES (? , ? , ? , ? , ? , ? , ? , ?)  ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->employe_id, $this->boutique_id, $this->start_date, $this->end_date, $this->type, "nr", $this->objet, $this->id]);


            return true;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create eventAbsence : " . $th, "class");
            return false;
        }
    }
    //Read
    public function readAbsence()
    {
        if (
            empty($this->id)
        ) {
            $this->setResponse(false, "Id Manquant", 401);
            return false;
        }
        try {
            $request = "SELECT * FROM absence WHERE id = ?";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->id]);
            $res = $r->fetchAll(\PDO::FETCH_ASSOC);
            return $res;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "class");
            return false;
        }
    }

    public function readAllAbsenceBoutique()
    {
        if (
            empty($this->boutique_id)
        ) {
            $this->setResponse(false, "Id Manquant", 401);
            return false;
        }
        try {
            $request = "SELECT * FROM absence WHERE boutique_id = ?";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->boutique_id]);
            $res = $r->fetchAll(\PDO::FETCH_ASSOC);
            return $res;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read absence With BoutiqueId : " . $th, "class");
            return false;
        }
    }

    public function readAllAbsenceUser()
    {
        if (
            empty($this->employe_id)
        ) {
            $this->setResponse(false, "Id Manquant", 401);
            return false;
        }
        try {
            $request = "SELECT * FROM absence WHERE employe_id = ?";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->employe_id]);
            $res = $r->fetchAll(\PDO::FETCH_ASSOC);
            return $res;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read absence With BoutiqueId : " . $th, "class");
            return false;
        }
    }
    //Update 
    public function updateAbsence()
    {
        if (
            empty($this->employe_id) ||
            empty($this->id) ||
            empty($this->boutique_id) ||
            empty($this->start_date) ||
            empty($this->end_date) ||
            empty($this->type) ||
            empty($this->validate)
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }
        try {
            $request = "UPDATE absence SET employe_id = ? , boutique_id = ? , start_date = ? , end_date = ? , type = ? , validate = ? WHERE id = ?; ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->employe_id, $this->boutique_id, $this->start_date, $this->end_date, $this->type, $this->validate, $this->id]);
            return true;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create absence : " . $th, "class");
            return false;
        }
    }
    //Valide 
    public function validateAbsence()
    {
        if (
            empty($this->etat) ||
            empty($this->id)
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }
        try {
            $request = "UPDATE absence SET etat = ? WHERE id = ?; ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->etat, $this->id]);
            $count = $r->rowCount();
            if ($count > 0) {
                $this->readEmployeAndSendMail();
            }
            return $count;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create absence : " . $th, "class");
            return false;
        }
    }

    //Delete
    public function deleteAbsence(): bool
    {
        if (
            empty($this->id)
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }
        try {
            $request = "DELETE FROM absence WHERE id = ? ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->id]);
            $count = $r->rowCount();
            if ($count > 0) {
                $requests = "DELETE FROM events WHERE absence_id = ?; ";
                $rs = $this->pdo->prepare($requests);
                $rs->execute([$this->id]);
            }
            return true;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Delete Absence : " . $th, "class");
            return false;
        }
    }


    public function updateAbsenceEmploye()
    {
        if (
            empty($this->employe_id) ||
            empty($this->id) ||
            empty($this->start_date) ||
            empty($this->end_date) ||
            empty($this->type)
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }
        try {
            $request = "UPDATE absence SET  start_date = ? , end_date = ? , type = ?  WHERE id = ? AND validate = 0 AND employe_id = ?; ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->start_date, $this->end_date, $this->type, $this->id, $this->employe_id]);
            return true;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create absence : " . $th, "class");
            return false;
        }
    }

    public function deleteAbsenceEmploye()
    {
        if (
            empty($this->id)
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }
        try {
            $request = "DELETE FROM absence WHERE id = ? AND employe_id = ? AND etat = 'en attente' ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->id, $this->employe_id]);
            return $r->rowCount();
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Delete Absence : " . $th, "class");
            return false;
        }
    }

    protected function readBoutiqueAndSendMail()
    {
        try {
            $request = "SELECT b.email , emp.surname , emp.name , emp.email  AS empMail , emp.notif_phone_id  FROM employe emp INNER JOIN boutique b ON b.id = emp.boutique_id  WHERE emp.id = ?";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->employe_id]);
            $res = $r->fetch(\PDO::FETCH_ASSOC);
            $boutique_mail = $res["email"];
            $name = $res["name"];
            $surname = $res["surname"];
            $mail = new MailerController();
            $mail->sendMailModif(
                "Vous avez recu une demande d'absence",
                "$name $surname Vous a envoyé une demande d'absence de type "
                    . $this->type . " du " . $this->start_date . " au " . $this->end_date,
                $boutique_mail,
                "Live Planning demande d'absence "
            );
            $mail->sendMailModif(
                "Votre demande d'absence à bien été transmise",
                "Bonjour $surname votre demande d'absence de type "
                    . $this->type . " du " . $this->start_date . " au " . $this->end_date . "a bien été envoyer </br>
                    Nous vous souhaitons une bonne journée",
                $res["empMail"],
                "Live Planning demande d'absence "
            );
            $notif = new NotifController();
            if ($res["notif_phone_id"]) {
                $message = $notif->createNotif("$surname Votre demande de type $this->type", "$surname votre demande pour le $this->start_date a bien été transmise ", $this->boutique_id);
                $success = $notif->sendNotification($message, [$res["notif_phone_id"]]);
    
            }
            
            $notif->createNewNotif($this->employe_id, $this->boutique_id, "$surname Votre demande de type $this->type", "$surname votre demande pour le $this->start_date a bien été transmise ", $success ?? 0);
           
            return;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "class");
            return false;
        }
    }

    protected function readEmployeAndSendMail()
    {
        try {
            $request = "SELECT emp.email , emp.surname , emp.name , emp.boutique_id, emp.notif_phone_id , a.etat
            , a.start_date , a.type , emp.id as employe_id FROM absence a INNER JOIN employe emp ON a.employe_id = emp.id  WHERE a.id = ?";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->id]);
            $res = $r->fetch(\PDO::FETCH_ASSOC);
            $employeMail = $res["email"];
            $employe_id = $res["employe_id"];
            $start_date = $res["start_date"];
            $type = $res["type"];
            $boutique_id = $res["boutique_id"];
            $phone_id = $res["notif_phone_id"];
            $name = $res["name"];
            $surname = $res["surname"];
            $etat = $res['etat'];
            $mail = new MailerController();
            $mail->sendMailModif(
                "$surname Changement d'état de votre demande de type $type",
                "$surname votre demande pour le $start_date est passé à l'état $etat ",
                $employeMail,
                "Live Planning demande d'absence "
            );
            $notif = new NotifController();
            $message = $notif->createNotif("$surname Changement d'état de votre demande de type $type", "$surname votre demande pour le $start_date est passé à l'état $etat ", $boutique_id);
            $success = $notif->sendNotification($message, [$phone_id]);

            $notif->createNewNotif($employe_id, $boutique_id, "$surname Changement d'état de votre demande de type $type", " $name $surname votre demande pour le $start_date est passé à l'état $etat ", $success);
            return;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "class");
            return false;
        }
    }



    //Valide 
    public function activateDesactivateEvent()
    {
        if (
            empty($this->etat) ||
            empty($this->id)
        ) {
            $this->setResponse(false, "Donnée manquante", 401);
            return false;
        }
        try {
            if ($this->etat == "valide") {
                $request = "UPDATE events SET validate = 1 WHERE absence_id = ?; ";
                $r = $this->pdo->prepare($request);
                $r->execute([$this->id]);
                $count = $r->rowCount();
                if ($count > 0) {
                    $this->getSuperposeEmployeeEvents();
                }
                return $count;
            }
            if ($this->etat == "refuse") {
                $request = "DELETE FROM events WHERE absence_id = ?; ";
                $r = $this->pdo->prepare($request);
                $r->execute([$this->id]);
                $count = $r->rowCount();
                return $count;
            }
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create absence : " . $th, "class");
            return false;
        }
    }

    //Read abs for notif
    private function readAbsenceForNotif()
    {
        if (
            empty($this->id)
        ) {
            $this->setResponse(false, "Id Manquant", 401);
            return false;
        }
        try {
            $request = "SELECT * FROM absence a INNER JOIN employe emp ON emp.id = a.employe_id  WHERE a.id = ?";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->id]);
            $res = $r->fetch(\PDO::FETCH_ASSOC);
            return $res;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "class");
            return false;
        }
    }

    //recup des events supperposé
    public function getSuperposeEmployeeEvents()
    {
        if (
            empty($this->id)
        ) {
            $this->setResponse(false, "Id Manquant", 401);
            return false;
        }
        $abs = $this->readAbsence();
        try {
            $request = "SELECT * FROM events 
              WHERE employe_id = :employee_id
              AND start_date < :end_date
              AND end_date > :start_date
              ORDER BY start_date ASC";
            $r = $this->pdo->prepare($request);
            $r->execute([
                ':employee_id' => $abs[0]["employe_id"],
                ':start_date' => $abs[0]["start_date"],
                ':end_date' => $abs[0]["end_date"]
            ]);
            $events = $r->fetchAll(\PDO::FETCH_ASSOC);
            if (in_array($abs[0]["type"], ["AUTRE", "FORMATION", "DEPLACEMENT"])) {
                return;
            }
            $this->updateEventWhereSupperposeAbs($abs[0], $events);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Supperpose event : " . $th, "class");
            return false;
        }
    }

    //Update Event en fonction de la requete
    private function updateEventWhereSupperposeAbs(array $abs, array $events)
    {
        try {
            foreach ($events as $event) {
                $absStart = new DateTime($abs['start_date']);
                $absEnd = new DateTime($abs['end_date']);
                $eventStart = new DateTime($event['start_date']);
                $eventEnd = new DateTime($event['end_date']);


                // Cas 1: L'absence recouvre complètement l'événement
                if ($absStart->getTimestamp() <= $eventStart->getTimestamp() && $absEnd->getTimestamp() >= $eventEnd->getTimestamp()) {
                    $this->deleteEvent($event['id'], $abs["id"]);
                }
                // Cas 2
                elseif ($absStart->getTimestamp() <= $eventStart->getTimestamp() && $absEnd->getTimestamp() > $eventStart->getTimestamp() && $absEnd->getTimestamp() < $eventEnd->getTimestamp()) {
                    $this->updateEventStartTime($event['id'], $absEnd->format('Y-m-d H:i:s'));
                }
                // Cas 3
                elseif ($absStart->getTimestamp() > $eventStart->getTimestamp() && $absStart->getTimestamp() < $eventEnd->getTimestamp() && $absEnd->getTimestamp() >= $eventEnd->getTimestamp()) {
                    $this->updateEventEndTime($event['id'], $absStart->format('Y-m-d H:i:s'));
                }
                // Cas 4
                elseif ($absStart->getTimestamp() > $eventStart->getTimestamp() && $absEnd->getTimestamp() < $eventEnd->getTimestamp()) {
                    // Créer un nouvel événement pour la partie après l'absence
                    $this->createEvent([
                        'employe_id' => $event['employe_id'],
                        'boutique_id' => $event['boutique_id'],
                        'type' => $event['type'],
                        'lieu' => $event['lieu'],
                        'objet' => $event['objet'],
                        'validate' => $event['validate'],
                        'start_date' => $absEnd->format('Y-m-d H:i:s'),
                        'end_date' => $event['end_date'],
                        // Autres propriétés de l'événement...
                    ]);
                    // Mettre à jour la fin de l'événement original
                    $this->updateEventEndTime($event['id'], $absStart->format('Y-m-d H:i:s'));
                }
            }
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Supperpose delete update etc : " . $th, "class");
            return false;
        }
    }
    private function deleteEvent($eventId, $absid = null)
    {
        try {


            $request = "DELETE FROM events WHERE id = ? ";
            $params = [$eventId];
            if ($absid) {
                $request .= "AND (absence_id != ? OR absence_id IS NULL)";
                $params[] = $absid;
            }
            $r = $this->pdo->prepare($request);
            $r->execute($params);
            $count = $r->rowCount();
            if ($count > 0) {
                return true;
            }
            return false;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Delete event : " . $th, "class");
            return false;
        }
    }

    private function updateEventStartTime($eventId, $newStartTime)
    {
        try {
            $request = "UPDATE events SET start_date = ? WHERE id = ? ";
            $r = $this->pdo->prepare($request);
            $r->execute([$newStartTime, $eventId]);
            $count = $r->rowCount();
            if ($count > 0) {
                return true;
            }
            return false;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " update event : " . $th, "class");
            return false;
        }
    }

    private function updateEventEndTime($eventId, $newEndTime)
    {
        try {
            $request = "UPDATE events SET end_date = ? WHERE id = ? ";
            $r = $this->pdo->prepare($request);
            $r->execute([$newEndTime, $eventId]);
            $count = $r->rowCount();
            if ($count > 0) {
                return true;
            }
            return false;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " update end event : " . $th, "class");
            return false;
        }
    }

    private function createEvent($eventData)
    {
        try {
            $request = "INSERT INTO events (employe_id , boutique_id , start_date , end_date ,type  , lieu  , objet , validate)
            VALUES (? , ? , ? , ? , ? , ? , ? , ?)  ";
            $r = $this->pdo->prepare($request);
            $r->execute([$eventData["employe_id"], $eventData["boutique_id"], $eventData["start_date"], $eventData["end_date"], $eventData["type"], $eventData["lieu"], $eventData["objet"], $eventData["validate"]]);


            return false;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " insert new event : " . $th, "class");
            return false;
        }
    }


    protected function verifyAbsenceNotExist(): bool
    {
        if (
            empty($this->employe_id) ||
            empty($this->boutique_id) ||
            empty($this->start_date) ||
            empty($this->end_date) ||
            empty($this->type)
        ) {
            $this->setResponse(false, "Id Manquant", 401);
            return false;
        }
        try {
            $request = "SELECT * FROM absence WHERE employe_id = ?  AND boutique_id = ? AND start_date = ? AND end_date = ? 
            AND type = ?";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->employe_id, $this->boutique_id, $this->start_date, $this->end_date, $this->type]);
            $res = $r->fetchAll(\PDO::FETCH_ASSOC);
            if ($res == false || count($res) == 0) {

                return true;
            }
            return false;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "class");
            return false;
        }
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
    public function setObjet(string $objet)
    {
        $this->objet = $objet;

        return $this;
    }

    /**
     * Get the value of validate
     *
     * @return  int
     */
    public function getValidate()
    {
        return $this->validate;
    }

    /**
     * Set the value of validate
     *
     * @param  int  $validate
     *
     * @return  self
     */
    public function setValidate(int $validate)
    {
        $this->validate = $validate;

        return $this;
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
     * Get the value of etat
     *
     * @return  string
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set the value of etat
     *
     * @param  string  $etat
     *
     * @return  self
     */
    public function setEtat(string $etat)
    {
        $validStates = ['valide', 'en attente', 'refuse'];

        if (!in_array($etat, $validStates)) {
            throw new InvalidArgumentException("L'état doit être 'valide', 'en attente' ou 'refuse'.");
        }

        $this->etat = $etat;
        return $this;
    }
}
