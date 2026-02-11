<?php

declare(strict_types=1);

namespace App\Service;

use InvalidArgumentException;

final class QueryParserService
{
    public function isCoordinates(string $input): bool
    {
        return preg_match(
            '/^-?\d{1,3}(\.\d+)?\s*,\s*-?\d{1,3}(\.\d+)?$/',
            trim($input)
        ) === 1;
    }

    public function extractCoordinates(string $input): array
    {
        if (!$this->isCoordinates($input)) {
            throw new InvalidArgumentException(sprintf('Input "%s" is not valid coordinates.', $input));
        }

        [$lat, $lon] = explode(',', str_replace(' ', '', $input));

        return [(float) $lat, (float) $lon];
    }
}
