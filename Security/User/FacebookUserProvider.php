<?php

namespace Otobank\Bundle\FacebookBundle\Security\User;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Otobank\Bundle\FacebookBundle\Security\User\FacebookUser;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class FacebookUserProvider implements UserProviderInterface
{
    /** @var Facebook */
    private $facebook;

    /** @var LoggerInterface */
    private $logger;

    /**
     * Constructor
     *
     * @param Facebook $facebook
     * @param LoggerInterface $logger
     */
    public function __construct(Facebook $facebook, LoggerInterface $logger = null)
    {
        if (is_null($logger)) {
            $logger = new NullLogger();
        }

        $this->facebook = $facebook;
        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        try {
            $response = $this->facebook->get('/' . $username);

            return $response->getGraphNode(FacebookUser::class);
        } catch (FacebookResponseException $e) {
            $this->logger->info(sprintf('FacebookResponseException: %s', $e->getMessage()));
        } catch (FacebookSDKException $e) {
            $this->logger->warning(sprintf('FacebookSDKException: %s', $e->getMessage()));
        }

        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof FacebookUser) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return $class === FacebookUser::class;
    }
}
