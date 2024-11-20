<?php

use App\Controller\JWTController;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class TestTask extends TestCase
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
        $response = $this->client->post('createTask', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken", // Envoi du token sous forme de Bearer
            ],
            'form_params' => [
                'boutique_id' => 6,
                'start_date' => "2024-08-23T15:12",
                'end_date' => "2024-08-23T19:12",
                'objet' => 'matinée',
                'lieu' => "sur site",
                'minPerson' => 2
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        self::$eventId = $responseBody[0]["id"];
        
        $this->assertArrayHasKey('id', $responseBody[0]); // Exemple de vérification supplémentaire
        $this->assertEquals(2, $responseBody[0]["minPerson"]);
        
    }
    public function testSuccessful2CreateEvent()
    {
        $response = $this->client->post('createTask', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken", // Envoi du token sous forme de Bearer
            ],
            'form_params' => [
                'boutique_id' => 6,
                'start_date' => "2024-08-24T15:12",
                'end_date' => "2024-08-24T19:12",
                'objet' => 'matinée',
                'lieu' => "sur site",
                'maxPerson' => 4
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        
        
        $this->assertArrayHasKey('id', $responseBody[0]); // Exemple de vérification supplémentaire
        $this->assertEquals(4, $responseBody[0]["maxPerson"]);
        
    }
    public function testErrorCreateEventWithOtherEmploye()
    {
        $response = $this->client->post('createTask', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken", // Envoi du token sous forme de Bearer
            ],
            'form_params' => [
                'boutique_id' => 5,
                'start_date' => "2024-08-23T15:12",
                'end_date' => "2024-08-23T19:12",
                'objet' => 'matinée',
                'detail' => "ras",
                'lieu' => "sur site",
                'type' => 'TRAVAIL'
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
        $response = $this->client->post('createTask', [
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

        $response = $this->client->post('createTask', [
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
        $response = $this->client->get('readTask?boutique_id=6', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);

        $this->assertArrayHasKey('skills', $responseBody[0]);
        $this->assertEquals(6, $responseBody[0]['boutique_id']);
    }

    public function testReadAbsneceWithInvalidToken()
    {
        $response = $this->client->get('readTask', [
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
        $response = $this->client->get('readTask', [
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
        $response = $this->client->get('readTask', [
            'form_params' => [
                'id' => 8,
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }



    // test mise a jour event 
    
    public function testErrorUpdateEventNoElementMaj()
    {
        $response = $this->client->post('updateTask', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'form_params' => [
                'id'=> self::$eventId,
                'boutique_id' => 6,
                'start_date' => "2024-08-23T15:12",
                'end_date' => "2024-08-23T19:12",
                'objet' => 'matinée',
                'lieu' => "sur site",
                'minPerson' => 2
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);
        $this->assertEquals('Aucun élément à mettre à jour', $responseBody['message']);
    }
    
    public function testSuccessfulUpdateEvent()
    {
        $response = $this->client->post('updateTask', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'form_params' => [
                'id'=> self::$eventId,
                'boutique_id' => 6,
                'start_date' => "2024-08-22T14:12",
                'end_date' => "2024-08-23T19:12",
                'objet' => 'matinée',
                'lieu' => "sur site",
                
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);
        $this->assertEquals('Mis à jour avec succès', $responseBody['message']);
    }
   

    public function testErrorUpdateEventWithBadEmploye()
    {
        $response = $this->client->post('updateTask', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'form_params' => [
                'id'=> 20,
                'boutique_id' => 5,
                'start_date' => "2024-08-23T15:12",
                'end_date' => "2024-08-23T19:12",
                'objet' => 'matinée',
                'detail' => "test",
                'lieu' => "sur site",
                'type' => 'TRAVAIL'
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(403, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);
        $this->assertEquals('Non Autorisé', $responseBody['message']);
    }

    public function testUpdateeventWithMissingData()
    {
        $response = $this->client->post('updateTask', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'form_params' => [
                'id' => 2,
                'start_date' => 'Jane',

            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(400, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);
        $this->assertFalse($responseBody['result']);
        $this->assertEquals('Données manquantes ou invalides', $responseBody['message']);
    }

    public function testUnauthorizedUpdateevent()
    {
        $response = $this->client->post('updateTask', [
            'headers' => [
                'Authorization' => 'InvalidToken',
            ],
            'form_params' => [
                'id'=> 2,
                'employe_id' => 8,
                'start_date' => "2024-08-23T15:12",
                'end_date' => "2024-08-23T19:12",
                'boutique_id' => 6,
                'type' => 'maladie',
                'validate' => 1,
                'type' => 'TRAVAIL'
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }


    // test delete event
    public function testSuccessfulDeleteevent()
    {
        $response = $this->client->delete('deleteTask', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'query' => [
                'id' => self::$eventId,
                'boutique_id' => 6,
            ]
        ]);
        
        $this->assertEquals(200, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);
        $this->assertTrue($responseBody['result']);
        $this->assertEquals('Suppression réussie', $responseBody['message']);
    }

    public function testDeleteeventWithMissingData()
    {
        $response = $this->client->delete('deleteTask', [
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
        $response = $this->client->delete('deleteTask', [
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
    // test delete event
    public function testSuccessfulDeleteWeekEvent()
    {
        $response = $this->client->delete('deleteWeekTask', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'query' => [
                'start_date' => "2024-08-23T00:12",
                'boutique_id' => 6,
            ]
        ]);
        
        $this->assertEquals(200, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);
        $this->assertTrue($responseBody['result']);
        $this->assertEquals('Suppression réussie', $responseBody['message']);
    }

}
