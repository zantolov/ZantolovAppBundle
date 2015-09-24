<?php

namespace Zantolov\AppBundle\Security;

use Zantolov\AppBundle\Controller\Api\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class TokenAuthenticator implements SimplePreAuthenticatorInterface
{
    protected $userProvider;

    public function __construct(TokenUserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function createToken(Request $request, $providerKey)
    {
        // look for an apikey query parameter
        $apiKey = $request->get('token');
        $apiKeyHeader = $request->headers->get('X-Api-Token');

        if (!$apiKey && !$apiKeyHeader) {
            throw new BadCredentialsException('No API key found');
        }

        return new PreAuthenticatedToken(
            'anon.',
            (empty($apiKey) ? $apiKeyHeader : $apiKey),
            $providerKey
        );
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        $userToken = $token->getCredentials();
        $user = $this->userProvider->getUserByToken($userToken);

        if (empty($user)) {
            throw new AuthenticationException(
                sprintf('API Key "%s" does not exist.', $userToken)
            );
        }

        return new PreAuthenticatedToken(
            $user,
            $userToken,
            $providerKey,
            $user->getRoles()
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }


    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse(array(ApiController::KEY_STATUS => ApiController::STATUS_ERROR, ApiController::KEY_MESSAGE => "Authentication Failed."));
    }

}