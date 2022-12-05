<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HelloControllerTest extends WebTestCase
{
    public function provideNameDisplayed(): array
    {
        return [
            ['/hello', 'Hello Laurent'],
            ['/hello/world', 'Hello world'],
        ];
    }

    /**
     * @dataProvider provideNameDisplayed
     */
    public function testNameDisplayed(string $uri, string $expectedTest): void
    {
        $client = static::createClient();
        $client->request('GET', $uri);

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString($expectedTest, $client->getResponse()->getContent());
    }

    public function provideNotFound(): array
    {
        return [
            'Bad requirements' => ['GET', '/hello/foo1', 404],
            'Bad method' => ['POST', '/hello/world', 405],
        ];
    }

    /**
     * @dataProvider provideNotFound
     */
    public function testNotFound(string $method, string $uri, int $expectedCode): void
    {
        $client = static::createClient();
        $client->request($method, $uri);

        $this->assertSame($expectedCode, $client->getResponse()->getStatusCode());
    }
}
