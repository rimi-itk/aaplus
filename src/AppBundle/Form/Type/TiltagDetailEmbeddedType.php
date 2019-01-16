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

/**
 * Class TiltagDetailEmbeddedType.
 */
class TiltagDetailEmbeddedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tilvalgt', 'checkbox', ['label' => false, 'required' => false, 'attr' => [
            'class' => 'tilvalgt',
        ]]);
        $builder->add('batchEdit', 'checkbox', ['label' => 'common.choose', 'required' => false, 'mapped' => true, 'attr' => [
            'class' => 'js-batch-edit',
        ]]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
      [
          'data_class' => 'AppBundle\Entity\TiltagDetail',
      ]
    );
    }

    public function getName()
    {
        return 'appbundle_tiltagdetail';
    }
}
