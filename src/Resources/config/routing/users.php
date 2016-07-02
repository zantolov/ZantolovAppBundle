<?php

use Zantolov\AppBundle\Controller\CRUD\UserController;
use Zantolov\AppBundle\Service\CrudRouteBuilderService;

$builder = new CrudRouteBuilderService(
    UserController::getRoutesConfig(),
    'ZantolovAppBundle:CRUD/User'
);

return $builder->buildCrudRouteCollection();