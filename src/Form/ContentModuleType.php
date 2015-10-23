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

    protected $htmlEditor = true;

    /**
     * ContentModuleType constructor.
     * @param bool $htmlEditor
     */
    public function __construct($htmlEditor)
    {
        $this->htmlEditor = $htmlEditor;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name')
            ->add('editor', null, array('label' => 'Use HTML editor', 'required' => false))
            ->add('active', null, array('required' => false));

        if ($this->htmlEditor === true) {
            $builder->add('body', 'ckeditor');
        } else {
            $builder->add('body', null, ['attr' => ['rows' => 10]]);
        }
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
