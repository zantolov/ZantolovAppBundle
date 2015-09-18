<?php

namespace Zantolov\AppBundle\Menu;

use Symfony\Component\HttpFoundation\RequestStack;

interface MenuBuilderInterface
{
    function createMenu(RequestStack $requestStack);

    function getOrder();
}