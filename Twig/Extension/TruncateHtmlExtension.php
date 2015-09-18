<?php
/**
 * Truncate Html string without stripping tags
 * register in Resources/config/services.yml with:
 * services:
 * truncatehtml.twig.extension:
 * class: Radley\TwigExtensionBundle\Extension\TruncateHtmlExtension
 * tags:
 * - { name: twig.extension }
 *
 * Usage:
 * {{ htmlstring|truncatehtml(500)|raw }}
 */

namespace Zantolov\AppBundle\Twig\Extension;

class TruncateHtmlExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'truncatehtml';
    }

    public function getFilters()
    {
        return array('truncatehtml' => new \Twig_Filter_Method($this, 'truncatehtml'));
    }

    public function truncatehtml($html, $limit, $endchar = '&hellip;')
    {
        $output = new TruncateHtmlString($html, $limit);
        return $output->cut() . $endchar;
    }
}