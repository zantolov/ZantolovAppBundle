<?php

namespace Zantolov\AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class MenuCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('zantolov.app.menu_builder')) {
            return;
        }

        $definition = $container->getDefinition('zantolov.app.menu_builder');

        foreach ($container->findTaggedServiceIds('zantolov.app.menu') as $id => $attributes) {
            $definition->addMethodCall('addMenu', array(new Reference($id)));
        }
    }
}
