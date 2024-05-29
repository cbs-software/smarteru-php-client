<?php

/**
 * Contains Tests\CBS\SmarterU\ClientTest
 *
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU;

use CBS\SmarterU\Client;
use GuzzleHttp\Client as HttpClient;
use PHPUnit\Framework\TestCase;

/**
 * Tests CBS\SmarterU\Client.
 */
class ClientTest extends TestCase {
    /**
     * Test agreement between getters and setters.
     */
    public function testAgreement() {
        $accountApi = 'account';
        $userApi = 'user';
        $httpClient = (new HttpClient(['base_uri' => 'https://localhost']));
        $client = (new Client($accountApi, $userApi))
            ->setHttpClient($httpClient);
        self::assertEquals($accountApi, $client->getAccountApi());
        self::assertEquals($userApi, $client->getUserApi());
        self::assertEquals($httpClient, $client->getHttpClient());
    }

    /**
     * Test that Client automatically generates the correct HttpClient if one
     * is not provided.
     */
    public function testProvisionHttpClient() {
        $accountApi = 'account';
        $userApi = 'user';
        $client = new Client($accountApi, $userApi);
        $httpClient = $client->getHttpClient();
        self::assertInstanceOf(HttpClient::class, $httpClient);
        self::assertEquals(
            Client::POST_URL,
            $httpClient->getConfig()['base_uri']
        );
    }
}
