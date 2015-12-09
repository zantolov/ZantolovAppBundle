<?php

namespace Zantolov\AppBundle\Service;


use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Zantolov\AppBundle\Controller\Traits\CrudControllerTrait;

class CrudRouteBuilderService
{
    private $config;
    private $controllerName;

    /**
     * CrudRouteBuilderService constructor.
     * @param $config
     * @param $controllerName
     */
    public function __construct($config = null, $controllerName = null)
    {
        if (!is_null($config)) {
            $this->config = $config;
        }
        if (!is_null($controllerName)) {
            $this->controllerName = $controllerName;
        }
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param mixed $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return mixed
     */
    public function getControllerName()
    {
        return $this->controllerName;
    }

    /**
     * @param mixed $controllerName
     */
    public function setControllerName($controllerName)
    {
        $this->controllerName = $controllerName;
    }

    public function buildCrudRouteCollection()
    {
        $collection = new RouteCollection();

        foreach ([CrudControllerTrait::$ROUTE_INDEX  => '/',
                  CrudControllerTrait::$ROUTE_NEW    => '/new',
                  CrudControllerTrait::$ROUTE_EDIT   => '/{id}/edit',
                  CrudControllerTrait::$ROUTE_DELETE => '/{id}/delete',
                  CrudControllerTrait::$ROUTE_SHOW   => '/{id}',
                 ] as $key => $path) {

            if (isset($this->config[$key])) {
                $route = new Route($path, ['_controller' => $this->controllerName . ':' . $key,]);
                $route->setMethods(['GET']);
                $collection->add($this->config[$key], $route);
            }
        }

        // Update
        $route = new Route('/{id}/update', ['_controller' => $this->controllerName . ':update',]);
        $route->setMethods(['POST', 'PUT']);
        $collection->add($this->config[CrudControllerTrait::$ROUTE_UPDATE], $route);

        // Create
        $route = new Route('/create', ['_controller' => $this->controllerName . ':create',]);
        $route->setMethods(['POST']);
        $collection->add($this->config[CrudControllerTrait::$ROUTE_CREATE], $route);

        // Destroy
        $route = new Route('/{id}/destroy', ['_controller' => $this->controllerName . ':destroy',]);
        $route->setMethods(['DELETE', 'POST']);
        $collection->add($this->config[CrudControllerTrait::$ROUTE_DESTROY], $route);

        return $collection;

    }

}