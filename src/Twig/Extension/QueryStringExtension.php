<?php

namespace Zantolov\AppBundle\Twig\Extension;

class QueryStringExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('queryString', array($this, 'queryStringFilter')),
        );
    }

    public function queryStringFilter($array)
    {
        return http_build_query($array);
    }

    public function getName()
    {
        return 'querystring';
    }
}