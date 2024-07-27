<?php

namespace App\Tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class MainGridTest extends ApiTestCase
{
    protected function getAuthAdminToken()
    {
      return $this->getAuthToken('den.timanovskiy@gmail.com','Ger759d0');
    }

    protected function getAuthToken($username, $password)
    {
        return static::$container->get('lexik_jwt_authentication.encoder')
            ->encode([ 'email' => $username ,'password' => $password]);
    }

    public function testCreateUser()
    {
        $client = self::createClient();
        $client->request('POST', '/api/users', [
            'headers' => ['Content-Type' => 'application/ld+json'],
            'json' =>  [
                "email" => "den.timanovskiy332@gmail.com",
                "plainPassword"=> "Ger759d0"
            ],
        ]);

        $this->assertResponseStatusCodeSame(200);
    }


    public function testFilters()
    {
        $client = self::createClient();
        $client->request('GET', '/api/screener_filters?page=1', [
            'headers' => [
                'Content-Type' => 'application/ld+json',
                'Authorization' => 'Bearer '.$this->getAuthAdminToken()
            ],
            'json' =>  [],
        ]);

        $this->assertResponseStatusCodeSame(200);
    }

}
