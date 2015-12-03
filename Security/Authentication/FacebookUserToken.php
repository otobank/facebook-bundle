<?php

namespace Otobank\Bundle\FacebookBundle\Security\Authentication;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class FacebookUserToken extends AbstractToken
{
    public function __construct(array $roles = [])
    {
        parent::__construct($roles);

        $this->setAuthenticated(count($roles) > 0);
    }

    public function getCredentials()
    {
        return '';
    }
}
