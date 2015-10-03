<?php

namespace Zantolov\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

abstract class DefaultEntityCrudController extends EntityCrudController
{
    const ROUTE_INDEX = 'index';
    const ROUTE_SHOW = 'show';
    const ROUTE_EDIT = 'edit';
    const ROUTE_DELETE = 'delete';
    const ROUTE_UPDATE = 'update';
    const ROUTE_CREATE = 'create';
    const ROUTE_PREFIX = '';

    abstract function getNewEntity();

    abstract function getNewEntityType();

    public function indexAction(Request $request)
    {
        return parent::baseIndexAction($request);
    }

    public function createAction(Request $request)
    {
        return parent::baseCreateAction($request, $this->getNewEntity(), static::ROUTE_PREFIX . static::ROUTE_SHOW);
    }


    /**
     * @param $entity
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createCreateForm($entity)
    {
        return parent::createBaseCreateForm(
            $entity,
            $this->getNewEntityType(),
            $this->generateUrl(static::ROUTE_PREFIX . static::ROUTE_CREATE)
        );
    }

    /**
     * @return array
     */
    public function newAction()
    {
        return parent::baseNewAction($this->getNewEntity());
    }


    /**
     * @param $id
     * @return array
     */
    public function showAction($id)
    {
        return parent::baseShowAction($id);
    }

    /**
     * @param $id
     * @return array
     */
    public function editAction($id)
    {
        return parent::baseEditAction($id);
    }


    /**
     * @param $entity
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createEditForm($entity)
    {
        return parent::createBaseEditForm(
            $entity,
            $this->getNewEntityType(),
            $this->generateUrl(static::ROUTE_PREFIX . static::ROUTE_UPDATE, array('id' => $entity->getId()))
        );
    }


    /**
     * @param Request $request
     * @param $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, $id)
    {
        return parent::baseUpdateAction(
            $request,
            $id,
            $this->generateUrl(static::ROUTE_PREFIX . static::ROUTE_EDIT, array('id' => $id))
        );
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        return parent::baseDeleteAction($request, $id, static::ROUTE_PREFIX . static::ROUTE_INDEX);
    }


    /**
     * @param mixed $id
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createDeleteForm($id)
    {
        return parent::baseCreateDeleteForm($this->generateUrl(static::ROUTE_PREFIX . static::ROUTE_DELETE, array('id' => $id)));
    }

}
