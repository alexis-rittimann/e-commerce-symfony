<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\OrderStatus;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/mon-compte')]
#[IsGranted('ROLE_USER')]
final class AccountController extends AbstractController
{
    #[Route('', name: 'app_account')]
    public function index(OrderRepository $orderRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        // Les commandes passées (validées), de la plus récente à la plus ancienne.
        $orders = $orderRepository->findBy(
            ['customer' => $user, 'status' => OrderStatus::VALIDATED],
            ['validatedAt' => 'DESC'],
        );

        return $this->render('account/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/api', name: 'app_account_api_toggle', methods: ['POST'])]
    public function toggleApi(Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('toggle-api', $request->getPayload()->getString('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        /** @var User $user */
        $user = $this->getUser();
        $user->setApiAccessEnabled(!$user->isApiAccessEnabled());
        $em->flush();

        $this->addFlash('success', $user->isApiAccessEnabled()
            ? 'Votre accès API est désormais activé.'
            : 'Votre accès API est désormais désactivé.');

        return $this->redirectToRoute('app_account');
    }

    #[Route('/supprimer', name: 'app_account_delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $em, TokenStorageInterface $tokenStorage): Response
    {
        if (!$this->isCsrfTokenValid('delete-account', $request->getPayload()->getString('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        /** @var User $user */
        $user = $this->getUser();

        // Supprime l'utilisateur ET ses commandes (cascade remove sur User.orders).
        $em->remove($user);
        $em->flush();

        // Déconnexion : on invalide la session et on vide le token de sécurité.
        $request->getSession()->invalidate();
        $tokenStorage->setToken(null);

        $this->addFlash('success', 'Votre compte a bien été supprimé.');

        return $this->redirectToRoute('app_home');
    }
}
