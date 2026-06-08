<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Service\CartManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/panier')]
#[IsGranted('ROLE_USER')] //tout ce contrôleur exige d'être connecté
final class CartController extends AbstractController
{
    #[Route('', name: 'app_cart')]
    public function index(CartManager $cartManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('cart/index.html.twig', [
            'cart' => $cartManager->getCart($user),
        ]);
    }

    #[Route('/ajouter/{id}', name: 'app_cart_add', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function add(Product $product, Request $request, CartManager $cartManager): Response
    {
        if (!$this->isCsrfTokenValid('add-to-cart', $request->getPayload()->getString('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        /** @var User $user */
        $user = $this->getUser();
        $quantity = $request->getPayload()->getInt('quantity');
        $cartManager->setProductQuantity($user, $product, $quantity);

        $this->addFlash('success', $quantity > 0
            ? 'Votre panier a été mis à jour.'
            : 'Le produit a été retiré du panier.');

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/vider', name: 'app_cart_clear', methods: ['POST'])]
    public function clear(Request $request, CartManager $cartManager): Response
    {
        if (!$this->isCsrfTokenValid('clear-cart', $request->getPayload()->getString('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        /** @var User $user */
        $user = $this->getUser();
        $cartManager->clear($user);
        $this->addFlash('success', 'Votre panier a été vidé.');

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/valider', name: 'app_cart_validate', methods: ['POST'])]
    public function validate(Request $request, CartManager $cartManager): Response
    {
        if (!$this->isCsrfTokenValid('validate-order', $request->getPayload()->getString('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        /** @var User $user */
        $user = $this->getUser();
        $cart = $cartManager->getCart($user);

        if ($cart->getItems()->isEmpty()) {
            $this->addFlash('error', 'Votre panier est vide.');

            return $this->redirectToRoute('app_cart');
        }

        $order = $cartManager->validate($user);
        $this->addFlash('success', sprintf(
            'Commande %s validée ! Merci pour votre achat.',
            $order->getNumber(),
        ));

        return $this->redirectToRoute('app_cart');
    }
}
