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

class GraddageFordelingType extends AbstractType
{
    private $graddagefordeling;

    // @TODO public function __construct(GraddageFordeling $graddagefordeling)
    public function __construct()
    {
        // $this->graddagefordeling = $graddagefordeling;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$this->graddagefordeling->isNormAar()) {
            $builder->add('titel', null, ['label' => false]);
        }
        $builder
            ->add('januar')
            ->add('februar')
            ->add('marts')
            ->add('april')
            ->add('maj')
            ->add('juni')
            ->add('juli')
            ->add('august')
            ->add('september')
            ->add('oktober')
            ->add('november')
            ->add('december');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\GraddageFordeling',
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_graddagefordeling';
    }
}
