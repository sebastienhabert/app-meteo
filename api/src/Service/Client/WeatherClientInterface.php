<?php

namespace App\Service\Client;

interface WeatherClientInterface
{
    public function fetchWeather(float $latitude, float $longitude): array;
}
