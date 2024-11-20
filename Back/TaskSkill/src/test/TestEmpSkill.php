<?php

use App\Controller\JWTController;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class TestEmpSkill extends TestCase
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
            'base_uri' => 'http://localhost:9090/', // Remplacez par l'URL de base de votre API
        ]);
        $this->validTokenEmploye = $token->createNewToken("8", time(), time() + 3600 , ["boutique_id" => 6]);
        
        
    }



    public function testSuccessfulCreateEvent()
    {
        $response = $this->client->post('createEmpSkill', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken", // Envoi du token sous forme de Bearer
            ],
            'form_params' => [
                'boutique_id' => 6,
                'employe_id' => 8,
                'skill_id' => "2"
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        
        
        $this->assertArrayHasKey('skill_id', $responseBody[0]); // Exemple de vérification supplémentaire
        $this->assertEquals(2, $responseBody[0]["skill_id"]);
        
    }
   
    public function testErrorCreateEventWithOtherEmploye()
    {
        $response = $this->client->post('createEmpSkill', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken", // Envoi du token sous forme de Bearer
            ],
            'form_params' => [
                'boutique_id' => 5,
                'employe_id' => 8,
                'skill_id' => 2
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(403, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        
        $this->assertArrayHasKey('result', $responseBody); // Exemple de vérification supplémentaire
        $this->assertEquals('Non Autorisé', $responseBody['message']);
        
    }
    public function testCreateEventWithMissingData()
    {
        $response = $this->client->post('createEmpSkill', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken", // Envoi du token sous forme de Bearer
            ],
            'form_params' => [
                'boutique_id' => 6,
                'start_date' => "2024-08-23T15:12",
                'objet' => 'matinée',
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(400, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertFalse($responseBody['result']);
        $this->assertEquals('Données manquantes ou invalides', $responseBody['message']);
    }

    public function testUnauthorizedAccess()
    {
        global $authenticate;
        $authenticate = false;

        $response = $this->client->post('createEmpSkill', [
            'headers' => [
                'Authorization' => "Bearer invalidtojddf", // Envoi du token sous forme de Bearer
            ],
            'form_params' => [

                'boutique_id' => 6,
                'start_date' => "2024-08-23T15:12",
                'end_date' => "2024-08-23T19:12",
                'objet' => 'matinée',

                'lieu' => "sur site",

            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

   


    //Read event
    public function testSuccessfulReadEvent()
    {
        $response = $this->client->get('readEmpSkill?boutique_id=6&skill_id=2&employe_id=8', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);

        $this->assertArrayHasKey('employe_id', $responseBody[0]);
        $this->assertArrayHasKey('skill_id', $responseBody[0]);

    }

    public function testReadAbsneceWithInvalidToken()
    {
        $response = $this->client->get('readEmpSkill', [
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

    public function testReadEventWithMissingData()
    {
        $response = $this->client->get('readEmpSkill', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'form_params' => [],
            'http_errors' => false,
        ]);

        $this->assertEquals(400, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertFalse($responseBody['result']);
        $this->assertEquals('Id manquant ou invalide', $responseBody['message']);
    }

    public function testReadEventWithoutToken()
    {
        $response = $this->client->get('readEmpSkill', [
            'form_params' => [
                'id' => 8,
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }


    // test delete event
    public function testSuccessfulDeleteevent()
    {
        $response = $this->client->delete('deleteEmpSkill', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'query' => [
                'boutique_id' => 6,
                'skill_id' => 2,
                'employe_id' => 8,
            ]
        ]);
        
        $this->assertEquals(200, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);
        $this->assertTrue($responseBody['result']);
        $this->assertEquals('Suppression réussie', $responseBody['message']);
    }

    public function testDeleteeventWithMissingData()
    {
        $response = $this->client->delete('deleteEmpSkill', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'query' => [
                // 'id' is missing here
                'boutique_id' => 2,
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(400, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);
        $this->assertFalse($responseBody['result']);
        $this->assertEquals('Id manquant ou invalide', $responseBody['message']);
    }

    public function testUnauthorizedDeleteevent()
    {
        $response = $this->client->delete('deleteEmpSkill', [
            'headers' => [
                'Authorization' => 'InvalidToken',
            ],
            'query' => [
                'id' => 1,
                'boutique_id' => 2,
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }
    
}
