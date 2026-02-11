<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\FavoriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/favorites', methods: ['GET'])]
final class FavoriteListController extends AbstractController
{
    public function __construct(
        private FavoriteRepository $favoriteRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $favorites = $this->favoriteRepository->findAll();

        $data = array_map(fn($favorite) => [
            'id' => $favorite->getId(),
            'name' => $favorite->getName(),
            'latitude' => $favorite->getLatitude(),
            'longitude' => $favorite->getLongitude(),
        ], $favorites);

        return $this->json($data);
    }
}
