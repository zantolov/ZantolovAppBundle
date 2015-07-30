<?php

namespace Zantolov\AppBundle\Twig\Extension;

class FontAwesomeIconsExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('fontAwesome', array($this, 'fontAwesomeFilter'), array('is_safe' => array('html'))),
        );
    }

    public function fontAwesomeFilter($value, $size = 'fa-1x')
    {
        if (true === $value) {
            return '<i class="fa fa-check green ' . $size . '"></i>';
        }

        if (false === $value) {
            return '<i class="fa fa-times red ' . $size . '"></i>';
        }

        return '<i class="fa ' . $value . ' ' . $size . '"></i>';
    }

    public function getName()
    {
        return 'fontAwesome';
    }
}