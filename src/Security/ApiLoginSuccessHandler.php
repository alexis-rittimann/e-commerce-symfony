<?php

namespace App\Security;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

/**
 * Appelé quand les identifiants de /api/login sont corrects.
 * Si l'utilisateur n'a pas activé son accès API => 403.
 * Sinon, on délègue au handler de Lexik qui génère et renvoie le token JWT.
 */
final class ApiLoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(
        #[Autowire(service: 'lexik_jwt_authentication.handler.authentication_success')]
        private readonly AuthenticationSuccessHandler $lexikSuccessHandler,
    ) {
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        $user = $token->getUser();

        if ($user instanceof User && !$user->isApiAccessEnabled()) {
            return new JsonResponse(
                ['message' => 'Accès API non activé.'],
                Response::HTTP_FORBIDDEN, // 403
            );
        }

        // Identifiants OK + accès activé => Lexik génère le token (200 + { token: ... }).
        return $this->lexikSuccessHandler->onAuthenticationSuccess($request, $token);
    }
}
