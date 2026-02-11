<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

        $weather = $weatherService->getWeatherByCity($query);

        return $this->json($weather);
    }
}
