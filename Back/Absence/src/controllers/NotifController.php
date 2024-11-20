<?php

namespace App\Controller;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Message;
use Kreait\Firebase\Messaging\Notification;

class NotifController extends CommonController{
    public function sendNotification(Message $notif, array $target, bool $test = false)
    {
        $factory = (new Factory)->withServiceAccount(DIR . "/firebaseKey.json");
        $messaging = $factory->createMessaging();

        $report = $messaging->sendMulticast($notif, $target);
        return $report->successes()->count();
    }

    public function createNewNotif($employe_id , $boutique_id , $title , $message , $succesSend)
    {

        try {
            $request = "INSERT INTO planning_notif (employe_id , boutique_id , title , message , succesSend )
            VALUES (? , ? , ? , ? , ?) ";
            $r = $this->pdo->prepare($request);
            $r->execute([$employe_id, $boutique_id, $title, $message, $succesSend]);

            return true;
        } catch (\Throwable $th) {
            $this->logError(date("d-m-Y H:i:s") . " Create notif : " . $th, "class");
            return false;
        }
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
}