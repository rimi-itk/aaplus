<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class VindueTiltagDetailType.
 */
class VindueTiltagDetailType extends KlimaskaermTiltagDetailType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
      ->remove('andelAfArealDerEfterisoleres')
      ->remove('tIndeC')
      ->remove('tUdeC')
      ->remove('tOpvarmningTimerAar')
      ->remove('andelAfArealDerEfterisoleres')
      ->add('orientering', null, [
        'required' => true,
      ])
      ->add('glasandel', 'percent', ['scale' => 2])
      ;

        $this->insertAfter($builder, $builder->get('uNyWM2K'), [
      ['solenergitransmittansEks', 'percent', ['scale' => 2]],
      ['solenergitransmittansNy', 'percent', ['scale' => 2, 'required' => false]],
    ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
      'data_class' => 'AppBundle\Entity\VindueTiltagDetail',
    ]);
    }

    public function getName()
    {
        return 'appbundle_vinduetiltagdetail';
    }
}
