<?php

namespace Zantolov\AppBundle\Entity\Traits;

use Gedmo\Sluggable\Util\Urlizer;

trait SluggableTrait
{

    /**
     * @var string
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function generateSlug()
    {
        $slug = Urlizer::transliterate($this->getSluggableProperty());
        if (method_exists($this, 'getId')) {
            $slug = $slug . '-' . $this->getId();
        }
        return $slug;
    }
}