<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\QueryParserService;
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
        private QueryParserService $queryParserService,
    ) {
    }

    public function __invoke(
        Request $request,
    ): JsonResponse {

        $query = $request->query->get('query');

        if (!$query) {
            return $this->json(
                ['error' => 'Query parameter is required'],
                Response::HTTP_BAD_REQUEST
            );
        }

        if ($this->queryParserService->isCoordinates($query)) {
            [$lat, $lon] = $this->queryParserService->extractCoordinates($query);

            $weather = $this->weatherService->getWeatherByCoordinates($lat, $lon);
        } else {
            $weather = $this->weatherService->getWeatherByCity($query);
        }

        return $this->json($weather);
    }
}
