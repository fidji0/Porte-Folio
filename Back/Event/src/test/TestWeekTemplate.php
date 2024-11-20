<?php

use App\Controller\JWTController;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class TestWeekTemplate extends TestCase
{
    private $client;
    protected $validToken;
    protected $validTokenEmploye;
    protected static $eventId;

    protected function setUp(): void

    {
        define("ENV", "dev");
        define("DIR", __DIR__);
        define("USER", "");
        define("PASSWORD", "");
        define("DIRMAIL", __DIR__ . "/src/templateEmail");
        $other = [
            "email" => "a.vincent.anthony@gmail.com",
            "id" => 6,
            "role" => "user"
        ];
        $token = new JWTController();
        $this->validToken = $token->createNewToken("6", time(), time() + 3600, $other);
        echo $this->validToken;
        $this->client = new Client([
            'base_uri' => 'http://localhost:9030/', // Remplacez par l'URL de base de votre API
        ]);
        $this->validTokenEmploye = $token->createNewToken("8", time(), time() + 3600 , ["boutique_id" => 6]);
        
        
    }



    public function testSuccessfulCreateEvent()
    {
        $response = $this->client->post('createWeek', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken", // Envoi du token sous forme de Bearer
            ],
            'form_params' => [
                'employe_id' => 8,
                'boutique_id' => 6,
                'name' => "semaine 1",
                'week_template' => '{"Lundi":[{"start_time":"2024-09-23T07:00:00.000Z","end_time":"2024-09-23T09:00:00.000Z","event_type":"TRAVAIL","description":"test"},{"start_time":"2024-09-23T07:00:00.000Z","end_time":"2024-09-23T09:00:00.000Z","event_type":"DEPLACEMENT","description":"matinée"}],"Mardi":[{"start_time":"2024-09-24T07:00:00.000Z","end_time":"2024-09-24T09:00:00.000Z","event_type":"TRAVAIL","description":"test"}],"Mercredi":[{"start_time":"2024-09-25T07:00:00.000Z","end_time":"2024-09-25T09:00:00.000Z","event_type":"TRAVAIL","description":"test"}],"Jeudi":[{"start_time":"2024-09-26T07:00:00.000Z","end_time":"2024-09-26T09:00:00.000Z","event_type":"TRAVAIL","description":"test"}],"Vendredi":[{"start_time":"2024-09-27T07:00:00.000Z","end_time":"2024-09-27T09:00:00.000Z","event_type":"TRAVAIL","description":"test"},{"start_time":"2024-09-27T09:00:00.000Z","end_time":"2024-09-27T21:00:00.000Z","event_type":"TRAVAIL","description":"matinée"}],"Samedi":[{"start_time":"2024-09-28T07:00:00.000Z","end_time":"2024-09-28T20:00:00.000Z","event_type":"TRAVAIL","description":"matinée"}],"Dimanche":[]}',
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        self::$eventId = $responseBody[0]["id"];
        
        $this->assertArrayHasKey('id', $responseBody[0]); // Exemple de vérification supplémentaire
        $this->assertEquals(8, $responseBody[0]["employe_id"]);
        
    }



    

}
