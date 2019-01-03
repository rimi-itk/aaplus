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
 * Class TekniskIsoleringTiltagDetailType.
 */
class TekniskIsoleringTiltagDetailType extends TiltagDetailType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
      ->add('laastAfEnergiraadgiver', null, [
        'required' => false,
      ])
      ->add('beskrivelseType')
      ->add('komponent', null, [
        'required' => false,
      ])
      ->add('driftstidTAar')
      ->add('udvDiameterMm')
      ->add('eksistIsolMm')
      ->add('tempOmgivelC')
      ->add('tempMedieC')
      ->add('roerlaengdeEllerHoejdeAfVvbM')
      ->add('nyIsolMm')
      ->add('standardinvestKrM2EllerKrM')
      ->add('overskrevetPris')
      ->add('prisfaktor');

        if ($this->isBatchEdit) {
            $builder->add('nyttiggjortVarme', null, [
        'required' => false,
        'placeholder' => '--',
        'empty_data' => null,
      ]);
            $builder->add('type', 'choice', [
        'choices' => [
          'Rør' => 'Rør',
          'Komponenter' => 'Komponenter',
        ],
        'placeholder' => '--',
        'empty_data' => null,
      ]);
        } else {
            $builder->add('nyttiggjortVarme', null, [
        'required' => true,
      ]);
            $builder->add('type', 'choice', [
        'choices' => [
          'Rør' => 'Rør',
          'Komponenter' => 'Komponenter',
        ],
      ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
      'data_class' => 'AppBundle\Entity\TekniskIsoleringTiltagDetail',
    ]);
    }

    public function getName()
    {
        return 'appbundle_tekniskisoleringtiltagdetail';
    }
}
