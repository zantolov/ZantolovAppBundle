<?php

namespace Zantolov\AppBundle\Form;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Zantolov\BlogBundle\Entity\Category;
use Zantolov\MediaBundle\Form\EventSubscriber\ImagesChooserFieldAdderSubscriber;

class ContentModuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('body', 'ckeditor')
            ->add('active', null, array('required' => false));

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Zantolov\AppBundle\Entity\ContentModule'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'zantolov_appbundle_contentmodule';
    }
}
