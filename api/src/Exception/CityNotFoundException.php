<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CityNotFoundException extends NotFoundHttpException
{
    public function __construct(string $city)
    {
        parent::__construct(sprintf('City "%s" was not found.', $city));
    }
}
