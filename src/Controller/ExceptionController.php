<?php

namespace Zantolov\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\TemplateReferenceInterface;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;


class ExceptionController extends \Symfony\Bundle\TwigBundle\Controller\ExceptionController
{

    /**
     * @param Request $request
     * @param string $format
     * @param int $code An HTTP response status code
     * @param bool $showException
     *
     * @return TemplateReferenceInterface
     */
    protected function _findTemplate(Request $request, $format, $code, $showException)
    {
        $template = new TemplateReference('ZantolovAppBundle', 'Exception', 'error', 'html', 'twig');
        return $template;
    }
}