<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Entity\User;
use App\Enum\OrderStatus;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Gère le panier de l'utilisateur.
 * Rappel : le panier EST une commande (Order) au statut CART. À la validation,
 * elle bascule en VALIDATED et devient une commande de l'historique.
 */
class CartManager
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly OrderRepository $orderRepository,
    ) {
    }

    /**
     * Récupère le panier courant de l'utilisateur (le crée s'il n'existe pas).
     */
    public function getCart(User $user): Order
    {
        $cart = $this->findCart($user);

        if (null === $cart) {
            $cart = (new Order())
                ->setCustomer($user)
                ->setStatus(OrderStatus::CART)
                ->setCreatedAt(new \DateTimeImmutable());
            $this->em->persist($cart);
        }

        return $cart;
    }

    /**
     * Définit la quantité d'un produit dans le panier.
     * Quantité <= 0 => le produit est retiré du panier.
     */
    public function setProductQuantity(User $user, Product $product, int $quantity): void
    {
        $cart = $this->getCart($user);
        $item = $this->findItem($cart, $product);

        if ($quantity <= 0) {
            if (null !== $item) {
                $cart->removeItem($item); // orphanRemoval => suppression en base
            }
        } else {
            if (null === $item) {
                $item = (new OrderItem())->setProduct($product);
                $cart->addItem($item);
            }
            $item->setQuantity($quantity);
            $item->setUnitPrice($product->getPrice()); // prix figé au moment de l'ajout
        }

        $this->em->flush();
    }

    /**
     * Quantité actuelle d'un produit dans le panier (0 si absent).
     * Ne crée pas de panier (lecture seule, utilisé sur la page produit).
     */
    public function getProductQuantity(User $user, Product $product): int
    {
        $cart = $this->findCart($user);

        return null !== $cart ? ($this->findItem($cart, $product)?->getQuantity() ?? 0) : 0;
    }

    /**
     * Vide le panier en supprimant la commande en cours.
     */
    public function clear(User $user): void
    {
        $cart = $this->findCart($user);

        if (null !== $cart) {
            $this->em->remove($cart);
            $this->em->flush();
        }
    }

    /**
     * Valide le panier : il devient une commande de l'historique.
     */
    public function validate(User $user): Order
    {
        $cart = $this->getCart($user);
        $cart->setStatus(OrderStatus::VALIDATED);
        $cart->setValidatedAt(new \DateTimeImmutable());
        $this->em->flush(); // flush une 1re fois pour obtenir l'id auto-généré

        $cart->setNumber(sprintf('CMD-%s-%04d', $cart->getValidatedAt()->format('Y'), $cart->getId()));
        $this->em->flush();

        return $cart;
    }

    private function findCart(User $user): ?Order
    {
        return $this->orderRepository->findOneBy([
            'customer' => $user,
            'status' => OrderStatus::CART,
        ]);
    }

    private function findItem(Order $cart, Product $product): ?OrderItem
    {
        foreach ($cart->getItems() as $item) {
            if ($item->getProduct()->getId() === $product->getId()) {
                return $item;
            }
        }

        return null;
    }
}
