<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class ApiController extends AbstractController
{
    /**
     * Connexion à l'API. Le corps de la requête ({ username, password }) est traité
     * par le firewall json_login : ce code n'est jamais exécuté.
     */
    #[Route('/login', name: 'api_login', methods: ['POST'])]
    public function login(): never
    {
        throw new \LogicException('Cette route est interceptée par le firewall json_login.');
    }

    /**
     * Liste des produits, au format du schéma Product des specs techniques.
     */
    #[Route('/products', name: 'api_products', methods: ['GET'])]
    public function products(ProductRepository $productRepository): JsonResponse
    {
        // Sécurité supplémentaire : un token a pu être émis puis l'accès désactivé.
        $user = $this->getUser();
        if (!$user instanceof User || !$user->isApiAccessEnabled()) {
            return new JsonResponse(['message' => 'Accès API non activé.'], Response::HTTP_FORBIDDEN);
        }

        $data = array_map(
            static fn (Product $p): array => [
                'id' => $p->getId(),
                'name' => $p->getName(),
                'shortDescription' => $p->getShortDescription(),
                'fullDescription' => $p->getFullDescription(),
                'price' => (float) $p->getPrice(), // decimal (string) -> Float comme dans le schéma
                'picture' => $p->getPicture(),
            ],
            $productRepository->findAll(),
        );

        return $this->json($data, Response::HTTP_OK, [], [
            'json_encode_options' => \JSON_UNESCAPED_UNICODE | \JSON_UNESCAPED_SLASHES,
        ]);
    }
}
