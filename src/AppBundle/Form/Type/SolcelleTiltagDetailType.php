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
 * Class SolcelleTiltagDetailType.
 */
class SolcelleTiltagDetailType extends TiltagDetailType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
      ->add('anlaegsstoerrelseKWp')
      ->add('produktionKWh')
      ->add('tilNettetPct', 'percent', ['scale' => 2])
      ->add('inverterskift1Aar')
      ->add('inverterskift2Aar')
      ->add('investeringKr')
      ->add('screeningOgProjekteringKr')
      ->add('omkostningTilMaalerKr')
      ->add('forringetYdeevnePrAar', 'percent', ['scale' => 2]);

        if ($this->authorizationChecker && $this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $builder
        ->add('energiprisstigningPctPrAar', 'percent', ['scale' => 2])
        ->add('salgsprisFoerste10AarKrKWh')
        ->add('salgsprisEfter10AarKrKWh');
        } else {
            // We need these hidden fields to persist default values.
            $builder
        ->add('energiprisstigningPctPrAar', 'hidden')
        ->add('salgsprisFoerste10AarKrKWh', 'hidden')
        ->add('salgsprisEfter10AarKrKWh', 'hidden');
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
      'data_class' => 'AppBundle\Entity\SolcelleTiltagDetail',
    ]);
    }

    public function getName()
    {
        return 'appbundle_solcelletiltagdetail';
    }
}
