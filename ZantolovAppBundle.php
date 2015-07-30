<?php

namespace Zantolov\AppBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ZantolovAppBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}

