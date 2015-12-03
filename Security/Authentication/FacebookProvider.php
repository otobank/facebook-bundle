<?php

namespace Otobank\Bundle\FacebookBundle\Security\Authentication;

use Otobank\Bundle\FacebookBundle\Security\Authentication\FacebookUserToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class FacebookProvider implements AuthenticationProviderInterface
{
    private $userProvider;

    public function __construct(UserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByUsername($token->getUsername());

        if ($user) {
            $authencatedToken = new FacebookUserToken([
                'ROLE_FACEBOOK_USER',
            ]);
            $authencatedToken->setUser($user);

            return $authencatedToken;
        }

        throw new AuthenticationException('Facebook authentication failed.');
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof FacebookUserToken;
    }
}
