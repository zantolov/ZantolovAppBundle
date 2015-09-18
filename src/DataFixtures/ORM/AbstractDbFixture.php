<?php

namespace Zantolov\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractDbFixture extends AbstractFixture implements ContainerAwareInterface, FixtureInterface, OrderedFixtureInterface
{

    public function getOrder()
    {
        return 1;
    }

    /**
     * @var ContainerInterface $container
     */
    protected $container;

    public function __construct(ContainerInterface $container = null)
    {
        $this->setContainer($container);
    }

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}