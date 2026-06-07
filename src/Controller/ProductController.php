<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/produit/{id}', name: 'app_product_show', requirements: ['id' => '\d+'])]
    public function show(Product $product, ProductRepository $productRepository): Response
    {
        // Produits « à associer » : les autres produits, limités à 3.
        $related = array_filter(
            $productRepository->findAll(),
            static fn (Product $p): bool => $p->getId() !== $product->getId(),
        );

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'related' => \array_slice($related, 0, 3),
        ]);
    }
}
