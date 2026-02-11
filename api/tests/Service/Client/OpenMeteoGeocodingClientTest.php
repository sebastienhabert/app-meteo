<?php

declare(strict_types=1);

namespace App\Tests\Service\Client;

use App\Service\Client\OpenMeteoGeocodingClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class OpenMeteoGeocodingClientTest extends TestCase
{
    public function testSearchReturnsLocation(): void
    {
        $mockResponse = new MockResponse(json_encode([
            'results' => [
                [
                    'name' => 'Dijon',
                    'latitude' => 47.31,
                    'longitude' => 5.01,
                    'country' => 'France'
                ]
            ]
        ]));

        $httpClient = new MockHttpClient($mockResponse);

        $client = new OpenMeteoGeocodingClient($httpClient);

        $location = $client->search('Dijon');

        $this->assertSame('Dijon', $location->name);
        $this->assertSame(47.31, $location->latitude);
        $this->assertSame(5.01, $location->longitude);
    }
}
