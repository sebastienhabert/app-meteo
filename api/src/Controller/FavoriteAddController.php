<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Favorite;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/favorites', methods: ['POST'])]
final class FavoriteAddController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(
        Request $request,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['latitude'], $data['longitude'])) {
            return $this->json(['error' => 'Missing data'], 400);
        }

        $favorite = new Favorite();
        $favorite->setName($data['name'] ?? $data['latitude'] . ', ' . $data['longitude']);
        $favorite->setLatitude((float) $data['latitude']);
        $favorite->setLongitude((float) $data['longitude']);

        $this->entityManager->persist($favorite);
        $this->entityManager->flush();

        return $this->json($favorite, 201);
    }
}
