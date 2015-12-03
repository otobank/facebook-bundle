<?php

namespace Otobank\Bundle\FacebookBundle\Security\User;

use Facebook\GraphNodes\GraphUser;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class FacebookUser extends GraphUser implements UserInterface, EquatableInterface
{
    /**
     * {@inheritDoc}
     */
    public function getRoles()
    {
        return ['ROLE_FACEBOOK_USER'];
    }

    /**
     * {@inheritDoc}
     */
    public function getPassword()
    {
        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getUsername()
    {
        return $this->getId();
    }

    /**
     * {@inheritDoc}
     */
    public function eraseCredentials()
    {
        // $this->items = [];
    }

    /**
     * {@inheritDoc}
     */
    public function isEqualTo(UserInterface $user)
    {
        if ($user instanceof FacebookUser && $user->getId() === $this->getId()) {
            return true;
        }

        return false;
    }
}
