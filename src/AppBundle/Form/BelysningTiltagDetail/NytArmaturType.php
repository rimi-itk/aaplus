<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Form\BelysningTiltagDetail;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NytArmaturType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('arbejdeOmfang')
            ->add('nyLyskildeAntal')
            ->add('wattage')
            ->add('nyeForkoblingerAntal')
            ->add('pris')
            ->add('noter')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\BelysningTiltagDetail\NytArmatur',
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_belysningtiltagdetail_nytarmatur';
    }
}
