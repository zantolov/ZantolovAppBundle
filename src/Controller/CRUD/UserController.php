<?php

namespace Zantolov\AppBundle\Controller\CRUD;

use FOS\UserBundle\Doctrine\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use Zantolov\AppBundle\Controller\Traits\CrudControllerTrait;
use Zantolov\AppBundle\Controller\Traits\EasyControllerTrait;
use Zantolov\AppBundle\Entity\User;
use Zantolov\AppBundle\Form\UserType;

/**
 * @Route("/users")
 */
class UserController extends Controller
{

    use EasyControllerTrait;
    use CrudControllerTrait;

    public static function getCrudId()
    {
        return 'app.user';
    }


    /**
     * @return string
     */
    protected function getEntityName()
    {
        return 'ZantolovAppBundle:User';
    }

    protected function getNewEntity()
    {
        /** @var UserManager $userManager */
        $userManager = $this->get('fos_user.user_manager');

        /** @var User $entity */
        $entity = $userManager->createUser();
        return $entity;
    }

    /**
     * @return AbstractType
     */
    protected function getCreateFormType()
    {
        $params = ['requiredPassword' => true];
        $formType = new UserType($params);
        $formType->setRoles($this->getAvailableRolesOptions());
        return $formType;
    }

    protected function getAvailableRolesOptions()
    {
        $roles = $this->getManager()->getRepository('ZantolovAppBundle:Role')->findAll();
        $roleOptions = [];
        foreach ($roles as $role) {
            $roleOptions[$role->name] = $role->name;
        }
        return $roleOptions;
    }

    protected function getEditFormType()
    {
        $params = ['requiredPassword' => false];
        $formType = new UserType($params);
        $formType->setRoles($this->getAvailableRolesOptions());
        return $formType;
    }

    /**
     * @return UserManager
     */
    protected function getUserManager()
    {
        return $this->get('fos_user.user_manager');
    }

    /**
     * @Template
     */
    public function createAction(Request $request)
    {
        $entity = $this->getNewEntity();
        $form = $this->getCreateForm($entity)->handleRequest($request);

        if ($form->isValid()) {
            $this->getUserManager()->updateUser($entity);
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
     * @Route("/{id}", name="app.users.update")
     * @Method("PUT")
     * @Template("ZantolovAppBundle:CRUD/User:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $entity = $this->getEntityById($id);
        $form = $this->getEditForm($entity)->handleRequest($request);

        if ($form->isValid()) {
            $this->getUserManager()->updateUser($entity);
            $this->sessionFlash()->add('success', $this->translate('Updated'));
            return $this->redirectToRoute(static::getRoutesConfig()[static::$ROUTE_EDIT], ['id' => $entity->getId()]);
        }

        $form = $form->createView();
        $crudId = static::getCrudId();

        $data = compact('form', 'crudId', 'id');
        $data = $this->beforeRender($data, self::$ROUTE_UPDATE);
        return $data;
    }

}
