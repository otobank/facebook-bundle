<?php

namespace Otobank\Bundle\FacebookBundle\Security\Firewall;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Otobank\Bundle\FacebookBundle\Security\Authentication\FacebookUserToken;
use Otobank\Bundle\FacebookBundle\Security\User\FacebookUser;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

class FacebookListener implements ListenerInterface
{
    protected $facebook;
    protected $tokenStorage;
    protected $authenticationManager;
    protected $logger;

    public function __construct(
        Facebook $facebook,
        TokenStorageInterface $tokenStorage,
        AuthenticationManagerInterface $authenticationManager,
        LoggerInterface $logger = null
    ) {
        if (is_null($logger)) {
            $logger = new NullLogger();
        }

        $this->facebook = $facebook;
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->logger = $logger;
    }

    public function handle(GetResponseEvent $event)
    {
        $helper = $this->facebook->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();

            if (!$accessToken) {
                return;
            }

            $response = $this->facebook->get('/me', $accessToken);
            $user = $response->getGraphNode(FacebookUser::class);

            $token = new FacebookUserToken();
            $token->setUser($user);

            $authToken = $this->authenticationManager->authenticate($token);
            $this->tokenStorage->setToken($authToken);

            return;
        } catch (FacebookResponseException $e) {
            $this->logger->info(sprintf('FacebookResponseException: %s', $e->getMessage()));
        } catch (FacebookSDKException $e) {
            $this->logger->warning(sprintf('FacebookSDKException: %s', $e->getMessage()));
        } catch (AuthenticationException $e) {
            $this->logger->info(sprintf('AuthenticationException: %s', $e->getMessage()));
        }

        $event->setResponse(new RedirectResponse('/login'));
    }
}
