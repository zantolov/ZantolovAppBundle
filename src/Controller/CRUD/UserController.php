<?php

namespace Zantolov\AppBundle\Controller\CRUD;

use FOS\UserBundle\Doctrine\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Zantolov\AppBundle\Controller\EntityCrudController;
use Zantolov\AppBundle\Entity\User;
use Zantolov\AppBundle\Form\UserType;

/**
 * @Route("/admin/users")
 */
class UserController extends EntityCrudController
{

    protected function getEntityClass()
    {
        return 'ZantolovAppBundle:User';
    }


    /**
     * @Route("/", name="app.users")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        return parent::baseIndexAction($request);
    }

    /**
     * @Route("/", name="app.users.create")
     * @Method("POST")
     * @Template("ZantolovAppBundle:CRUD/User:new.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::baseCreateAction($request, new User(), 'app.users.show');
    }

    /**
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    public function createCreateForm($entity)
    {
        return parent::createBaseCreateForm($entity, new UserType(), $this->generateUrl('app.users.create'));
    }

    /**
     * @Route("/new", name="app.users.new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        return parent::baseNewAction(new User());
    }

    /**
     * @Route("/{id}", name="app.users.show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        return parent::baseShowAction($id);
    }

    /**
     * @Route("/{id}/edit", name="app.users.edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        return parent::baseEditAction($id);
    }

    /**
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    public function createEditForm($entity)
    {
        return parent::createBaseEditForm($entity, new UserType(), $this->generateUrl('app.users.update', array('id' => $entity->getId())));
    }

    /**
     * @Route("/{id}", name="app.users.update")
     * @Method("PUT")
     * @Template("ZantolovAppBundle:CRUD/User:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {

        $redirectUrl = $this->generateUrl('app.users.edit', array('id' => $id));
        $entity = $this->getEntityById($id);
        $editForm = $this->createEditForm($entity)->handleRequest($request);

        /** @var UserManager $userManager */
        $userManager = $this->get('fos_user.user_manager');

        if ($editForm->isValid()) {
            $userManager->updateUser($entity);
            $updated = $this->translate('Updated');
            $this->get('session')->getFlashBag()->add('success', $updated);
            return $this->redirect($redirectUrl);
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $this->createDeleteForm($id)->createView(),
        );
    }

    /**
     * @Route("/{id}", name="app.users.delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        return parent::baseDeleteAction($request, $id, $this->generateUrl('app.users'));
    }

    /**
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    public function createDeleteForm($id)
    {
        return parent::baseCreateDeleteForm($this->generateUrl('app.users.delete', array('id' => $id)));
    }
}
