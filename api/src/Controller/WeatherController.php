<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/weather', methods: ['GET'])]
final class WeatherController extends AbstractController
{
    public function __construct(
        private WeatherService $weatherService,
    ) {
    }

    public function __invoke(
        Request $request,
        WeatherService $weatherService,
    ): JsonResponse {

        $query = $request->query->get('query');

        if (!$query) {
            return $this->json(
                ['error' => 'Query parameter is required'],
                Response::HTTP_BAD_REQUEST
            );
        }

        if ($this->isCoordinates($query)) {
            [$lat, $lon] = $this->extractCoordinates($query);

            $weather = $weatherService->getWeatherByCoordinates($lat, $lon);
        } else {
            $weather = $weatherService->getWeatherByCity($query);
        }

        return $this->json($weather);
    }

    private function isCoordinates(string $input): bool
    {
        return preg_match('/^\d+(\.\d+)?,\s*\d+(\.\d+)?$/', trim($input)) === 1;
    }

    private function extractCoordinates(string $input): array
    {
        [$lat, $lon] = explode(',', trim($input));

        return [(float) $lat, (float) $lon];
    }
}
