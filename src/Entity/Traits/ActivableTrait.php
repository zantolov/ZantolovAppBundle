<?php

namespace Zantolov\AppBundle\Entity\Traits;

trait ActivableTrait
{
    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    protected $active = false;

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }


}