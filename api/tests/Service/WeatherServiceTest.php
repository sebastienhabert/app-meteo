<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\Client\WeatherClientInterface;
use App\Service\Client\GeocodingClientInterface;
use App\DTO\LocationDTO;
use App\Service\WeatherService;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;

#[AllowMockObjectsWithoutExpectations]
class WeatherServiceTest extends TestCase
{
    public function testGetWeatherByCoordinates(): void
    {
        $weatherClient = $this->createMock(WeatherClientInterface::class);
        $geocodingClient = $this->createMock(GeocodingClientInterface::class);

        $weatherClient->method('fetchWeather')
            ->willReturn([
                'current' => [
                    'temperature_2m' => 20,
                    'wind_speed_10m' => 15,
                    'time' => '2026-11-02T12:00'
                ],
                'latitude' => 47.31,
                'longitude' => 5.01,
            ]);

        $service = new WeatherService($weatherClient, $geocodingClient);

        $result = $service->getWeatherByCoordinates(47.31, 5.01);

        $this->assertSame(20.0, $result->temperature);
        $this->assertSame(15.0, $result->windSpeed);
    }

    public function testGetWeatherByCity(): void
    {
        $weatherClient = $this->createMock(WeatherClientInterface::class);
        $geocodingClient = $this->createMock(GeocodingClientInterface::class);

        $location = new LocationDTO('Dijon', 47.31, 5.01, 'France');

        $geocodingClient->method('search')
            ->willReturn($location);

        $weatherClient->method('fetchWeather')
            ->willReturn([
                'current' => [
                    'temperature_2m' => 22,
                    'wind_speed_10m' => 10,
                    'time' => '2026-11-02T12:00'
                ],
            ]);

        $service = new WeatherService($weatherClient, $geocodingClient);

        $result = $service->getWeatherByCity('Dijon');

        $this->assertSame(22.0, $result->temperature);
        $this->assertSame('Dijon', $result->city);
        $this->assertSame('France', $result->country);
    }
}
