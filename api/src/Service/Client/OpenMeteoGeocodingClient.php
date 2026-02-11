<?php

namespace App\Service\Client;

use App\DTO\LocationDTO;
use App\Exception\CityNotFoundException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class OpenMeteoGeocodingClient implements GeocodingClientInterface
{
    public function __construct(
        private HttpClientInterface $httpClient
    ) {
    }

    public function search(string $city): LocationDTO
    {
        $response = $this->httpClient->request(
            'GET',
            'https://geocoding-api.open-meteo.com/v1/search',
            [
                'query' => [
                    'name' => $city,
                    'count' => 1,
                    'language' => 'fr',
                ],
            ]
        );

        $data = $response->toArray();

        if (empty($data['results'])) {
            throw new CityNotFoundException($city);
        }

        $result = $data['results'][0];

        return new LocationDTO(
            name: $result['name'],
            latitude: $result['latitude'],
            longitude: $result['longitude'],
            country: $result['country']
        );
    }
}
