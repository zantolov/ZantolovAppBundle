<?php

namespace Zantolov\AppBundle\Controller\Traits;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

trait CrudControllerTrait
{
    public static $ROUTE_INDEX = 'index';
    public static $ROUTE_EDIT = 'edit';
    public static $ROUTE_UPDATE = 'update';
    public static $ROUTE_NEW = 'new';
    public static $ROUTE_CREATE = 'create';
    public static $ROUTE_DELETE = 'delete';
    public static $ROUTE_DESTROY = 'destroy';
    public static $ROUTE_SHOW = 'show';
    public static $ROUTE_PATH = 'path';

    /**
     * @return string
     */
    public static function getCrudId()
    {
        throw new \Exception('CRUD ID must be defined in controller extension');
    }

    /**
     * @return string
     */
    abstract protected function getEntityName();

    abstract protected function getNewEntity();

    /**
     * @return AbstractType
     */
    abstract protected function getCreateFormType();

    public function beforeRender(array $params, $action)
    {
        return $params;
    }

    /**
     * @return AbstractType
     */
    protected function getEditFormType()
    {
        return $this->getCreateFormType();
    }

    /**
     * @param $route
     * @param array $parameters
     * @param bool $referenceType
     * @return mixed
     */
    abstract protected function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH);

    /**
     * @param $type
     * @param null $data
     * @param array $options
     * @return Form
     */
    abstract protected function createForm($type, $data = null, array $options = array());

    /**
     * @param $text
     * @return string
     */
    abstract protected function translate($string, $params = [], $domain = null);

    /** @return EntityManager */
    abstract protected function getManager();

    /** @return FlashBag */
    abstract protected function sessionFlash();

    /** @return RedirectResponse */
    abstract protected function redirectToRoute($route, array $parameters = [], $status = 302);

    /**
     * @return Form
     */
    protected function getCreateForm($entity = null)
    {
        if (is_null($entity)) {
            $entity = $this->getNewEntity();
        }

        $form = $this->createForm(
            $this->getCreateFormType(),
            $entity,
            [
                'action' => $this->generateUrl($this->getRoutesConfig()[self::$ROUTE_CREATE]),
                'method' => 'POST',
            ]
        );

        $form->add('submit', 'submit', [
                'label' => $this->translate('Create'),
                'attr'  => [
                    'class' => 'btn btn-success btn-lg'
                ]
            ]
        );

        return $form;
    }

    /**
     * @return Form
     */
    protected function getEditForm($entity)
    {
        $form = $this->createForm(
            $this->getEditFormType(),
            $entity,
            [
                'action' => $this->generateUrl($this->getRoutesConfig()[self::$ROUTE_UPDATE], ['id' => $entity->getId()]),
                'method' => 'PUT',
            ]
        );

        $form->add('save', SubmitType::class, [
                'label' => $this->translate('Update'),
                'attr'  => [
                    'class' => 'btn btn-success btn-lg'
                ]
            ]
        );

        return $form;
    }

    /**
     * @return Form
     */
    protected function getDeleteForm($entity)
    {
        $delete = $this->translate('Delete');
        $confirm = $this->translate('Are you sure?');
        return $this->createFormBuilder()
            ->setAction($this->generateUrl($this->getRoutesConfig()[self::$ROUTE_DESTROY], ['id' => $entity->getId()]))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => $delete, 'attr' => array('class' => 'btn btn-danger', 'data-confirm' => $confirm)))
            ->getForm();
    }

    /**
     * Returns array of routes for current CRUD controller
     * @return array
     */
    public static function getRoutesConfig()
    {
        $getRoute = function ($part) {
            return sprintf('%s.%s', static::getCrudId(), $part);
        };

        return [
            self::$ROUTE_PATH    => static::getCrudId(),
            self::$ROUTE_INDEX   => $getRoute('index'),
            self::$ROUTE_EDIT    => $getRoute('edit'),
            self::$ROUTE_UPDATE  => $getRoute('update'),
            self::$ROUTE_NEW     => $getRoute('new'),
            self::$ROUTE_CREATE  => $getRoute('create'),
            self::$ROUTE_DELETE  => $getRoute('delete'),
            self::$ROUTE_SHOW    => $getRoute('show'),
            self::$ROUTE_DESTROY => $getRoute('destroy'),
        ];
    }

    /**
     * @param $id
     * @return object
     * @throws \Exception
     */
    protected function getEntityById($id)
    {
        $entity = $this->getEntityRepository()->find($id);

        if (!$entity) {
            throw new \Exception($this->translate('Entity Not Found'));
        }

        return $entity;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getEntityRepository()
    {
        return $this->getRepository(
            $this->getEntityName()
        );
    }

    /**
     * @Template
     * @return array
     */
    public function indexAction()
    {
        $items = $this->getEntityRepository()->findAll();
        $crudId = static::getCrudId();
        return compact('items', 'crudId');
    }

    /**
     * @Template
     * @return array
     */
    public function newAction()
    {
        $form = $this->getCreateForm()->createView();
        $crudId = static::getCrudId();

        $data = compact('form', 'crudId');
        $data = $this->beforeRender($data, self::$ROUTE_NEW);
        return $data;
    }

    /**
     * @Template
     * @return array
     */
    public function createAction(Request $request)
    {
        $entity = $this->getNewEntity();
        $form = $this->getCreateForm($entity)->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getManager();
            $em->persist($entity);
            $em->flush();
            $this->sessionFlash()->add('success', $this->translate('Created'));

            return $this->redirectToRoute(static::getRoutesConfig()[static::$ROUTE_EDIT], ['id' => $entity->getId()]);

        }

        $crudId = static::getCrudId();
        $form = $form->createView();

        $data = compact('form', 'crudId');
        $data = $this->beforeRender($data, self::$ROUTE_CREATE);
        return $data;
    }

    /**
     * @Template
     * @return array
     */
    public function editAction($id)
    {
        $form = $this->getEditForm($this->getEntityById($id))->createView();
        $crudId = static::getCrudId();

        $data = compact('form', 'crudId', 'id');
        $data = $this->beforeRender($data, self::$ROUTE_EDIT);
        return $data;
    }

    /**
     * @Template()
     * @return array
     */
    public function updateAction(Request $request, $id)
    {
        $entity = $this->getEntityById($id);
        $form = $this->getEditForm($entity)->handleRequest($request);

        if ($form->isValid()) {
            $this->getManager()->flush();
            $this->sessionFlash()->add('success', $this->translate('Updated'));
            return $this->redirectToRoute(static::getRoutesConfig()[static::$ROUTE_EDIT], ['id' => $entity->getId()]);
        }

        $form = $form->createView();
        $crudId = static::getCrudId();

        $data = compact('form', 'crudId', 'id');
        $data = $this->beforeRender($data, self::$ROUTE_UPDATE);
        return $data;
    }

    /**
     * @Template
     * @return array
     */
    public function deleteAction($id)
    {
        $entity = $this->getEntityById($id);
        $form = $this->getDeleteForm($entity)->createView();
        $crudId = static::getCrudId();

        $data = compact('form', 'entity', 'crudId', 'id');
        $data = $this->beforeRender($data, self::$ROUTE_DELETE);
        return $data;
    }

    /**
     * @return array
     */
    public function destroyAction($id)
    {
        $entity = $this->getEntityById($id);
        $this->getManager()->remove($entity);
        $this->getManager()->flush();
        $this->sessionFlash()->add('success', $this->translate('Deleted'));
        return $this->redirectToRoute(static::getRoutesConfig()[static::$ROUTE_INDEX]);
    }


    /**
     * @Template
     * @return array
     */
    public function showAction($id)
    {
        $item = $this->getEntityById($id);
        $crudId = static::getCrudId();

        $data = compact('item', 'crudId', 'id');
        $data = $this->beforeRender($data, self::$ROUTE_SHOW);
        return $data;
    }

}
