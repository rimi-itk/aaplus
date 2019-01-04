<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\Group;
use AppBundle\Entity\User;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EntityFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
            ->add('username', TextFilterType::class, ['condition_pattern' => FilterOperands::STRING_CONTAINS, 'label' => false])
            ->add('firstname', TextFilterType::class, ['condition_pattern' => FilterOperands::STRING_CONTAINS, 'label' => false])
            ->add('lastname', TextFilterType::class, ['condition_pattern' => FilterOperands::STRING_CONTAINS, 'label' => false])
            ->add('phone', TextFilterType::class, ['condition_pattern' => FilterOperands::STRING_CONTAINS, 'label' => false])
            // ->add('groups', EntityFilterType::class, array('class' => Group::class, 'label' => false))
            ->add('Søg', SubmitType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
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
