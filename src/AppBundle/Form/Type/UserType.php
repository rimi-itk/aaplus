<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
      ->add('enabled', null, ['label' => 'user.enabled'])
      ->add('email')
      ->add('plainpassword')
      ->add('firstname')
      ->add('lastname')
      ->add('phone')
      ->add('segmenter', null, ['by_reference' => false, 'expanded' => true, 'multiple' => true])
      ->add('groups', null, ['by_reference' => false, 'expanded' => true, 'multiple' => true])
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
