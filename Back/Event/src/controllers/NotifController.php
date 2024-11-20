<?php

namespace App\Controller;

use App\Class\NotifClass;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Message;
use Kreait\Firebase\Messaging\Notification;

class NotifController extends CommonController
{

    public function sendNotification(Message $notif, array $target, bool $test = false)
    {
        $factory = (new Factory)->withServiceAccount(DIR . "/firebaseKey.json");
        $messaging = $factory->createMessaging();

        $report = $messaging->sendMulticast($notif, $target);
        return $report->successes()->count();
    }

    public function createNotif(string $title,  string $body, int $boutique_id, string $image = null)
    {


        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, strip_tags($body)))
            ->withData(['id' => $boutique_id])
            ->withApnsConfig(
                ApnsConfig::new()
                    ->withBadge(1)
            );

        return $message;
    }
    public function readEmployeNotif($token)
    {
        try {

            if (!$this->verifyEmployeAuthorization($token)) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }
            if (!isset($this->boutique_ids, $this->user_id)) {
                return $this->setResponse(false, "Donnée Manquante", 403);
            }
            $notif = new NotifClass();
            $notif->setEmploye_id((int) $this->user_id);
            $result = $notif->readNotif();
            if ($result) {
                return $result;
            }

            return $this->setResponse(false, "Aucune donnée trouvée", 404);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }
    public function updateEmployeNotif($token)
    {
        try {

            if (!$this->verifyEmployeAuthorization($token)) {
                return $this->setResponse(false, "Non Autorisé", 403);
            }
            if (!isset($this->boutique_ids, $this->user_id)) {
                return $this->setResponse(false, "Donnée Manquante", 403);
            }
            $notif = new NotifClass();
            $notif->setEmploye_id((int) $this->user_id);
            $result = $notif->updateNotif();
            if ($result > 0) {
                return true;
            }

            return $this->setResponse(false, "Aucune donnée trouvée", 400);
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Read Absence : " . $th, "controller");
            return false;
        }
    }
}
