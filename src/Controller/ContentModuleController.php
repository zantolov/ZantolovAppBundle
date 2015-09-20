<?php

namespace Zantolov\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class ContentModuleController extends Controller
{

    /**
     * @param $id
     * @return array
     * @Template()
     */
    public function renderAction($id)
    {
        $module = $this->getDoctrine()->getManager()->getRepository('ZantolovAppBundle:ContentModule')->findOneBy(array(
            'active' => 1,
            'id'     => $id,
        ));

        return compact('module');
    }

}