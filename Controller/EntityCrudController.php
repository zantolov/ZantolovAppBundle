<?php

namespace Zantolov\AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Zantolov\BlogBundle\Repository\CategoryRepository;

abstract class EntityCrudController extends Controller
{

    protected $enabledFilters = array('parent');

    /**
     * Return entity class name
     * Example:'ZantolovAppBundle:User'
     * @return string
     */
    abstract protected function getEntityClass();

    abstract protected function createCreateForm($entity);

    abstract protected function createDeleteForm($id);

    abstract protected function createEditForm($entity);

    /**
     * @return EntityManager
     */
    protected function getManager()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->getManager()->getRepository($this->getEntityClass());
    }


    /**
     * Returns entity object if found by ID, or throws exception
     * @param $id
     * @return object
     */
    protected function getEntityById($id)
    {
        $entity = $this->getRepository()->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Entity Not Found');
        }

        return $entity;
    }

    /**
     * @return array
     */
    protected function baseIndexAction(Request $request, $filters = null)
    {
        if (is_array($filters) && count($filters) > 0) {
            return array(
                'entities' => $this->getRepository()->findBy($filters),
                'filters'  => $filters,
            );
        } else {
            return array(
                'entities' => $this->getRepository()->findAll()
            );
        }

    }

    /**
     * @param $entity
     * @param $type
     * @param $submitUrl
     * @return \Symfony\Component\Form\Form
     */
    protected function createBaseEditForm($entity, $type, $submitUrl)
    {
        $form = $this->createForm($type, $entity, array(
            'action' => $submitUrl,
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update', 'attr' => array('class' => 'btn btn-success btn-lg')));

        return $form;
    }


    /**
     * @param $entity
     * @return \Symfony\Component\Form\Form
     */
    protected function createBaseCreateForm($entity, $type, $submitUrl)
    {
        $form = $this->createForm($type, $entity, array(
            'action' => $submitUrl,
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'btn btn-success btn-lg')));

        return $form;
    }


    /**
     * @return array
     */
    protected function baseNewAction($entity)
    {
        return array(
            'entity' => $entity,
            'form'   => $this->createCreateForm($entity)->createView(),
        );
    }


    /**
     * @param $id
     * @return array
     */
    protected function baseShowAction($id)
    {
        $entity = $this->getEntityById($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $this->createDeleteForm($id)->createView(),
        );
    }

    /**
     * @param $id
     * @return array
     */
    protected function baseEditAction($id)
    {
        $entity = $this->getEntityById($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $this->createEditForm($entity)->createView(),
            'delete_form' => $this->createDeleteForm($id)->createView(),
        );
    }

    /**
     * @param Request $request
     * @param $id
     * @param $redirectUrl
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function baseUpdateAction(Request $request, $id, $redirectUrl)
    {
        $entity = $this->getEntityById($id);
        $editForm = $this->createEditForm($entity)->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getManager()->flush();
            $this->get('session')->getFlashBag()->add('success', 'Updated');
            return $this->redirect($redirectUrl);
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $this->createDeleteForm($id)->createView(),
        );
    }


    /**
     * @param Request $request
     * @param $entity
     * @param $redirectUrl
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function baseCreateAction(Request $request, $entity, $redirectRouteName)
    {
        $form = $this->createCreateForm($entity)->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Created');

            return $this->redirect($this->generateUrl($redirectRouteName, array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * @param Request $request
     * @param $id
     * @param $redirectUrl
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function baseDeleteAction(Request $request, $id, $redirectUrl)
    {
        $form = $this->createDeleteForm($id)->handleRequest($request);

        if ($form->isValid()) {
            $entity = $this->getEntityById($id);
            $this->getManager()->remove($entity);
            $this->getManager()->flush();
            $this->get('session')->getFlashBag()->add('success', 'Deleted');
        }

        return $this->redirect($redirectUrl);
    }

    /**
     * @param $submitUrl
     * @return \Symfony\Component\Form\Form
     */
    protected function baseCreateDeleteForm($submitUrl)
    {
        return $this->createFormBuilder()
            ->setAction($submitUrl)
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array('class' => 'btn btn-danger btn-sm', 'data-confirm' => 'Are you sure?')))
            ->getForm();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    protected function baseMassAction(Request $request)
    {
        $ids = $request->get('ids');
        $action = $request->get('action');

        if (empty($ids) || empty($action)) {
            return new JsonResponse(array('status' => 0));
        }

        switch ($action) {
            case 'activate':
                $res = $this->getRepository()->massFieldUpdate($ids, 'active', '1');
                break;
            case 'deactivate':
                $res = $this->getRepository()->massFieldUpdate($ids, 'active', '0');
                break;
            default:
                return new JsonResponse(array('status' => 0));
        }

        return new JsonResponse(array('status' => $res));

    }


    /**
     * @param Request $request
     * @param $id
     * @param $direction
     * @return JsonResponse
     * @throws \Exception
     */
    public function baseReorderAction(Request $request, $id, $direction)
    {
        $entity = $this->getEntityById($id);
        switch ($direction) {
            case 'up':
                $entity->setPosition($entity->getPosition() - 1);
                break;
            case 'down':
                $entity->setPosition($entity->getPosition() + 1);
                break;
            default:
                throw new \Exception('Invalid direction');
        }

        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(array(
            'status'      => 1,
            'newPosition' => $entity->getPosition(),
        ));
    }


    /**
     * @return array|null
     */
    protected function processFilters()
    {
        /** @var Request $request */
        $request = $this->get('request_stack')->getCurrentRequest();
        $filters = $request->get('filters');
        if (is_array($filters) && count($filters) > 0) {
            $filters = array_intersect_key($filters, array_flip($this->enabledFilters));
        } else {
            $filters = null;
        }
        return $filters;
    }

}