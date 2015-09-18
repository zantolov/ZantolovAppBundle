<?php

namespace Zantolov\AppBundle\Service;


use Doctrine\Common\Collections\ArrayCollection;

class AssetsService
{
    /**
     * @var ArrayCollection
     */
    private $css;
    private $js;


    public function __construct()
    {
        $this->css = new ArrayCollection();
        $this->js = new ArrayCollection();
    }

    /**
     * @param $css
     */
    public function addCss($css)
    {
        if (!$this->css->contains($css)) {
            $this->css->add($css);
        }
    }

    /**
     * @param $js
     */
    public function addJs($js)
    {
        if (!$this->js->contains($js)) {
            $this->js->add($js);
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getCss()
    {
        return $this->css;
    }

    /**
     * @return ArrayCollection
     */
    public function getJs()
    {
        return $this->js;
    }
}