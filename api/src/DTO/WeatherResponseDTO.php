<?php

namespace App\DTO;

final class WeatherResponseDTO
{
    public function __construct(
        public readonly float $temperature,
        public readonly float $windSpeed,
        public readonly \DateTimeImmutable $time,
        public readonly ?string $city,
        public readonly ?string $country,
        public readonly float $latitude,
        public readonly float $longitude,
    ) {
    }
}
