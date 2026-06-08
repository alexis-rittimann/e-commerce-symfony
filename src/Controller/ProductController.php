<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Service\CartManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/produit/{id}', name: 'app_product_show', requirements: ['id' => '\d+'])]
    public function show(Product $product, ProductRepository $productRepository, CartManager $cartManager): Response
    {
        // Produits « à associer » : les autres produits, limités à 3.
        $related = array_filter(
            $productRepository->findAll(),
            static fn (Product $p): bool => $p->getId() !== $product->getId(),
        );

        // Quantité déjà au panier (0 si non connecté ou produit absent du panier).
        $cartQuantity = 0;
        $user = $this->getUser();
        if ($user instanceof User) {
            $cartQuantity = $cartManager->getProductQuantity($user, $product);
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'related' => \array_slice($related, 0, 3),
            'cartQuantity' => $cartQuantity,
        ]);
    }
}
