<?php

namespace Zantolov\AppBundle\Entity\Interfaces;

/**
 * Entity that has slug
 *
 * Interface Sluggab1leInterface
 * @package Zantolov\AppBundle\Entity\Interfaces
 */
interface SluggableInterface
{

    public function generateSlug();

    public function getSlug();

    public function setSlug($slug);

    public function getSluggableProperty();

}