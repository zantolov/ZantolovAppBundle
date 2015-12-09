<?php

namespace Zantolov\AppBundle\Controller\Traits;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

trait EasyControllerTrait
{
    abstract function get($key);

    /**
     * @param $string
     * @return String
     */
    protected function translate($string)
    {
        return $this->get('translator')->trans($string);
    }

    /**
     * @return EntityManager
     */
    protected function getManager()
    {
        return $this->get('doctrine')->getManager();
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository($entityName)
    {
        return $this->getManager()->getRepository($entityName);
    }

    protected function getGlobalparams()
    {
        return [];
    }

    protected function withGlobalParams(array $params)
    {
        return $params + $this->getGlobalparams();
    }

    protected function sessionFlash()
    {
        return $this->get('session')->getFlashBag();
    }

}