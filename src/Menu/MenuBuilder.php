<?php

namespace Zantolov\AppBundle\Menu;

use Doctrine\Common\Collections\ArrayCollection;
use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RequestStack;

class MenuBuilder extends ContainerAware
{

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var ArrayCollection
     */
    protected $menus;


    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
        $this->menus = new ArrayCollection();
    }

    public function addMenu(MenuBuilderInterface $menuBuilderInterface)
    {
        $this->menus->add($menuBuilderInterface);
    }

    public function createMainMenu(RequestStack $requestStack)
    {
        $mainMenu = $this->factory->createItem('root');
        $mainMenu->setChildrenAttribute('class', 'nav navbar-nav');

        $items = array();

        $items[0]['modules'] = $this->factory->createItem('Modules', array('route' => 'app.content_module.index'))->setAttribute('icon', 'fa fa-plug');
        $items[1]['users'] = $this->factory->createItem('Users', array('route' => 'app.users'))->setAttribute('icon', 'fa fa-user');


        $items[20]['logout'] = $this->factory->createItem('Logout', array('route' => 'fos_user_security_logout'))->setAttribute('icon', 'fa fa-lock');


        /** @var MenuBuilderInterface $menu */
        foreach ($this->menus as $menu) {
            foreach ($menu->createMenu($requestStack) as $menuItem) {
                $items[$menu->getOrder()][] = $menuItem;
            }
        }

        ksort($items);

        foreach ($items as $menuItem) {
            foreach ($menuItem as $i) {
                $mainMenu->addChild($i);
            }
        }

        return $mainMenu;

    }
}