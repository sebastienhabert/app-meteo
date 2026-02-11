<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\LocationDTO;
use App\DTO\WeatherResponseDTO;
use App\Service\Client\WeatherClientInterface;
use App\Service\Client\GeocodingClientInterface;

final class WeatherService
{
    public function __construct(
        private WeatherClientInterface $weatherClient,
        private GeocodingClientInterface $geocodingClient,
    ) {
    }

    public function getWeatherByCoordinates(float $lat, float $lon): WeatherResponseDTO
    {
        $data = $this->weatherClient->fetchWeather($lat, $lon);

        return $this->mapToDTO($data);
    }

    public function getWeatherByCity(string $city): WeatherResponseDTO
    {
        $location = $this->geocodingClient->search($city);

        $data = $this->weatherClient->fetchWeather(
            $location->latitude,
            $location->longitude
        );

        return $this->mapToDTO($data, $location);
    }

    private function mapToDTO(array $data, ?LocationDTO $location = null): WeatherResponseDTO
    {
        return new WeatherResponseDTO(
            temperature: $data['current']['temperature_2m'],
            windSpeed: $data['current']['wind_speed_10m'],
            time: new \DateTimeImmutable($data['current']['time']),
            city: $location?->name,
            country: $location?->country,
            latitude: $location?->latitude ?? $data['latitude'],
            longitude: $location?->longitude ?? $data['longitude'],
            relativeHumidity: $data['current']['relative_humidity_2m'],
            apparentTemperature: $data['current']['apparent_temperature'],
        );
    }
}
