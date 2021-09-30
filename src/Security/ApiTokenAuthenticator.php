<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ApiTokenAuthenticator extends AbstractGuardAuthenticator
{
    // Est-ce que c'est une requête d'authentification que je sais gérer?
    public function supports(Request $request)
    {
        return $request->headers->has('Authorization');
    }

    // Step2: Je suis une requête d'authentification, je vais récupérer les credentials dans la requête
    public function getCredentials(Request $request)
    {
        return [
            'token' => $request->headers->get('Authorization')
        ];
    }

    // Step3: J'ai les credentials, je fais une requête pour vérifier si il y a une ligne qui match
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $apiUser = $userProvider->loadUserByIdentifier($credentials['token']);

        return $apiUser;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // Pas besoin de vérifier un mot de passe dans le cas d'une api via token
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // todo
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        // todo
    }

    // Step4: Cette méthode est appelée quand getUser() ne trouve pas de user
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse(['error' => 'Authentication required'], Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        // todo
    }
}
