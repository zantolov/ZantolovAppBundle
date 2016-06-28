<?php

namespace Zantolov\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Role
 * @package Zantolov\AppBundle\Entity
 * @ORM\Entity()
 */
class Role
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @var
     * @ORM\Column(type="string", nullable=false)
     */
    public $name;

}