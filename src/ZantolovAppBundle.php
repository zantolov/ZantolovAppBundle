<?php

namespace Zantolov\AppBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Zantolov\AppBundle\DependencyInjection\Compiler\DoctrineEntityListenerCompilerPass;
use Zantolov\AppBundle\DependencyInjection\Compiler\MenuCompilerPass;

class ZantolovAppBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new MenuCompilerPass());
        $container->addCompilerPass(new DoctrineEntityListenerCompilerPass());
    }

    public function getParent()
    {
        return 'FOSUserBundle';
    }

    public static function getDependentBundles()
    {
        return [
            new \Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new \Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new \Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new \Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
        ];
    }
}

