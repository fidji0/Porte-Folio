<?php

use App\Controller\JWTController;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class TestEmployeEvent extends TestCase
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
            "boutique_id" => "6"
        ];
        $token = new JWTController();
        $this->validTokenEmploye = $token->createNewToken("8", time(), time() + 3600 , $other);
        echo $this->validTokenEmploye;
        $this->client = new Client([
            'base_uri' => 'http://localhost:9030/', // Remplacez par l'URL de base de votre API
        ]);
        
    }
    public function testSuccessfulReadNotif()
    {
        $response = $this->client->get('readNotif', [
            'headers' => [
                'Authorization' => "Bearer $this->validTokenEmploye",
            ]
        ]);
    
        $this->assertEquals(200, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('employe_id', $responseBody[0]);
        $this->assertArrayHasKey('boutique_id', $responseBody[0]);
        $this->assertEquals(8, $responseBody[0]['employe_id']);
        $this->assertEquals(6, $responseBody[0]['boutique_id']);
    }
    
//Read event
public function testSuccessfulReadEvent()
{
    $response = $this->client->get('readEmploye', [
        'headers' => [
            'Authorization' => "Bearer $this->validTokenEmploye",
        ]
    ]);

    $this->assertEquals(200, $response->getStatusCode());
    $responseBody = json_decode($response->getBody(), true);
    $this->assertArrayHasKey('employe_id', $responseBody[0]);
    $this->assertArrayHasKey('boutique_id', $responseBody[0]);
    $this->assertEquals(8, $responseBody[0]['employe_id']);
    $this->assertEquals(6, $responseBody[0]['boutique_id']);
}

public function testReadAbsneceWithInvalidToken()
{
    $response = $this->client->get('readEmploye', [
        'headers' => [
            'Authorization' => 'Bearer invalid_token',
        ],
        'form_params' => [
            'id' => 1,
        ],
        'http_errors' => false,
    ]);

    $this->assertEquals(401, $response->getStatusCode());
}

public function testReadEventWithoutToken()
{
    $response = $this->client->get('readEmploye', [
        'form_params' => [
            'id' => 8,
        ],
        'http_errors' => false,
    ]);

    $this->assertEquals(401, $response->getStatusCode());
}
public function testSuccessfulCreateEvent()
{
    $response = $this->client->post('createEmploye', [
        'headers' => [
            'Authorization' => "Bearer $this->validTokenEmploye", // Envoi du token sous forme de Bearer
        ],
        'form_params' => [
            'start_date' => "2024-08-23T15:12",
            'end_date' => "2024-08-23T19:12",
            'objet' => 'congès noel',
            'detail' => "",
            'type' => 'CONGES'
        ]
    ]);

    $this->assertEquals(201, $response->getStatusCode());
    $responseBody = json_decode($response->getBody(), true);
    self::$eventId = $responseBody[0]["id"];
    
    $this->assertArrayHasKey('id', $responseBody[0]); // Exemple de vérification supplémentaire
    $this->assertEquals(8, $responseBody[0]["employe_id"]);
    
}
public function testerrorCreateEvent()
{
    $response = $this->client->post('createEmploye', [
        'headers' => [
            'Authorization' => "Bearer $this->validTokenEmploye", // Envoi du token sous forme de Bearer
        ],
        'form_params' => [
            'start_date' => "2024-08-23T15:12",
            'end_date' => "2024-08-23T19:12",
            'objet' => 'congès noel',
            'detail' => "",
            'type' => 'TRAVAIL'
        ],
        'http_errors' => false,
    ]);

    $this->assertEquals(401, $response->getStatusCode());
    $responseBody = json_decode($response->getBody(), true);
    
    $this->assertArrayHasKey('result', $responseBody); // Exemple de vérification supplémentaire
    $this->assertEquals(false, $responseBody["result"]);
    
}
}