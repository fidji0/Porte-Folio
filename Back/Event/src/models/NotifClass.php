<?php

namespace App\Class;

use App\Controller\CommonController;
use DateTime;

class NotifClass extends CommonController
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
    protected $created_at;
    /**
     * @var string
     */
    protected $title;
    /**
     * @var string
     */
    protected $message;
    /**
     * @var int
     */
    protected $succesSend;
    /**
     * @var string
     */
    protected $employeView;
    /**
     * @var int
     */
    protected $succesSendMail;


    public function createNewNotif()
    {


        if (
            empty($this->employe_id) ||
            empty($this->boutique_id) ||
            empty($this->title) ||
            empty($this->message) 
        ) {
            return;
        }
        try {
            $request = "INSERT INTO planning_notif (employe_id , boutique_id , title , message , succesSend )
            VALUES (? , ? , ? , ? , ?) ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->employe_id, $this->boutique_id, $this->title, $this->message, $this->succesSend]);

            return true;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create notif : " . $th, "class");
            return false;
        }
    }

    public function readNotif()
    {


        if (
            empty($this->employe_id)
        ) {
            return;
        }
        try {
            $request = "SELECT * FROM planning_notif WHERE employe_id = ? ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->employe_id,]);
            $res = $r->fetchAll(\PDO::FETCH_ASSOC);
            return $res;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create notif : " . $th, "class");
            return false;
        }
    }

    public function updateNotif()
    {


        if (
            empty($this->employe_id)
        ) {
            return;
        }
        try {
            $request = "UPDATE planning_notif SET employeView = 1 WHERE employe_id = ? ";
            $r = $this->pdo->prepare($request);
            $r->execute([$this->employe_id,]);
            $res = $r->rowCount();
            return $res;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create notif : " . $th, "class");
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
     * Get the value of created_at
     *
     * @return  dateTime
     */ 
    public function getCreated_at()
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     *
     * @param  dateTime  $created_at
     *
     * @return  self
     */ 
    public function setCreated_at(dateTime $created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the value of title
     *
     * @return  string
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @param  string  $title
     *
     * @return  self
     */ 
    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of message
     *
     * @return  string
     */ 
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the value of message
     *
     * @param  string  $message
     *
     * @return  self
     */ 
    public function setMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the value of succesSend
     *
     * @return  int
     */ 
    public function getSuccesSend()
    {
        return $this->succesSend;
    }

    /**
     * Set the value of succesSend
     *
     * @param  int  $succesSend
     *
     * @return  self
     */ 
    public function setSuccesSend(int $succesSend)
    {
        $this->succesSend = $succesSend;

        return $this;
    }

    /**
     * Get the value of employeView
     *
     * @return  string
     */ 
    public function getEmployeView()
    {
        return $this->employeView;
    }

    /**
     * Set the value of employeView
     *
     * @param  string  $employeView
     *
     * @return  self
     */ 
    public function setEmployeView(string $employeView)
    {
        $this->employeView = $employeView;

        return $this;
    }
}