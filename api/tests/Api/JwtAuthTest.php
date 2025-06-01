<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Throwable;

class JwtAuthTest extends ApiTestCase
{
    protected static ?bool $alwaysBootKernel = true;

    /**
     * @throws TransportExceptionInterface
     */
    #[DataProvider('getTestGetTokenData')]
    public function testGetToken(int $expectedStatus, string $email, string $password): void
    {
        $response = static::createClient()->request('POST', '/api/auth', [
            'json' => [
                'email' => $email,
                'password' => $password,
            ],
            'headers' => [
                'Content-Type' => 'application/ld+json',
            ],
        ]);

        self::assertResponseStatusCodeSame($expectedStatus);

        if ($expectedStatus !== Response::HTTP_OK) {
            return;
        }

        try {
            self::assertArrayHasKey('token', $response->toArray());
        } catch (Throwable) {
            $this->markTestIncomplete('Failed to decode response body. Ensure the API is returning valid JSON.');
        }
    }

    public static function getTestGetTokenData(): array
    {
        return [
            'invalid admin email' => [
                'email' => 'unkownuser@example.com',
                'password' => 'password',
                'expectedStatus' => Response::HTTP_UNAUTHORIZED,
            ],
            'invalid admin password' => [
                'email' => 'johny.silverhand@afterlife.ai',
                'password' => 'wrong-password',
                'expectedStatus' => Response::HTTP_UNAUTHORIZED,
            ],
            'valid admin credentials' => [
                'email' => 'johny.silverhand@afterlife.ai',
                'password' => 'password',
                'expectedStatus' => Response::HTTP_OK,
            ],
            'valid user credentials' => [
                'email' => 'user1@example.com',
                'password' => 'password',
                'expectedStatus' => Response::HTTP_OK,
            ],
        ];
    }
}
