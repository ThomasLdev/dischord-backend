<?php

namespace App\Tests\Api\Entity;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class UserTest extends ApiTestCase
{
    protected static ?bool $alwaysBootKernel = true;

    private function getToken(string $username, string $password): string
    {
        $response = static::createClient()->request('POST', '/api/auth', [
            'json' => [
                'username' => $username,
                'password' => $password,
            ],
        ]);
        $data = $response->toArray();
        return $data['token'] ?? '';
    }

    public function testCreateUserAsAdmin(): void
    {
        $token = $this->getToken('johny.silverhand@afterlife.ai', 'password');
        static::createClient()->request('POST', '/api/users', [
            'json' => [
                'email' => 'newuser@example.com',
                'plainPassword' => 'newpassword',
                'roles' => ['ROLE_USER']
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);
        self::assertResponseStatusCodeSame(201);
    }

    public function testGetUsersAsUser(): void
    {
        $token = $this->getToken('johny.silverhand@afterlife.ai', 'password');
        static::createClient()->request('GET', '/api/users', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);
        self::assertResponseIsSuccessful();
    }

    public function testUpdateUserAsAdmin(): void
    {
        $token = $this->getToken('johny.silverhand@afterlife.ai', 'password');
        // Create user first
        $response = static::createClient()->request('POST', '/api/users', [
            'json' => [
                'email' => 'updateuser@example.com',
                'plainPassword' => 'password',
                'roles' => ['ROLE_USER']
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);
        $userIri = $response->toArray(false)['@id'];
        // Update user
        static::createClient()->request('PUT', $userIri, [
            'json' => [
                'email' => 'updateduser@example.com',
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);
        self::assertResponseIsSuccessful();
    }

    public function testDeleteUserAsAdmin(): void
    {
        $token = $this->getToken('johny.silverhand@afterlife.ai', 'password');
        // Create user first
        $response = static::createClient()->request('POST', '/api/users', [
            'json' => [
                'email' => 'deleteuser@example.com',
                'plainPassword' => 'password',
                'roles' => ['ROLE_USER']
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);
        $userIri = $response->toArray(false)['@id'];
        // Delete user
        static::createClient()->request('DELETE', $userIri, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);
        self::assertResponseStatusCodeSame(204);
    }
}
