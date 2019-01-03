<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Form\Type;

use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
      ->add('username', 'filter_text', ['condition_pattern' => FilterOperands::STRING_BOTH, 'label' => false])
      ->add('firstname', 'filter_text', ['condition_pattern' => FilterOperands::STRING_BOTH, 'label' => false])
      ->add('lastname', 'filter_text', ['condition_pattern' => FilterOperands::STRING_BOTH, 'label' => false])
      ->add('phone', 'filter_text', ['condition_pattern' => FilterOperands::STRING_BOTH, 'label' => false])
//      ->add('groups', 'filter_entity', array('class' => 'AppBundle:Group', 'label' => false))
      ->add('Søg', 'submit')
    ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
      'data_class' => 'AppBundle\Entity\User',
    ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_user';
    }
}
