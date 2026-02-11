<?php

namespace App\Service\Client;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class OpenMeteoClient implements WeatherClientInterface
{
    public function __construct(
        private HttpClientInterface $httpClient
    ) {
    }

    public function fetchWeather(float $lat, float $lon): array
    {
        $response = $this->httpClient->request(
            'GET',
            'https://api.open-meteo.com/v1/forecast',
            [
                'query' => [
                    'latitude' => $lat,
                    'longitude' => $lon,
                    'current' => 'temperature_2m,wind_speed_10m,relative_humidity_2m,apparent_temperature',
                ],
            ]
        );

        return $response->toArray();
    }
}
