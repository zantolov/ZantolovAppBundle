<?php

namespace Zantolov\AppBundle\Controller\CRUD;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Zantolov\AppBundle\Controller\Traits\CrudControllerTrait;
use Zantolov\AppBundle\Controller\Traits\EasyControllerTrait;
use Zantolov\AppBundle\Entity\ContentModule;
use Zantolov\AppBundle\Form\ContentModuleType;

class ContentModuleController extends Controller
{
    use EasyControllerTrait;
    use CrudControllerTrait;

    protected $enabledFilters = array('category');

    public static function getCrudId()
    {
        return 'app.content_module';
    }

    /**
     * @return string
     */
    protected function getEntityName()
    {
        return 'ZantolovAppBundle:ContentModule';
    }

    protected function getNewEntity()
    {
        return new ContentModule();
    }

    protected function getCreateFormType()
    {
        throw new \Exception('DEPRECATED');
    }

    /**
     * @return AbstractType
     */
    protected function buildFormType($htmlEditor)
    {
        return new ContentModuleType($htmlEditor);
    }

    /**
     * @param null $entity
     * @return Form
     */
    protected function getCreateForm($entity = null)
    {
        if (is_null($entity)) {
            $entity = $this->getNewEntity();
        }

        $formType = $this->buildFormType($entity->isEditor());

        $form = $this->createForm(
            $formType,
            $entity,
            [
                'action' => $this->generateUrl($this->getRoutesConfig()[self::$ROUTE_CREATE]),
                'method' => 'POST',
            ]
        );

        $this->addSubmitButton($form, 'Create');

        return $form;
    }

    /**
     * @param $entity
     * @return Form
     */
    protected function getEditForm($entity)
    {

        $formType = $this->buildFormType($entity->isEditor());

        $form = $this->createForm(
            $formType,
            $entity,
            [
                'action' => $this->generateUrl($this->getRoutesConfig()[self::$ROUTE_UPDATE], ['id' => $entity->getId()]),
                'method' => 'PUT',
            ]
        );

        $this->addSubmitButton($form, 'Update');

        return $form;
    }

    /**
     * @param FormInterface $form
     * @param string $text
     * @return FormInterface
     */
    protected function addSubmitButton(FormInterface $form, $text = 'Submit')
    {
        $form->add('submit', 'submit', [
                'label' => $this->translate($text),
                'attr'  => [
                    'class' => 'btn btn-success btn-lg'
                ]
            ]
        );
        return $form;
    }


}
