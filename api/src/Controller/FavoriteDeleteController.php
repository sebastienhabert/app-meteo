<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\FavoriteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/favorites/{id}', methods: ['DELETE'])]
final class FavoriteDeleteController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private FavoriteRepository $favoriteRepository,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $id = $request->attributes->get('id');
        $favorite = $this->favoriteRepository->find($id);

        if (!$favorite) {
            return $this->json(['error' => 'Favorite not found'], 404);
        }

        $this->entityManager->remove($favorite);
        $this->entityManager->flush();

        return $this->json(null, 204);
    }
}
