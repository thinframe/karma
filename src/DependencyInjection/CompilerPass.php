<?php

namespace ThinFrame\Karma\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class CompilerPass
 * @package ThinFrame\Karma\DependencyInjection
 * @since   0.3
 */
class CompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('karma.controller') as $serviceId => $tagOptions) {
            $container->getDefinition('karma.router')->addMethodCall(
                'registerInstantiatedController',
                [new Reference($serviceId)]
            );
        }
    }
}
