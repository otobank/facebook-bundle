<?php

namespace Otobank\Bundle\FacebookBundle\Security\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

class FacebookFactory implements SecurityFactoryInterface
{
    public function create(
        ContainerBuilder $container,
        $id,
        $config,
        $userProvider,
        $defaultEntryPoint
    ) {
        $providerId = 'security.authentication.provider.facebook.' . $id;
        $container
            ->setDefinition($providerId, new DefinitionDecorator('otobank_facebook.authentication_provider'))
            // ->replaceArgument(0, new Reference($userProvider))
        ;
        $listenerId = 'security.authentication.listener.facebook.' . $id;
        $listener = $container->setDefinition($listenerId, new DefinitionDecorator('otobank_facebook.authentication_listener'));

        return [
            $providerId,
            $listenerId,
            $defaultEntryPoint,
        ];
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'facebook';
    }

    public function addConfiguration(NodeDefinition $node)
    {
    }
}
