<?php

use Zantolov\AppBundle\Controller\CRUD\ContentModuleController;
use Zantolov\AppBundle\Service\CrudRouteBuilderService;

$builder = new CrudRouteBuilderService(
    ContentModuleController::getRoutesConfig(),
    'ZantolovAppBundle:CRUD/ContentModule'
);

return $builder->buildCrudRouteCollection();