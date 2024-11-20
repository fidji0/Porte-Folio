<?php

use App\Controller\JWTController;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class TestTeam extends TestCase
{
    private $client;
    protected $validToken;
    protected static $user_id;

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
        $this->client = new Client([
            'base_uri' => 'http://localhost:9020/', // Remplacez par l'URL de base de votre API
        ]);
        
    }



    public function testSuccessfulCreateTeam()
    {
        $response = $this->client->post('team/create', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken", // Envoi du token sous forme de Bearer
            ],
            'form_params' => [
                'name' => 'Team 1',
                'description' => 'Equipe de ménage',
                'boutique_id' => 6
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        self::$user_id = $responseBody[0]["id"];
        
        $this->assertArrayHasKey('id', $responseBody[0]); // Exemple de vérification supplémentaire
        
    }

    public function testCreateTeamWithMissingData()
    {
        $response = $this->client->post('team/create', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken", // Envoi du token sous forme de Bearer
            ],
            'form_params' => [
                'name' => 'Team 1',
                'description' => 'Equipe de ménage',

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

        $response = $this->client->post('team/create', [
            'headers' => [
                'Authorization' => "Bearer invalidtojddf", // Envoi du token sous forme de Bearer
            ],
            'form_params' => [
                'name' => 'Team 1',
                'description' => 'Equipe de ménage',

            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    


   
   

   
    //Read Team
    public function testSuccessfulReadTeam()
    {
        $response = $this->client->get('team/read?id=8&boutique_id=6', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('name', $responseBody[0]);
        $this->assertEquals('Team de foulie', $responseBody[0]['name']);
    }

    public function testReadTeamWithInvalidToken()
    {
        $response = $this->client->get('team/read', [
            'headers' => [
                'Authorization' => 'Bearer invalid_token',
            ],
            'form_params' => [
                'id' => 8,
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testReadTeamWithMissingData()
    {
        $response = $this->client->get('team/read', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'form_params' => [],
            'http_errors' => false,
        ]);

        $this->assertEquals(400, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertFalse($responseBody['result']);
        $this->assertEquals('Données manquantes ou invalides', $responseBody['message']);
    }

    public function testReadTeamWithoutToken()
    {
        $response = $this->client->get('team/read', [
            'form_params' => [
                'id' => 8,
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }
   
    // test mise a jour Team 
    public function testSuccessfulUpdateTeam()
    {
        $response = $this->client->post('team/update', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'form_params' => [
                
                'id' => '2',
                'name' => 'Test',
                'description' => 'description',
                'boutique_id' => 6
            
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);
        $this->assertEquals('Team de foulie', $responseBody[0]['name']);
    }

    public function testUpdateTeamWithMissingData()
    {
        $response = $this->client->post('team/update', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'form_params' => [
                'id' => 1,
                'name' => 'Jane',

            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(400, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);
        $this->assertFalse($responseBody['result']);
        $this->assertEquals('Données manquantes ou invalides', $responseBody['message']);
    }

    public function testUnauthorizedUpdateTeam()
    {
        $response = $this->client->post('team/update', [
            'headers' => [
                'Authorization' => 'InvalidToken',
            ],
            'form_params' => [
                'id' => 1,
                'name' => 'Jane',
                'surname' => 'Doe',
                'email' => 'janedoe@example.com',
                'phone' => '0987654321',
                'boutique_id' => 6,
                'solde_conges' => 15,
                'contrat' => 40
            ],
            'http_errors' => false,
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    

    // test delete Team
    public function testSuccessfulDeleteTeam()
    {
        $response = $this->client->delete('team/delete', [
            'headers' => [
                'Authorization' => "Bearer $this->validToken",
            ],
            'query' => [
                'id' => self::$user_id,
                'boutique_id' => 6,
            ]
        ]);
        
        $this->assertEquals(200, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);
        $this->assertTrue($responseBody['result']);
        $this->assertEquals('Supprimer', $responseBody['message']);
    }

    public function testDeleteTeamWithMissingData()
    {
        $response = $this->client->delete('team/delete', [
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
        $this->assertEquals('Données manquantes ou invalides', $responseBody['message']);
    }

    public function testUnauthorizedDeleteTeam()
    {
        $response = $this->client->delete('team/delete', [
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
