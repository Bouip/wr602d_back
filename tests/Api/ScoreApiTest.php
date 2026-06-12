<?php

namespace App\Tests\Api;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ScoreApiTest extends TestCase
{
    private HttpClientInterface $client;
    private string $jwtToken;
    private string $baseUrl = 'http://localhost:8000';

    protected function setUp(): void
    {
        $this->client = HttpClient::create();

        $response = $this->client->request('POST', $this->baseUrl . '/api/login_check', [
            'json' => [
                'email' => 'test@test.fr',
                'password' => 'password123',
            ],
        ]);

        $data = $response->toArray();
        $this->jwtToken = $data['token'];
    }

    public function testGetScores(): void
    {
        $response = $this->client->request('GET', $this->baseUrl . '/api/scores', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->jwtToken,
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $data = $response->toArray();
        $this->assertIsArray($data);
    }

    public function testAddScore(): void
    {
        $response = $this->client->request('POST', $this->baseUrl . '/api/scores/add', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->jwtToken,
                'Content-Type' => 'application/ld+json',
            ],
            'json' => [
                'points' => 999,
                'duration' => 60,
            ],
        ]);

        $this->assertEquals(201, $response->getStatusCode());
        $data = $response->toArray();
        $this->assertEquals(999, $data['points']);
    }

    public function testAddScoreWithoutAuth(): void
    {
        $response = $this->client->request('POST', $this->baseUrl . '/api/scores/add', [
            'headers' => [
                'Content-Type' => 'application/ld+json',
            ],
            'json' => [
                'points' => 100,
                'duration' => 30,
            ],
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testRegisterUser(): void
    {
        $email = 'test_' . time() . '@test.fr';

        $response = $this->client->request('POST', $this->baseUrl . '/api/user/register', [
            'headers' => [
                'Content-Type' => 'application/ld+json',
            ],
            'json' => [
                'username' => 'testuser_' . time(),
                'email' => $email,
                'plainPassword' => 'password123',
            ],
        ]);

        $this->assertEquals(201, $response->getStatusCode());
        $data = $response->toArray();
        $this->assertEquals('User created successfully', $data['message']);
    }

    public function testAddScoreWithInvalidData(): void
    {
        $response = $this->client->request('POST', $this->baseUrl . '/api/scores/add', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->jwtToken,
                'Content-Type' => 'application/ld+json',
            ],
            'json' => [
                'points' => -100,
                'duration' => 0,
            ],
        ]);

        $this->assertEquals(422, $response->getStatusCode());
    }
}