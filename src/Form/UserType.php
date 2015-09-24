<?php
/**
 * Created by PhpStorm.
 * User: zoka123
 * Date: 24.09.15.
 * Time: 04:36
 */

namespace Zantolov\AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    private $params = array(
        'requiredPassword' => true
    );

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('username')
            ->add('email');

        if ($this->params['requiredPassword'] == false) {
            $builder->add('plainPassword', null, array('required' => false));
        } else {
            $builder->add('plainPassword');
        }

        $builder->add('enabled', null, array('required' => false));

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Zantolov\AppBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'zantolov_appbundle_user';
    }
}