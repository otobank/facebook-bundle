<?php

namespace Otobank\Bundle\FacebookBundle;

use Otobank\Bundle\FacebookBundle\Security\Factory\FacebookFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FacebookBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new FacebookFactory());
    }
}
