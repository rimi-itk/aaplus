<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BaselineKorrektionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datoForImplementering')
            ->add('beskrivelse')
            ->add('korrektionEl')
            ->add('korrektionGAF')
            ->add('korrektionGUF')
            ->add('kilde')
            ->add('indvirkning');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\BaselineKorrektion',
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_baselinekorrektion';
    }
}
