<?php

namespace Zantolov\AppBundle\Controller\CRUD;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Zantolov\AppBundle\Controller\EntityCrudController;
use Zantolov\AppBundle\Entity\ContentModule;
use Zantolov\AppBundle\Form\ContentModuleType;

/**
 * @Route("/admin/content-module")
 */
class ContentModuleController extends EntityCrudController
{

    protected function getEntityClass()
    {
        return 'ZantolovAppBundle:ContentModule';
    }


    /**
     * @Route("/", name="app.content-module")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        return parent::baseIndexAction($request);
    }

    /**
     * @Route("/", name="app.content-module.create")
     * @Method("POST")
     * @Template("ZantolovAppBundle:CRUD/ContentModule:new.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::baseCreateAction($request, new ContentModule(), 'app.content-module.show');
    }

    /**
     * @param ContentModule $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    public function createCreateForm($entity)
    {
        return parent::createBaseCreateForm($entity, new ContentModuleType(), $this->generateUrl('app.content-module.create'));
    }

    /**
     * @Route("/new", name="app.content-module.new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        return parent::baseNewAction(new ContentModule());
    }

    /**
     * @Route("/{id}", name="app.content-module.show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        return parent::baseShowAction($id);
    }

    /**
     * @Route("/{id}/edit", name="app.content-module.edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        return parent::baseEditAction($id);
    }

    /**
     * @param ContentModule $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    public function createEditForm($entity)
    {
        return parent::createBaseEditForm($entity, new ContentModuleType(), $this->generateUrl('app.content-module.update', array('id' => $entity->getId())));
    }

    /**
     * @Route("/{id}", name="app.content-module.update")
     * @Method("PUT")
     * @Template("ZantolovAppBundle:CRUD/ContentModule:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::baseUpdateAction($request, $id, $this->generateUrl('app.content-module.edit', array('id' => $id)));
    }

    /**
     * @Route("/{id}", name="app.content-module.delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        return parent::baseDeleteAction($request, $id, $this->generateUrl('app.content-module'));
    }

    /**
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    public function createDeleteForm($id)
    {
        return parent::baseCreateDeleteForm($this->generateUrl('app.content-module.delete', array('id' => $id)));
    }
}
