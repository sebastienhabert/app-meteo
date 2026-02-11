<?php

namespace App\Service\Client;

use App\DTO\LocationDTO;

interface GeocodingClientInterface
{
    public function search(string $city): LocationDTO;
}
